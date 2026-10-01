<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ijazah;
use App\Models\RevokeRequest;
use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $query = Ijazah::query();

        if ($user->role === 'akademik') {
            $query->where('created_by', $user->id);
        } elseif ($user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('current_approver_role', $user->role)
                  ->orWhere('approval_data->signatures->' . $user->role, '!=', null)
                  ->orWhere('approval_data->rejected_role', $user->role);
            });
        }

        // Limit data ijazah di dashboard

        $myDecisions = collect();
        if ($user->role !== 'akademik' && $user->role !== 'admin') {
            $myDecisions = Ijazah::query()
                ->where(function ($q) use ($user) {
                    $q->whereNotNull('approval_data->signatures->' . $user->role)
                      ->orWhere('approval_data->rejected_role', $user->role);
                })
                ->latest('updated_at')
                ->limit(3)
                ->get();
        }

        return view('dashboard', [
            'ijazahs' => $query->latest()->limit(3)->get(),
            'myDecisions' => $myDecisions,
            'stats' => [
                'total' => Ijazah::count(),
                'draft' => Ijazah::where('status', 'Draft')->count(),
                'pending_my_approval' => Ijazah::where('current_approver_role', $user->role)->count(),
                'pending_admin' => Ijazah::where('status', 'Pending Upload')->orWhere('current_approver_role', 'admin')->count(),
                'aktif' => Ijazah::where('status', 'Aktif')->count(),
                'ditolak' => Ijazah::where('status', 'Ditolak')->count(),
                'revoked' => Ijazah::where('status', 'Revoked')->count(),
                'blockchain' => Ijazah::where('blockchain_status', 'Uploaded')->count(),
                'verifications' => VerificationLog::count(),
                'revoke_pending' => RevokeRequest::whereIn('status', ['Pending Rektor', 'Pending Akademik', 'Approved'])->count(),
            ],
            'blockchainConnection' => [
                'connected' => filled(config('blockchain.contract_address')),
                'network' => config('blockchain.network_name'),
                'contract' => config('blockchain.contract_address'),
            ],
            'logs' => ActivityLog::with('user')->latest()->limit(3)->get(),
        ]);
    }
}
