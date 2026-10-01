<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::orderBy('id', 'desc')->get();
        return view('workflows.index', compact('workflows'));
    }

    public function create()
    {
        $roles = \App\Models\User::select('role')->distinct()->pluck('role')->filter(fn($r) => $r !== 'admin')->values();
        return view('workflows.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'steps' => 'required|array',
            'steps.*' => 'required|string',
        ]);

        // Clean steps
        $stepsArray = array_values(array_filter(array_map('trim', array_map('strtolower', $request->steps))));

        // Hapus 'admin' jika user secara tidak sengaja/sengaja memasukkannya
        $stepsArray = array_values(array_filter($stepsArray, fn($role) => $role !== 'admin'));

        if (count($stepsArray) < 1) {
            return back()->with('error', 'Workflow minimal harus memiliki 1 role persetujuan selain Admin (misal: Rektor/Akademik).');
        }

        // Pastikan admin selalu ada di tahapan terakhir mutlak untuk eksekusi blockchain
        $stepsArray[] = 'admin';

        Workflow::create([
            'steps' => $stepsArray,
            'is_active' => false,
        ]);

        return redirect()->route('workflows.index')->with('success', 'Workflow baru berhasil dibuat. Silakan sinkronisasi dengan Blockchain untuk mengaktifkannya.');
    }

    public function destroy(Workflow $workflow)
    {
        if ($workflow->is_active) {
            return back()->with('error', 'Tidak dapat menghapus Workflow yang sedang aktif.');
        }
        
        $workflow->delete();
        return redirect()->route('workflows.index')->with('success', 'Workflow berhasil dihapus.');
    }

    public function activate(Workflow $workflow)
    {
        Workflow::query()->update(['is_active' => false]);
        $workflow->update(['is_active' => true]);
        return response()->json(['success' => true, 'message' => 'Workflow berhasil diaktifkan.']);
    }

    public function getSigners(Workflow $workflow)
    {
        $addresses = [];
        foreach ($workflow->steps as $role) {
            $user = \App\Models\User::where('role', $role)->whereNotNull('wallet_address')->first();
            if (!$user) {
                return response()->json(['error' => "User dengan role '{$role}' belum memiliki Wallet Address terdaftar."], 400);
            }
            $addresses[] = $user->wallet_address;
        }
        return response()->json(['signers' => $addresses]);
    }
}
