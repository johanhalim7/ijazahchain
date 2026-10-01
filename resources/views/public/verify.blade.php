@extends('layouts.app')
@section('title','Verifikasi Ijazah - IjazahChain')

@push('head')
<style>
    .verify-bg {
        background: #f6f8fb;
        position: relative;
    }
    .verify-bg::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 350px;
        background: linear-gradient(135deg, #021a17, #0f766e);
        z-index: 0;
    }
    .modern-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border-radius: 8px;
        padding: 12px 24px;
        transition: all 0.2s;
    }
    .modern-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #0f766e;
    }
    .modern-tabs .nav-link.active {
        background: #eaf7f5;
        color: #0f766e;
    }
    .card-verify {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        background: #fff;
    }
</style>
@endpush

@section('content')
<section class="py-5 verify-bg flex-grow-1 d-flex flex-column">
    <div class="container position-relative z-1" style="max-width: 1200px; flex-grow: 1;">
        
        <div class="text-center mb-5 text-white mt-3">
            <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded-circle mb-3" style="width: 70px; height: 70px; border: 1px solid rgba(255,255,255,0.2);">
                <i class="bi bi-shield-check fs-1"></i>
            </div>
            <h1 class="display-6 fw-bold mb-3">Portal Verifikasi Publik</h1>
            <p class="lead opacity-75 mx-auto" style="max-width: 600px; font-size: 1.1rem;">Verifikasi keabsahan dokumen ijazah secara langsung dan transparan melalui jaringan Blockchain.</p>
        </div>

        <div class="card card-verify p-4 p-md-5 mb-5">
            <ul class="nav nav-pills modern-tabs mb-4 justify-content-center gap-2" role="tablist">

                <li class="nav-item">
                    <button class="nav-link active d-flex align-items-center gap-2" data-bs-toggle="pill" data-bs-target="#via-file" type="button">
                        <i class="bi bi-file-earmark-pdf"></i> Verifikasi via Dokumen
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link d-flex align-items-center gap-2" data-bs-toggle="pill" data-bs-target="#via-hash" type="button">
                        <i class="bi bi-hash"></i> Verifikasi via Hash
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link d-flex align-items-center gap-2" data-bs-toggle="pill" data-bs-target="#via-nomor" type="button">
                        <i class="bi bi-123"></i> Verifikasi via Nomor
                    </button>
                </li>
            </ul>
            
            <hr class="mb-4 opacity-10">

            <div class="tab-content">
                <div class="tab-pane fade show active" id="via-file">
                    <div class="alert border shadow-sm mb-4 d-flex gap-3 align-items-center rounded-3" style="background-color: #f0f9ff; border-color: #bae6fd;">
                        <i class="bi bi-cloud-upload-fill text-info fs-3"></i>
                        <div class="small text-secondary" style="line-height: 1.6;">
                            Unggah file digital asli ijazah (PDF) untuk memverifikasi keasliannya melalui <i>Cryptographic Hashing</i>.
                        </div>
                    </div>
                    <form method="post" action="{{ route('verify.submit') }}" class="vstack gap-4" enctype="multipart/form-data">
                        @csrf <input type="hidden" name="method" value="file">
                        <div class="border rounded-4 p-5 text-center bg-light" style="border: 2px dashed #cbd5e1 !important;">
                            <i class="bi bi-cloud-arrow-up text-secondary opacity-50 mb-3 d-block" style="font-size: 3rem;"></i>
                            <h3 class="h6 fw-bold text-dark mb-2">Pilih File Ijazah</h3>
                            <p class="small text-secondary mb-4">Mendukung format PDF (Maksimal 5MB)</p>
                            <input type="file" name="document" accept=".pdf" class="form-control form-control-lg shadow-sm mx-auto" style="max-width: 400px;" required>
                        </div>
                        <div class="text-center pt-2">
                            <button class="btn btn-info text-white btn-lg px-5 hover-lift rounded-pill shadow-sm" style="min-width: 250px;">
                                <i class="bi bi-search me-2"></i> Verifikasi File Dokumen
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="via-hash">
                    <div class="alert border shadow-sm mb-4 d-flex gap-3 align-items-center rounded-3" style="background-color: #f0fdf4; border-color: #bbf7d0;">
                        <i class="bi bi-shield-lock-fill text-success fs-3"></i>
                        <div class="small text-secondary" style="line-height: 1.6;">
                            Verifikasi instan jika Anda telah memiliki 64 karakter <i>Cryptographic Hash</i> dari dokumen yang bersangkutan.
                        </div>
                    </div>
                    <form method="post" action="{{ route('verify.submit') }}" class="vstack gap-4">
                        @csrf <input type="hidden" name="method" value="hash">
                        <div>
                            <label class="form-label fw-semibold text-dark">Hash Blockchain (SHA-256)</label>
                            <input name="hash" class="form-control form-control-lg bg-light border-0 shadow-sm" value="{{ $hash }}" placeholder="Masukkan Hash Dokumen Ijazah Anda..." required style="font-family: monospace; font-size: 1rem; padding: 1rem;">
                        </div>
                        <div class="text-center pt-2">
                            <button class="btn btn-success btn-lg px-5 hover-lift rounded-pill shadow-sm" style="min-width: 250px;">
                                <i class="bi bi-cpu me-2"></i> Eksekusi di Blockchain
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="via-nomor">
                    <div class="alert border shadow-sm mb-4 d-flex gap-3 align-items-center rounded-3" style="background-color: #fff7ed; border-color: #fed7aa;">
                        <i class="bi bi-search text-warning fs-3"></i>
                        <div class="small text-secondary" style="line-height: 1.6;">
                            Verifikasi ijazah dengan menggunakan Nomor Registrasi Ijazah.
                        </div>
                    </div>
                    <form method="post" action="{{ route('verify.submit') }}" class="vstack gap-4">
                        @csrf <input type="hidden" name="method" value="nomor">
                        <div>
                            <label class="form-label fw-semibold text-dark">Nomor Registrasi Ijazah</label>
                            <input name="nomor_ijazah" class="form-control form-control-lg bg-light border-0 shadow-sm" placeholder="Masukkan Nomor Ijazah (contoh: 1234/UNIV/2026)..." required style="padding: 1rem;">
                        </div>
                        <div class="text-center pt-2">
                            <button class="btn btn-warning text-dark btn-lg px-5 hover-lift rounded-pill shadow-sm" style="min-width: 250px;">
                                <i class="bi bi-search me-2"></i> Cari Berdasarkan Nomor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="text-center text-secondary small fw-medium mb-4">
            <i class="bi bi-lock-fill text-success"></i> Secured by Ethereum Cryptography (ECDSA)
        </div>
    </div>
</section>
@endsection
