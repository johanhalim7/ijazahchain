<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ijazah;
use App\Models\Workflow;
use App\Services\DiplomaHashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function show(Ijazah $ijazah, DiplomaHashService $hashService): View
    {
        return view('approvals.show', [
            'ijazah' => $ijazah,
            'regeneratedHash' => $hashService->hash($ijazah),
            'typedData' => $hashService->typedData($ijazah, 'approve'),
        ]);
    }

    public function approve(Request $request, Ijazah $ijazah, DiplomaHashService $hashService): RedirectResponse
    {
        $data = $request->validate([
            'wallet_address' => ['required', 'string', 'max:64'],
            'signature' => ['required', 'string', 'max:512'],
        ]);
        
        $role = $request->user()->role;
        $this->ensureWalletMatchesAuthenticatedUser($request, $data['wallet_address']);
        
        $currentHash = $hashService->hash($ijazah);
        abort_if($currentHash !== $ijazah->hash, 422, 'Hash database tidak identik dengan hasil regenerasi.');

        $approval_data = $ijazah->approval_data ?? [];
        $workflowSteps = $approval_data['workflow_steps'] ?? [];
        
        if (empty($workflowSteps)) {
            abort(500, 'Workflow tidak ditemukan pada ijazah ini.');
        }

        // Cek apakah ini giliran user tersebut
        abort_unless($ijazah->current_approver_role === $role, 403, 'Belum giliran Anda untuk menandatangani.');

        // Simpan signature
        $signatures = $approval_data['signatures'] ?? [];
        $signatures[$role] = [
            'wallet_address' => $data['wallet_address'],
            'signature' => $data['signature'],
            'signed_at' => now()->toISOString(),
            'hash' => $ijazah->hash,
        ];
        $approval_data['signatures'] = $signatures;

        // Tentukan siapa giliran selanjutnya
        $currentIndex = array_search($role, $workflowSteps);
        if ($currentIndex !== false && isset($workflowSteps[$currentIndex + 1])) {
            $nextRole = $workflowSteps[$currentIndex + 1];
            $ijazah->status = 'Pending ' . ucfirst($nextRole);
            $ijazah->current_approver_role = $nextRole;
        } else {
            // Jika tidak ada next role, berarti sudah selesai semua tahapan (seharusnya Admin yang terakhir)
            if ($role === 'admin') {
                $ijazah->status = 'Pending Upload';
                $ijazah->current_approver_role = null;
            } else {
                $ijazah->status = 'Pending Admin';
                $ijazah->current_approver_role = 'admin';
            }
        }

        // Catat jejak audit
        $approval_data['approved_'.$role.'_at'] = now()->toDateTimeString();
        $approval_data['approved_'.$role.'_by'] = $request->user()->id;
        
        // Bersihkan data penolakan jika sebelumnya ditolak lalu direvisi
        if ($role === $workflowSteps[0]) {
            $approval_data['rejected_by'] = null;
            $approval_data['rejected_role'] = null;
            $approval_data['rejected_at'] = null;
        }

        $ijazah->approval_data = $approval_data;
        $ijazah->save();

        $this->log($request, 'APPROVE_'.$role, "{$role} menandatangani ijazah {$ijazah->nomor_ijazah}.");

        return back()->with('success', 'Digital signature tersimpan dan status approval diperbarui.');
    }

    public function reject(Request $request, Ijazah $ijazah): RedirectResponse
    {
        $data = $request->validate(['catatan' => ['required', 'string', 'max:1000']]);
        
        $role = $request->user()->role;
        abort_unless($ijazah->current_approver_role === $role, 403, 'Belum giliran Anda untuk menolak.');

        $approval_data = $ijazah->approval_data ?? [];
        $approval_data['catatan'] = $data['catatan'];
        $approval_data['rejected_by'] = $request->user()->id;
        $approval_data['rejected_role'] = $role;
        $approval_data['rejected_at'] = now()->toDateTimeString();
        
        $ijazah->update([
            'status' => 'Ditolak',
            'current_approver_role' => null,
            'approval_data' => $approval_data,
        ]);
        
        $this->log($request, 'REJECT_IJAZAH', "Ijazah {$ijazah->nomor_ijazah} ditolak oleh {$role}: {$data['catatan']}");

        return redirect()->route('dashboard')->with('success', 'Ijazah ditolak dan dikembalikan untuk direvisi.');
    }

    public function upload(Request $request, Ijazah $ijazah): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);
        abort_unless(isset(($ijazah->approval_data['signatures'] ?: [])['admin']), 403, 'Admin belum menandatangani ijazah ini.');
        
        $data = $request->validate([
            'tx_hash' => ['required', 'string', 'max:100'],
            'block_number' => ['nullable', 'integer'],
        ]);

        $blockchain_data = $ijazah->blockchain_data ?? [];
        $blockchain_data['tx_hash'] = $data['tx_hash'];
        $blockchain_data['block_number'] = $data['block_number'] ?? null;

        $ijazah->update([
            'status' => 'Aktif',
            'blockchain_status' => 'Uploaded',
            'current_approver_role' => null,
            'blockchain_data' => $blockchain_data,
        ]);
        
        $this->log($request, 'UPLOAD_BLOCKCHAIN', "Hash ijazah {$ijazah->nomor_ijazah} disimpan ke blockchain.");

        return redirect()->route('ijazahs.show', $ijazah)->with('success', 'Ijazah aktif, transaction hash tersimpan, dan QR verification dibuat.');
    }

    private function log(Request $request, string $activity, string $description): void
    {
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function ensureWalletMatchesAuthenticatedUser(Request $request, string $walletAddress): void
    {
        $registeredWallet = $request->user()->wallet_address;

        abort_unless($registeredWallet, 403, 'Anda belum mendaftarkan Wallet Address di profil akun Anda.');
        abort_unless(
            strtolower($registeredWallet) === strtolower($walletAddress),
            403,
            'Wallet MetaMask (' . substr($walletAddress, 0, 6) . '...) tidak sesuai dengan wallet resmi di akun Anda.'
        );
    }
}
