@extends('layouts.app')
@section('title','Detail Revoke - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <div class="text-danger small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;"><i class="bi bi-shield-slash-fill me-1"></i> Keamanan Ekstrem</div>
        <h1 class="h3 fw-bold text-dark mb-0">Detail Permintaan Pencabutan (Revoke)</h1>
        <p class="text-secondary mb-0 mt-1">Status dan alur persetujuan pencabutan dokumen ijazah.</p>
    </div>
    <a href="{{ route('revoke.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm hover-lift fw-medium text-primary">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Revoke
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">Informasi Ijazah & Alasan</h2>
                @php
                    $statusClass = 'bg-secondary bg-opacity-10 text-secondary border-secondary';
                    $icon = 'bi-hourglass-split';
                    if ($revoke->status === 'Pending Rektor' || $revoke->status === 'Pending Akademik' || $revoke->status === 'Pending Admin') {
                        $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning';
                    } elseif ($revoke->status === 'Revoked') {
                        $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger';
                        $icon = 'bi-slash-circle-fill';
                    } elseif ($revoke->status === 'Ditolak') {
                        $statusClass = 'bg-dark bg-opacity-10 text-dark border-dark';
                        $icon = 'bi-x-circle-fill';
                    }
                @endphp
                <span class="badge {{ $statusClass }} border border-opacity-25 px-3 py-2 rounded-pill fw-medium fs-6 shadow-sm">
                    <i class="bi {{ $icon }} me-1"></i> {{ $revoke->status }}
                </span>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded-4 p-3 bg-light border-0 h-100">
                        <div class="text-secondary small fw-medium mb-1">Nomor Ijazah</div>
                        <div class="fw-bold text-dark fs-5">{{ $revoke->ijazah->nomor_ijazah }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-4 p-3 bg-light border-0 h-100">
                        <div class="text-secondary small fw-medium mb-1">Identitas Lulusan</div>
                        <div class="fw-bold text-dark fs-5">{{ $revoke->ijazah->nama }}</div>
                        <div class="text-secondary small">{{ $revoke->ijazah->prodi }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border border-danger border-opacity-25 rounded-4 p-4 bg-danger bg-opacity-10 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-danger">
                            <i class="bi bi-info-circle-fill"></i>
                            <div class="fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Alasan Pencabutan (Revoke)</div>
                        </div>
                        <div class="fw-semibold text-danger fs-5">{{ $revoke->reason }}</div>
                        <div class="small text-danger opacity-75 mt-2">Diajukan oleh: <span class="fw-bold">{{ $revoke->requester->nama }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-2">
                <i class="bi bi-shield-check fs-4 text-primary"></i>
                <h2 class="h5 fw-bold mb-0 text-dark">Verifikasi Integritas Data Lokal</h2>
            </div>
            
            <div class="text-secondary small fw-semibold mb-1">Cryptographic Hash</div>
            <div class="hash-box font-monospace bg-light border-0 p-3 rounded-3 text-break shadow-sm mb-4">{{ $revoke->ijazah->hash }}</div>
            
            @if($regeneratedHash === $revoke->ijazah->hash)
                <div class="alert alert-success border-success border-opacity-25 bg-success bg-opacity-10 d-flex gap-3 align-items-center rounded-3 mb-0">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <strong class="d-block text-success">Verifikasi Sukses: Hash Identik.</strong>
                        <span class="small text-success opacity-75">Hash valid terhadap data lokal. Proses revoke aman untuk dilanjutkan.</span>
                    </div>
                </div>
            @else
                <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 d-flex gap-3 align-items-center rounded-3 mb-0">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                    <div>
                        <strong class="d-block text-danger">Peringatan Kritis: Hash Berbeda!</strong>
                        <span class="small text-danger opacity-75">Hash tidak valid. Proses pencabutan dihentikan demi keamanan.</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-xl-4">
        <!-- Workflow Approval -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
            <!-- decorative background -->
            <div class="position-absolute" style="top: -20px; right: -20px; color: #f8fafc; transform: rotate(15deg); z-index: 0;">
                <i class="bi bi-shield-lock-fill" style="font-size: 8rem;"></i>
            </div>
            
            <h2 class="h5 fw-bold mb-4 position-relative z-1 text-dark border-bottom pb-2">Alur Persetujuan Pencabutan</h2>
            
            <div class="position-relative z-1 mt-3">
                @php($steps = $revoke->workflow ? $revoke->workflow->steps : [])
                @foreach($steps as $role)
                    @php($label = 'Persetujuan ' . ucfirst($role))
                    @if($role === 'admin')
                        @php($label = 'Eksekusi Administrator')
                    @endif
                    @php($sig = ($revoke->signatures ?: [])[$role] ?? null)
                    
                    <div class="d-flex gap-3 mb-4 position-relative">
                        <!-- timeline line -->
                        @if(!$loop->last)
                            <div class="position-absolute bg-secondary opacity-25" style="width: 2px; height: 100%; top: 30px; left: 19px; z-index: -1;"></div>
                        @endif
                        
                        <div class="flex-shrink-0 mt-1 bg-white">
                            @if($sig)
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-lg fs-5"></i>
                                </div>
                            @else
                                <div class="bg-light text-secondary border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-circle"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="pb-1 bg-white w-100 pe-2">
                            <div class="fw-bold text-dark">{{ $label }}</div>
                            @if($sig)
                                <div class="small text-secondary font-monospace mt-1 text-break" style="font-size: 0.75rem;">
                                    <i class="bi bi-wallet2 text-success me-1"></i> {{ substr($sig['wallet_address'], 0, 10) }}...{{ substr($sig['wallet_address'], -8) }}
                                </div>
                            @else
                                <div class="small text-secondary mt-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal border-0"><i class="bi bi-hourglass-split"></i> Menunggu persetujuan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @php($canSign = (auth()->user()->role === $revoke->current_approver_role))
            
            @if($canSign)
                <div class="mt-4 pt-3 border-top position-relative z-1">
                    <form id="revoke-signature-form" method="post" action="{{ route('revoke.approve', $revoke) }}">
                        @csrf
                        <input type="hidden" name="typed_data" value='@json($typedData)'>
                        <input type="hidden" name="wallet_address">
                        <input type="hidden" name="signature">
                    </form>
                    @include('partials.metamask-sign', ['typedData' => $typedData, 'button' => 'Tanda Tangan Pencabutan', 'formId' => 'revoke-signature-form'])
                </div>
            @endif
            
            @php($canReject = ($canSign && auth()->user()->role !== 'admin'))
            @if($canReject)
                <div class="mt-4 pt-3 border-top position-relative z-1">
                    <form method="post" action="{{ route('revoke.reject', $revoke) }}" class="mt-2">
                        @csrf
                        <button class="btn btn-outline-danger w-100 rounded-pill hover-lift shadow-sm">
                            <i class="bi bi-x-circle me-1"></i> Tolak Permintaan Revoke
                        </button>
                    </form>
                </div>
            @endif
        </div>

        @if(auth()->user()->role === 'admin' && $revoke->status === 'Approved' && isset(($revoke->signatures ?: [])['admin']))
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-danger text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #b91c1c, #450a0a);">
            <!-- decorative background -->
            <div class="position-absolute" style="top: 10px; right: 10px; color: #ffffff; opacity: 0.1; z-index: 0;">
                <i class="bi bi-exclamation-octagon-fill" style="font-size: 8rem;"></i>
            </div>
            
            <div class="position-relative z-1">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <i class="bi bi-cloud-upload fs-3 text-white"></i>
                    <h2 class="h5 fw-bold mb-0 text-white">Eksekusi Pencabutan</h2>
                </div>
                <p class="small opacity-75 mb-4">Panggil fungsi `revoke()` pada Smart Contract melalui MetaMask untuk membatalkan validitas ijazah ini secara permanen di blockchain.</p>
                
                <button class="btn btn-light text-danger w-100 mb-4 fw-bold rounded-pill hover-lift shadow-lg" id="call-revoke" data-number="{{ $revoke->ijazah->nomor_ijazah }}" data-hash="{{ $revoke->ijazah->hash }}">
                    <i class="bi bi-cpu me-1"></i> Eksekusi revoke()
                </button>
                
                <form method="post" action="{{ route('revoke.execute', $revoke) }}" class="vstack gap-3 border-top border-white border-opacity-25 pt-4">
                    @csrf
                    <div>
                        <label class="form-label small fw-semibold">Transaction Hash Bukti (0x...)</label>
                        <input name="tx_hash" class="form-control bg-white bg-opacity-10 border-0 text-white shadow-none placeholder-white" placeholder="0x..." required style="backdrop-filter: blur(5px);">
                    </div>
                    <button class="btn btn-outline-light rounded-pill hover-lift w-100 mt-2">
                        <i class="bi bi-save2 me-1"></i> Konfirmasi & Simpan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
document.getElementById('call-revoke')?.addEventListener('click', async (event) => {
    try {
        const contractAddress = @json(config('blockchain.contract_address'));
        if (!contractAddress) { alert('Konfigurasi contract address belum diisi di .env.'); return; }
        
        // Disable button and show loading state
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menunggu MetaMask...';

        const abi = [
            'function revoke(string nomorIjazah, string diplomaHash) external',
            'error Unauthorized()',
            'error NotFound()',
            'error AlreadyRevoked()',
            'error InvalidHash()'
        ];
        const provider = new ethers.BrowserProvider(window.ethereum);
        await provider.send('eth_requestAccounts', []);
        
        const network = await provider.getNetwork();
        if (Number(network.chainId) !== 11155111) {
            await window.ethereum.request({
                method: 'wallet_switchEthereumChain',
                params: [{ chainId: '0xaa36a7' }],
            });
        }
        
        const signer = await provider.getSigner();
        const contract = new ethers.Contract(contractAddress, abi, signer);
        
        // Simulasi staticCall untuk menangkap error persis
        try {
            await contract.revoke.staticCall(btn.dataset.number, btn.dataset.hash);
        } catch (simError) {
            console.error("Simulation Error:", simError);
            const reason = simError.reason || simError.shortMessage || simError.message;
            alert('Simulasi Transaksi Gagal: ' + reason);
            btn.disabled = false;
            btn.innerHTML = originalText;
            return;
        }

        const tx = await contract.revoke(btn.dataset.number, btn.dataset.hash);
        document.querySelector('input[name=tx_hash]').value = tx.hash;
        
        btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Transaksi Dikirim';
        btn.classList.replace('btn-light', 'btn-success');
        btn.classList.replace('text-danger', 'text-white');
    } catch (error) {
        console.error(error);
        alert(error.reason || error.message || 'Terjadi kesalahan saat memanggil smart contract.');
        const btn = event.currentTarget;
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cpu me-1"></i> Eksekusi revoke()';
    }
});
</script>
@endpush
@endsection
