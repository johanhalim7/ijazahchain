@extends('layouts.app')
@section('title','Wallet Saya - IjazahChain')

@section('content')
<div class="mb-4 pb-2 border-bottom">
    <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Pengaturan Keamanan</div>
    <h1 class="h3 fw-bold text-dark mb-1">Otoritas Wallet Digital</h1>
    <p class="text-secondary mb-0">Hubungkan dompet MetaMask Anda untuk menandatangani dokumen secara kriptografis.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white hover-lift" style="transition: transform 0.2s;">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <h2 class="h5 fw-bold mb-1 text-dark">Profil Wallet Anda</h2>
                    <p class="text-secondary small mb-0">Pastikan wallet yang terhubung adalah milik Anda pribadi dan bersifat rahasia.</p>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="border rounded-4 p-3 bg-light border-0 h-100">
                        <div class="text-secondary small fw-medium mb-1">Identitas Pengguna</div>
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random&color=fff&rounded=true" alt="Avatar" width="48" height="48" class="rounded-circle shadow-sm">
                            <div>
                                <div class="fw-bold text-dark fs-5 lh-1">{{ $user->nama }}</div>
                                <div class="badge bg-secondary bg-opacity-10 text-secondary mt-1 px-2 py-1 rounded-pill text-capitalize border-0">{{ $user->role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-4 p-3 bg-light border-0 h-100">
                        <div class="text-secondary small fw-medium mb-1">Jaringan Blockchain Target</div>
                        <div class="fw-bold text-dark text-break font-monospace small mb-2">{{ $contractAddress ?: 'Belum dikonfigurasi' }}</div>
                        <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill border-0"><i class="bi bi-diagram-3-fill me-1"></i> {{ $networkName }}</div>
                    </div>
                </div>
                
                <div class="col-12 mt-4">
                    <div class="border rounded-4 p-4 {{ $user->wallet_address ? 'bg-success bg-opacity-10 border-success border-opacity-25' : 'bg-warning bg-opacity-10 border-warning border-opacity-25' }} text-center position-relative overflow-hidden">
                        <div class="position-absolute opacity-25" style="top: -20px; right: -10px; color: currentColor; transform: rotate(-15deg); z-index: 0;">
                            <i class="bi bi-safe2" style="font-size: 8rem;"></i>
                        </div>
                        <div class="position-relative z-1">
                            <div class="small fw-semibold mb-2 {{ $user->wallet_address ? 'text-success' : 'text-warning' }}">
                                @if($user->wallet_address)
                                    <i class="bi bi-check-circle-fill me-1"></i> Wallet Aktif Terhubung
                                @else
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Wallet Belum Terhubung
                                @endif
                            </div>
                            
                            <div class="fw-bold text-dark text-break font-monospace fs-5 bg-white d-inline-block px-4 py-2 rounded-pill shadow-sm mb-3 border">
                                {{ $user->wallet_address ?: 'Belum Ada Wallet Address' }}
                            </div>

                            <form action="{{ route('wallet.link') }}" method="POST" id="form-link-wallet">
                                @csrf
                                <input type="hidden" name="wallet_address" id="input-wallet-address">
                                <div>
                                    <button type="button" class="btn {{ $user->wallet_address ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill px-4 shadow-sm hover-lift fw-medium" id="btn-connect-wallet">
                                        <i class="bi bi-usb-plug-fill me-1"></i> 
                                        {{ $user->wallet_address ? 'Perbarui Wallet MetaMask' : 'Sambungkan ke MetaMask' }}
                                    </button>
                                </div>
                                <div id="wallet-status" class="small text-secondary mt-3 fw-medium">
                                    Klik tombol di atas untuk mengambil alamat dompet dari ekstensi MetaMask Anda secara otomatis.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="background: linear-gradient(135deg, #021a17, #0f766e); color: white;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-shield-lock-fill fs-3 text-success"></i>
                <h2 class="h5 fw-bold mb-0 text-white">Panduan Keamanan</h2>
            </div>
            
            <div class="vstack gap-4 opacity-75">
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-white bg-opacity-10 rounded-circle p-2 flex-shrink-0 mt-1"><i class="bi bi-fingerprint"></i></div>
                    <div>
                        <div class="fw-semibold text-white mb-1">Tanda Tangan Kriptografis</div>
                        <div class="small">Sistem memerlukan otorisasi kriptografi langsung dari ekstensi MetaMask Anda untuk menyetujui setiap penerbitan Ijazah.</div>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-white bg-opacity-10 rounded-circle p-2 flex-shrink-0 mt-1"><i class="bi bi-person-bounding-box"></i></div>
                    <div>
                        <div class="fw-semibold text-white mb-1">Pengikatan Identitas</div>
                        <div class="small">Wallet Address yang didaftarkan di sini akan mengikat identitas Anda ({{ $user->nama }} - {{ $user->role }}).</div>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-danger bg-opacity-25 text-danger rounded-circle p-2 flex-shrink-0 mt-1 border border-danger"><i class="bi bi-exclamation-diamond-fill"></i></div>
                    <div class="text-white">
                        <div class="fw-semibold mb-1">Zero Recovery</div>
                        <div class="small">Jangan pernah membagikan Secret Recovery Phrase (Seed Phrase) MetaMask Anda kepada siapa pun, termasuk administrator kampus.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('btn-connect-wallet')?.addEventListener('click', async (event) => {
    const status = document.getElementById('wallet-status');
    const form = document.getElementById('form-link-wallet');
    const input = document.getElementById('input-wallet-address');

    const setStatus = (message, className = 'small mt-3 fw-bold') => {
        status.className = className;
        status.textContent = message;
    };

    if (!window.ethereum) {
        setStatus('Extensi MetaMask tidak ditemukan di browser Anda. Harap install terlebih dahulu.', 'small text-warning mt-3 fw-bold');
        return;
    }

    try {
        setStatus('Meminta akses MetaMask...', 'small text-primary mt-3 fw-bold');
        const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
        const activeWallet = accounts[0].toLowerCase();

        setStatus('MetaMask berhasil dibaca! Menyimpan ke profil...', 'small text-success mt-3 fw-bold bg-success bg-opacity-10 py-2 rounded');
        
        // Set nilai input hidden
        input.value = activeWallet;
        
        // Submit form otomatis ke backend
        form.submit();
        
    } catch (error) {
        setStatus(error.message || 'Otorisasi MetaMask dibatalkan pengguna.', 'small text-danger mt-3 fw-bold');
    }
});
</script>
@endpush
@endsection
