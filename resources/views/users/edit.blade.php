@extends('layouts.app')
@section('title', 'Edit Data Pengguna')

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
                <!-- Sisi Kiri: Dekorasi / Profil Singkat -->
                <div class="col-md-5 d-none d-md-block position-relative bg-light border-end">
                    <div class="p-5 h-100 d-flex flex-column align-items-center justify-content-center text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random&color=fff&rounded=true&size=128" alt="Avatar" class="rounded-circle shadow-sm mb-4 border border-4 border-white">
                        <h4 class="fw-bold text-dark mb-1">{{ $user->nama }}</h4>
                        <div class="text-secondary small mb-3">{{ $user->email }}</div>
                        
                        @php
                            $badgeColor = match(strtolower($user->role)) {
                                'admin' => 'danger',
                                'rektor' => 'primary',
                                'akademik' => 'info',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-4 py-2 rounded-pill text-uppercase fw-semibold" style="letter-spacing: 1px;">
                            {{ $user->role }}
                        </span>
                        
                        <div class="mt-4 pt-4 border-top w-100 text-start">
                            <div class="small text-secondary fw-semibold mb-1">Status Wallet:</div>
                            @if($user->wallet_address)
                                <div class="d-flex align-items-center gap-2 text-success small">
                                    <i class="bi bi-check-circle-fill"></i> Terhubung
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2 text-warning small">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Diatur
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Edit -->
                <div class="col-md-7">
                    <div class="p-4 p-md-5">
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-3">Edit Data Pengguna</h4>
                        
                        <form action="{{ route('users.update', $user) }}" method="POST" class="vstack gap-4">
                            @csrf @method('PUT')
                            
                            <div>
                                <label class="form-label fw-semibold text-secondary small mb-1">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" class="form-control bg-light border-0 shadow-none @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
                                </div>
                                @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="form-label fw-semibold text-secondary small mb-1">Email Login</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-secondary small mb-1">Ganti Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control bg-light border-0 shadow-none @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah" minlength="8">
                                    </div>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-secondary small mb-1">Jabatan / Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-diagram-3"></i></span>
                                        <input type="text" name="role" class="form-control bg-light border-0 shadow-none text-lowercase @error('role') is-invalid @enderror" value="{{ old('role', $user->role) }}" list="role-list" required autocomplete="off">
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
                                <label class="form-label fw-semibold text-secondary small mb-1">Wallet Address MetaMask</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-secondary"><i class="bi bi-wallet2"></i></span>
                                    <input type="text" name="wallet_address" class="form-control bg-light border-0 shadow-none font-monospace fs-6 text-muted @error('wallet_address') is-invalid @enderror" value="{{ old('wallet_address', $user->wallet_address) }}" placeholder="0x...">
                                </div>
                                @error('wallet_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mt-2 pt-3 border-top text-end d-flex gap-2 justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4 fw-medium">Batal</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 hover-lift shadow-sm fw-bold">
                                    Simpan Perubahan
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
