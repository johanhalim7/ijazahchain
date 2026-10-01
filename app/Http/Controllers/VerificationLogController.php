<?php

namespace App\Http\Controllers;

use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = VerificationLog::query()
            ->when($request->filled('q'), fn ($query) => $query->where(function ($inner) use ($request) {
                $inner->where('hash', 'like', '%'.$request->q.'%')
                    ->orWhere('tx_hash', 'like', '%'.$request->q.'%')
                    ->orWhere('ip_address', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('result'), fn ($query) => $query->where('result', $request->result))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('verification-logs.index', compact('logs'));
    }
}
