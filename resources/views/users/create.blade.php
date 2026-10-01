@extends('layouts.app')
@section('title', 'Tambah User Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary hover-lift d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Manajemen User
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="row g-0">
                <!-- Sisi Kiri: Dekorasi / Info -->
                <div class="col-md-5 d-none d-md-block position-relative" style="background: linear-gradient(135deg, var(--ic-primary), var(--ic-accent));">
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                    <div class="p-5 text-white h-100 d-flex flex-column justify-content-center position-relative z-1">
                        <div class="mb-4">
                            <i class="bi bi-person-plus text-white opacity-50" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Registrasi Akun Baru</h3>
                        <p class="text-white opacity-75 small lh-lg mb-0">
                            Tambahkan anggota baru ke dalam sistem IjazahChain. Pastikan peran (Role) yang diberikan sesuai dengan wewenangnya dalam struktur organisasi universitas.
                        </p>
                    </div>
                </div>

                <!-- Sisi Kanan: Form -->
                <div class="col-md-7">
                    <div class="p-4 p-md-5">
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-3">Data Pengguna</h4>
                        
                        <form action="{{ route('users.store') }}" method="POST" class="vstack gap-4">
                            @csrf
                            
                            <div>
                                <label class="form-label fw-semibold text-secondary small mb-1">Nama Lengkap</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" class="form-control bg-light border-0 shadow-none @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Johan Halim" required>
                                </div>
                                @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="form-label fw-semibold text-secondary small mb-1">Email Login</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@ijazahchain.test" required>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-secondary small mb-1">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control bg-light border-0 shadow-none @error('password') is-invalid @enderror" placeholder="Min. 8 Karakter" required minlength="8">
                                    </div>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-secondary small mb-1">Jabatan / Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-diagram-3"></i></span>
                                        <input type="text" name="role" class="form-control bg-light border-0 shadow-none text-lowercase @error('role') is-invalid @enderror" value="{{ old('role') }}" placeholder="dekan, kaprodi..." list="role-list" required autocomplete="off">
                                        <datalist id="role-list">
                                            @foreach($roles as $r)
                                                <option value="{{ $r }}">
                                            @endforeach
                                        </datalist>
                                    </div>
                                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label fw-semibold text-secondary small mb-1">Wallet Address MetaMask <span class="text-muted fw-normal">(Opsional)</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-wallet2"></i></span>
                                    <input type="text" name="wallet_address" class="form-control bg-light border-0 shadow-none font-monospace fs-6 @error('wallet_address') is-invalid @enderror" value="{{ old('wallet_address') }}" placeholder="0x...">
                                </div>
                                <div class="form-text small mt-1">Bisa dikosongkan terlebih dahulu, biarkan pengguna menyambungkannya sendiri saat login.</div>
                                @error('wallet_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mt-2 pt-3 border-top text-end">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 fw-bold">
                                    Buat Akun Pengguna
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
