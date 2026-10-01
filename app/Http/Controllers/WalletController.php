<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function show(Request $request): View
    {
        return view('wallet.show', [
            'user' => $request->user(),
            'networkName' => config('blockchain.network_name'),
            'contractAddress' => config('blockchain.contract_address'),
        ]);
    }

    public function link(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required|string|max:64',
        ]);

        $request->user()->update([
            'wallet_address' => strtolower($request->wallet_address),
        ]);

        return back()->with('success', 'Wallet Address berhasil diperbarui.');
    }
}
