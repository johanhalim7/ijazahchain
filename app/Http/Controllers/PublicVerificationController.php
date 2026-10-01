<?php

namespace App\Http\Controllers;

use App\Models\Ijazah;
use App\Models\VerificationLog;
use App\Services\DiplomaHashService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicVerificationController extends Controller
{
    public function landing(): View
    {
        return view('public.home', [
            'stats' => [
                'total' => Ijazah::count(),
                'verified' => Ijazah::where('status', 'Aktif')->count(),
                'blockchain' => Ijazah::where('blockchain_status', 'Uploaded')->count(),
            ],
        ]);
    }

    public function form(Request $request, DiplomaHashService $hashService): View
    {
        // Jika dari scan QR Code (membawa parameter hash dan nomor_ijazah), langsung proses verifikasi
        if ($request->filled('hash') && $request->filled('nomor_ijazah')) {
            $request->merge(['method' => 'hash']);
            return $this->verify($request, $hashService);
        }

        return view('public.verify', [
            'nomor_ijazah' => $request->query('nomor_ijazah'),
            'hash' => $request->query('hash'),
        ]);
    }

    public function verify(Request $request, DiplomaHashService $hashService): View
    {
        $method = $request->input('method', 'data');
        $hash = null;
        $ijazah = null;

        if ($method === 'file') {
            $request->validate(['document' => ['required', 'file', 'mimes:pdf', 'max:5120']]);
            $uploadedFileHash = hash_file('sha256', $request->file('document')->getRealPath());
            $ijazah = Ijazah::where('file_hash', $uploadedFileHash)->first();
            
            // Jika PDF palsu (tidak ada di database), tampilkan hash PDF tersebut agar kolom tidak kosong
            if (!$ijazah) {
                $hash = $uploadedFileHash;
            }
        } elseif ($method === 'hash') {
            $request->validate(['hash' => ['required', 'string', 'size:64']]);
            $hash = strtolower($request->hash);
            $ijazah = Ijazah::where('hash', $hash)->first();
        } elseif ($method === 'nomor') {
            $request->validate(['nomor_ijazah' => ['required', 'string']]);
            $ijazah = Ijazah::where('nomor_ijazah', $request->nomor_ijazah)->first();
            if ($ijazah) {
                $hash = $ijazah->hash;
            } else {
                $hash = '-';
            }
        }

        $dbTampered = false;
        $currentDbHash = null;
        if ($ijazah) {
            // Tamper check: Calculate composite hash based on CURRENT DB data & file hash
            $currentDbHash = $hashService->hash($ijazah);
            if (!hash_equals(strtolower($ijazah->hash), strtolower($currentDbHash))) {
                $dbTampered = true;
            }
            
            // Set the hash parameter for the view based on what the user provided or the DB hash
            if ($method === 'file') {
                $hash = $ijazah->hash;
            }
        }

        $result = 'INVALID';
        $hashMatch = false;
        if ($ijazah) {
            $hashMatch = hash_equals(strtolower($ijazah->hash), strtolower($hash));
        }

        if ($ijazah && $hashMatch) {
            if ($ijazah->status === 'Revoked' || $ijazah->blockchain_status === 'Revoked') {
                $result = 'REVOKED';
            } elseif ($ijazah->status === 'Aktif' && $ijazah->blockchain_status === 'Uploaded') {
                $result = 'VALID';
            }
        }

        VerificationLog::create([
            'verification_type' => $method,
            'input' => json_encode($request->except(['_token', 'document']), JSON_UNESCAPED_UNICODE),
            'hash' => $hash,
            'blockchain_hash' => $ijazah?->hash,
            'tx_hash' => $ijazah?->tx_hash,
            'result' => $result,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'verified_at' => now(),
        ]);

        return view('public.result', compact('result', 'ijazah', 'hash', 'method', 'dbTampered', 'currentDbHash'));
    }
}
