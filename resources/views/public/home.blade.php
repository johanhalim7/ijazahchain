@extends('layouts.app')
@section('title','IjazahChain - Verifikasi Ijazah Blockchain')
@section('content')
<section class="hero position-relative overflow-hidden" style="background: linear-gradient(135deg, #021a17, #063f3a, #0b2265);">
    <div class="bg-glow glow-primary"></div>
    <div class="bg-glow glow-accent"></div>
    
    <div class="container position-relative z-1" style="padding: 80px 0;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge text-bg-light mb-3 px-3 py-2 rounded-pill shadow-sm" style="letter-spacing: 1px;"><i class="bi bi-circle-fill text-success small me-1"></i> ETHEREUM SEPOLIA TESTNET</span>
                <h1 class="display-4 fw-bolder mb-4" style="line-height: 1.2;">Masa Depan Verifikasi Ijazah Digital yang <span style="color: #6ee7b7;">Tak Terbantahkan</span></h1>
                <p class="lead mb-5" style="color: #cbd5e1; font-weight: 300;">Amankan reputasi institusi Anda. IjazahChain menggunakan kombinasi canggih <i>Multi Digital Signature ECDSA</i> dan teknologi Blockchain untuk memastikan setiap lembar ijazah valid, transparan, dan 100% anti-manipulasi.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a class="btn btn-primary btn-lg px-4 hover-lift shadow-lg" href="{{ route('verify.form') }}" style="border-radius: 12px; background: #0f766e; border-color: #0f766e;">
                        <i class="bi bi-patch-check me-2"></i> Verifikasi Sekarang
                    </a>
                    <a class="btn btn-outline-light btn-lg px-4 hover-lift" href="#cara-kerja" style="border-radius: 12px;">
                        <i class="bi bi-play-circle me-2"></i> Lihat Cara Kerja
                    </a>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="verify-card glass-panel rounded-4 p-4 animate-float mx-auto mx-lg-0" style="max-width: 450px;">
                    <div class="position-absolute text-success" style="top: -20px; right: -20px; opacity: 0.1; transform: rotate(15deg); pointer-events: none;">
                        <i class="bi bi-shield-check" style="font-size: 14rem;"></i>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3 mb-4 position-relative z-1">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-patch-check-fill text-success" style="font-size: 2.5rem; line-height: 1;"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold mb-1 text-dark">Status Validasi</h2>
                            <div class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25"><i class="bi bi-check-circle-fill me-1"></i> Terverifikasi On-Chain</div>
                        </div>
                    </div>
                    
                    <div class="border rounded-3 p-3 bg-white bg-opacity-75 mb-3 position-relative z-1 shadow-sm">
                        <div class="row g-3 small">
                            <div class="col-5 text-secondary fw-medium">Pemilik Ijazah</div>
                            <div class="col-7 fw-bold text-dark">Johan Halim, S.T.</div>
                            <div class="col-12"><hr class="my-0 opacity-25"></div>
                            <div class="col-5 text-secondary fw-medium">Nomor Ijazah</div>
                            <div class="col-7 fw-bold text-dark">IJZ/2026/08/001</div>
                            <div class="col-12"><hr class="my-0 opacity-25"></div>
                            <div class="col-5 text-secondary fw-medium">Program Studi</div>
                            <div class="col-7 fw-bold text-dark">Teknik Informatika</div>
                        </div>
                    </div>
                    
                    <div class="position-relative z-1">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <span class="small text-secondary fw-medium">Smart Contract Hash</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">EIP-712</span>
                        </div>
                        <div class="hash-box small text-primary mb-0 d-flex justify-content-between align-items-center bg-white shadow-sm border-0" style="padding: 10px 14px; cursor: pointer; border-radius: 8px;" title="Contoh Transaction Hash">
                            <span class="font-monospace fw-semibold">0x8f2a...3c91e4</span>
                            <i class="bi bi-box-arrow-up-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Banner -->
<section class="py-4 border-bottom bg-white">
    <div class="container text-center">
        <p class="text-secondary small fw-bold text-uppercase tracking-wider mb-3">DIDUKUNG OLEH TEKNOLOGI STANDAR INDUSTRI</p>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5 opacity-75">
            <div class="d-flex align-items-center gap-2 fw-bold fs-5 text-dark"><i class="bi bi-diamond-fill text-primary"></i> Ethereum</div>
            <div class="d-flex align-items-center gap-2 fw-bold fs-5 text-dark"><i class="bi bi-shield-lock-fill text-success"></i> ECDSA Crypto</div>
            <div class="d-flex align-items-center gap-2 fw-bold fs-5 text-dark"><i class="bi bi-file-earmark-code-fill text-warning"></i> EIP-712 Typed Data</div>
            <div class="d-flex align-items-center gap-2 fw-bold fs-5 text-dark"><i class="bi bi-braces text-danger"></i> Laravel</div>
        </div>
    </div>
