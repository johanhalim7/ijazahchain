<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ijazah;
use App\Models\RevokeRequest;
use App\Services\DiplomaHashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevokeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $query = RevokeRequest::with(['ijazah', 'requester']);

        if ($search) {
            $query->whereHas('ijazah', function ($q) use ($search) {
                $q->where('nomor_ijazah', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $requests = $query->latest()->paginate(10)->withQueryString();
        $activeIjazahs = Ijazah::where('status', 'Aktif')->orderBy('payload_data->nama')->get();

        return view('revoke.index', compact('requests', 'activeIjazahs'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isRole('admin'), 403);
        $data = $request->validate([
            'ijazah_id' => ['required', 'exists:ijazahs,id'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);
        $ijazah = Ijazah::findOrFail($data['ijazah_id']);
        abort_unless($ijazah->status === 'Aktif', 422);

        $activeWorkflow = \App\Models\Workflow::where('is_active', true)->first();
        if (!$activeWorkflow) {
            return back()->with('error', 'Belum ada Workflow (Alur Persetujuan) yang diaktifkan oleh Admin.');
        }
        $steps = $activeWorkflow->steps;
        $firstRole = $steps[0] ?? 'admin';

        $revoke = RevokeRequest::create([
            'ijazah_id' => $ijazah->id,
            'workflow_id' => $activeWorkflow->id,
            'requested_by' => $request->user()->id,
            'reason' => $data['reason'],
            'status' => 'Pending ' . ucfirst($firstRole),
            'current_approver_role' => $firstRole,
        ]);
        $this->log($request, 'REQUEST_REVOKE', "Pengajuan revoke ijazah {$ijazah->nomor_ijazah} dibuat.");

        return redirect()->route('revoke.show', $revoke)->with('success', 'Pengajuan revoke dikirim untuk diproses.');
    }

    public function show(RevokeRequest $revoke, DiplomaHashService $hashService): View
    {
        $revoke->load('ijazah', 'requester', 'workflow');

        return view('revoke.show', [
            'revoke' => $revoke,
            'regeneratedHash' => $hashService->hash($revoke->ijazah),
            'typedData' => $hashService->typedData($revoke->ijazah, 'revoke'),
        ]);
    }

    public function approve(Request $request, RevokeRequest $revoke, DiplomaHashService $hashService): RedirectResponse
    {
        $data = $request->validate([
            'wallet_address' => ['required', 'string', 'max:64'],
            'signature' => ['required', 'string', 'max:512'],
        ]);
        $role = $request->user()->role;
        $this->ensureWalletMatchesAuthenticatedUser($request, $data['wallet_address']);
        
        $revoke->load('ijazah', 'workflow');
        abort_if($hashService->hash($revoke->ijazah) !== $revoke->ijazah->hash, 422, 'Hash database tidak identik.');
        abort_unless($revoke->ijazah->status === 'Aktif', 422);
        
        $workflowSteps = $revoke->workflow ? $revoke->workflow->steps : [];
        if (empty($workflowSteps)) {
            abort(500, 'Workflow tidak ditemukan pada pengajuan ini.');
        }
        
        abort_unless($revoke->current_approver_role === $role, 403, 'Belum giliran Anda untuk menandatangani.');

        $signatures = $revoke->signatures ?: [];
        $signatures[$role] = [
            'wallet_address' => $data['wallet_address'],
            'signature' => $data['signature'],
            'signed_at' => now()->toISOString(),
            'hash' => $revoke->ijazah->hash,
        ];

        $currentIndex = array_search($role, $workflowSteps);
        if ($currentIndex !== false && isset($workflowSteps[$currentIndex + 1])) {
            $nextRole = $workflowSteps[$currentIndex + 1];
            $revoke->update([
                'status' => 'Pending ' . ucfirst($nextRole),
                'current_approver_role' => $nextRole,
                'signatures' => $signatures,
            ]);
        } else {
            // Tahap akhir workflow (Admin)
            $revoke->update([
                'status' => 'Approved',
                'current_approver_role' => null,
                'signatures' => $signatures,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);
        }

        $this->log($request, 'APPROVE_REVOKE_'.$role, "{$role} menandatangani revoke ijazah {$revoke->ijazah->nomor_ijazah}.");

        return back()->with('success', 'Approval revoke dan digital signature berhasil disimpan.');
    }

    public function execute(Request $request, RevokeRequest $revoke): RedirectResponse
    {
        abort_unless($request->user()->isRole('admin'), 403);
        abort_unless($revoke->status === 'Approved' && isset(($revoke->signatures ?: [])['admin']), 403);
        $data = $request->validate(['tx_hash' => ['required', 'string', 'max:100']]);
        $revoke->load('ijazah');
        $revoke->update(['status' => 'Completed', 'tx_hash' => $data['tx_hash']]);
        
        $blockchain_data = $revoke->ijazah->blockchain_data ?? [];
        $blockchain_data['revoke_tx_hash'] = $data['tx_hash'];
        
        $revoke->ijazah->update([
            'status' => 'Revoked', 
            'blockchain_status' => 'Revoked', 
            'blockchain_data' => $blockchain_data
        ]);
        $this->log($request, 'EXECUTE_REVOKE', "Revoke ijazah {$revoke->ijazah->nomor_ijazah} dieksekusi di blockchain.");

        return redirect()->route('revoke.show', $revoke)->with('success', 'Ijazah berhasil direvoke dan transaction hash tersimpan.');
    }

    public function reject(Request $request, RevokeRequest $revoke): RedirectResponse
    {
        abort_unless($revoke->current_approver_role === $request->user()->role, 403, 'Belum giliran Anda untuk menolak.');
        $revoke->update(['status' => 'Rejected', 'current_approver_role' => null]);
        $this->log($request, 'REJECT_REVOKE', "Permintaan revoke #{$revoke->id} ditolak.");

        return redirect()->route('revoke.index')->with('success', 'Permintaan revoke ditolak.');
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
            'Wallet MetaMask tidak sesuai dengan wallet resmi yang terdaftar di akun Anda.'
        );
    }
}
