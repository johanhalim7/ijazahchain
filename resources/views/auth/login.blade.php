@extends('layouts.app')
@section('title','Login - IjazahChain')

@push('head')
<style>
    .login-bg {
        background: linear-gradient(135deg, #021a17, #0f766e);
        position: relative;
    }
    .login-bg::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('{{ asset('images/blockchain_concept.jpg') }}') center/cover;
        opacity: 0.1;
        mix-blend-mode: lighten;
        z-index: 0;
    }
    .card-login {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    /* Highlight bar at top of card */
    .card-login::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, #0f766e, #2563eb);
    }
    .input-group-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background-color: #f8fafc;
        transition: all 0.2s ease-in-out;
    }
    .input-group-wrapper:focus-within {
        background-color: #fff;
        border-color: #0f766e;
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.15);
    }
    .input-group-wrapper .input-group-text {
        border: none;
        background: transparent;
        color: #64748b;
        padding-left: 1.25rem;
    }
    .input-group-wrapper .form-control {
        border: none;
        background: transparent;
        padding-left: 0.5rem;
        padding-top: 0.8rem;
        padding-bottom: 0.8rem;
    }
    .input-group-wrapper .form-control:focus {
        box-shadow: none;
    }
    .input-group-wrapper .btn {
        border: none;
        color: #64748b;
        background: transparent;
    }
    .input-group-wrapper .btn:hover {
        color: #0f766e;
    }
    .form-check-input:checked {
        background-color: #0f766e;
        border-color: #0f766e;
    }
</style>
@endpush

@section('content')
<section class="flex-grow-1 d-flex align-items-center justify-content-center px-3 py-5 login-bg">
    <div class="w-100" style="max-width:480px; position: relative; z-index: 2;">
        
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none d-inline-block hover-lift">
                <div class="brand-mark mx-auto mb-3 shadow" style="width: 56px; height: 56px; font-size: 1.5rem;"><i class="bi bi-shield-lock"></i></div>
            </a>
            <h1 class="h3 fw-bold text-white mb-2" style="letter-spacing: -0.5px;">Selamat Datang Kembali</h1>
            <p class="text-white opacity-75">Masuk ke portal manajemen IjazahChain</p>
        </div>

        <div class="card card-login p-4 p-md-5">
            <form method="post" action="{{ route('login.submit') }}" class="vstack gap-4">
                @csrf
                
                <div>
                    <label class="form-label fw-semibold text-dark small mb-2">Email Institusi</label>
                    <div class="input-group input-group-wrapper">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" autocomplete="email" placeholder="Masukkan Alamat Email Institusi" required autofocus>
                    </div>
                </div>
                
                <div>
                    <label class="form-label fw-semibold text-dark small mb-2">Kata Sandi</label>
                    <div class="input-group input-group-wrapper">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input id="password" name="password" type="password" class="form-control" autocomplete="current-password" placeholder="Masukkan Kata Sandi" required>
                        <button class="btn" type="button" id="toggle-password" aria-label="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <div class="form-check">
                        <input class="form-check-input shadow-sm" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small fw-medium text-secondary" for="remember">Ingat saya</label>
                    </div>
                    <a href="{{ route('verify.form') }}" class="small fw-semibold text-primary text-decoration-none hover-lift d-inline-block">
                        Verifikasi Publik <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                
                <button class="btn btn-primary btn-lg w-100 py-3 mt-2 rounded-pill fw-bold shadow-sm hover-lift" style="font-size: 1rem;">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sistem
                </button>
            </form>
            
            <div class="mt-4 pt-4 border-top text-center">
                <p class="small text-secondary mb-0"><i class="bi bi-lock-fill text-success me-1"></i> Sesi Anda dienkripsi secara end-to-end.</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('toggle-password')?.addEventListener('click', function () {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    const showPassword = password.type === 'password';

    password.type = showPassword ? 'text' : 'password';
    icon.classList.toggle('bi-eye', !showPassword);
    icon.classList.toggle('bi-eye-slash', showPassword);
    this.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
});
</script>
@endpush
@endsection