</section>

<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase small">Mengapa IjazahChain?</span>
            <h2 class="display-6 fw-bold mt-2 text-dark">Keunggulan Sistem Verifikasi Kami</h2>
            <p class="text-secondary mx-auto mt-3" style="max-width: 600px;">Sistem kami dirancang untuk mengatasi masalah pemalsuan dokumen akademik dengan keamanan kriptografi tingkat tinggi.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift" style="border-radius: 16px;">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3 mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Aman & Terenkripsi</h3>
                    <p class="text-secondary mb-0">Hash SHA-256 dan *Digital Signature* ECDSA memastikan integritas data tidak bisa diubah walau 1 byte sekalipun.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift" style="border-radius: 16px;">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-globe fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Transparan & Global</h3>
                    <p class="text-secondary mb-0">Status validitas ijazah tertanam abadi di *Blockchain* dan dapat diverifikasi secara publik dari manapun di seluruh dunia.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift" style="border-radius: 16px;">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-3 mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-fingerprint fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Anti Manipulasi</h3>
                    <p class="text-secondary mb-0">Menggunakan *Smart Contract*, segala bentuk perubahan data lokal akan otomatis terdeteksi karena hash tidak lagi identik.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white" id="cara-kerja">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase small">Alur Verifikasi</span>
            <h2 class="display-6 fw-bold mt-2 text-dark">Bagaimana Cara Kerjanya?</h2>
        </div>
        
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex gap-4 align-items-start hover-lift p-3 rounded-4 bg-light border border-white">
                        <div class="step-dot fs-5 shadow-sm bg-white text-primary flex-shrink-0" style="width: 48px; height: 48px;">1</div>
                        <div>
                            <h4 class="h5 fw-bold text-dark">Input Data Ijazah</h4>
                            <p class="text-secondary mb-0">Pihak akademik memasukkan data ijazah mahasiswa ke dalam sistem secara lengkap dan akurat.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 align-items-start hover-lift p-3 rounded-4 bg-light border border-white">
                        <div class="step-dot fs-5 shadow-sm bg-white text-primary flex-shrink-0" style="width: 48px; height: 48px;">2</div>
                        <div>
                            <h4 class="h5 fw-bold text-dark">Generate Cryptographic Hash</h4>
                            <p class="text-secondary mb-0">Sistem memadatkan seluruh data menjadi *hash string* unik menggunakan standar keamanan EIP-712.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 align-items-start hover-lift p-3 rounded-4 bg-light border border-white">
                        <div class="step-dot fs-5 shadow-sm bg-white text-primary flex-shrink-0" style="width: 48px; height: 48px;">3</div>
                        <div>
                            <h4 class="h5 fw-bold text-dark">Multi-Role Digital Signature</h4>
                            <p class="text-secondary mb-0">Admin dan Rektor memberikan tanda tangan digital (ECDSA) mereka untuk menyetujui keabsahan dokumen.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 align-items-start hover-lift p-3 rounded-4 bg-light border border-white">
                        <div class="step-dot fs-5 shadow-sm bg-white text-primary flex-shrink-0" style="width: 48px; height: 48px;">4</div>
                        <div>
                            <h4 class="h5 fw-bold text-dark">Eksekusi Smart Contract</h4>
                            <p class="text-secondary mb-0">Data hash dan *signatures* dikirim dan dikunci secara permanen ke dalam jaringan *Blockchain Ethereum*.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <div class="position-relative d-inline-block">
                    <div class="bg-glow glow-primary" style="opacity: 0.3;"></div>
                    <img src="{{ asset('images/blockchain_concept.jpg') }}" alt="Blockchain Concept" class="img-fluid rounded-4 shadow-lg position-relative z-1 hover-lift animate-float" style="max-width: 80%; border: 8px solid white;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 text-center text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f766e, #2563eb);">
    <div class="container position-relative z-1 py-4">
        <h2 class="display-6 fw-bold mb-4">Siap Memverifikasi Dokumen?</h2>
        <p class="lead mb-4 opacity-75" style="max-width: 600px; margin: 0 auto;">Pastikan keaslian ijazah dengan sistem verifikasi publik IjazahChain. Cepat, aman, dan 100% transparan.</p>
        <a class="btn btn-light btn-lg px-5 hover-lift shadow-lg fw-bold text-primary rounded-pill mt-2" href="{{ route('verify.form') }}">
            Mulai Verifikasi <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>
@endsection
