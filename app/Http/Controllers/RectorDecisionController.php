<?php

namespace App\Http\Controllers;

use App\Models\Ijazah;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RectorDecisionController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isRole('rektor'), 403);
        $user = $request->user();
        
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $query = Ijazah::query()
            ->where(function ($decisionQuery) use ($user) {
                $decisionQuery->where('approval_data->approved_rektor_by', $user->id)
                    ->orWhere(function ($rejectQuery) use ($user) {
                        $rejectQuery->where('approval_data->rejected_by', $user->id)
                            ->where('approval_data->rejected_role', 'rektor');
                    });
            });
            
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ijazah', 'like', "%{$search}%")
                  ->orWhere('payload_data->nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        if ($statusFilter) {
            if ($statusFilter === 'disetujui') {
                $query->where('approval_data->approved_rektor_by', $user->id);
            } elseif ($statusFilter === 'ditolak') {
                $query->where('approval_data->rejected_by', $user->id)->where('approval_data->rejected_role', 'rektor');
            }
        }

        $decisions = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('rector-decisions.index', compact('decisions'));
    }

    public function show(Request $request, Ijazah $ijazah): View
    {
        abort_unless($request->user()->isRole('rektor'), 403);

        $isApprovedByYou = $ijazah->approved_rektor_by === $request->user()->id;
        $isRejectedByYou = $ijazah->rejected_by === $request->user()->id && $ijazah->rejected_role === 'rektor';

        abort_unless($isApprovedByYou || $isRejectedByYou, 403);

        return view('rector-decisions.show', [
            'ijazah' => $ijazah,
            'decision' => [
                'status' => $isRejectedByYou ? 'Ditolak' : 'Disetujui',
                'badge' => $isRejectedByYou ? 'text-bg-danger' : 'text-bg-success',
                'decided_at' => $isRejectedByYou ? $ijazah->rejected_at : $ijazah->approved_rektor_at,
                'description' => $isRejectedByYou ? $ijazah->catatan : 'Ijazah telah diverifikasi dan disetujui oleh Rektor untuk dilanjutkan ke tahap Admin.',
            ],
        ]);
    }
}
