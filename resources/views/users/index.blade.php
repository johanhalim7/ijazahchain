@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-secondary small mb-0 mt-1">Kelola data login, jabatan (role), dan wallet address.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm hover-lift rounded-pill px-4">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah User Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-bottom p-3">
        <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search text-secondary"></i></span>
                    <input type="text" name="q" class="form-control bg-light border-0 shadow-none" placeholder="Cari nama, email, wallet..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select bg-light border-0 shadow-none text-capitalize">
                    <option value="">-- Semua Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100 rounded-pill hover-lift">Filter Data</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="px-4 py-3 font-weight-medium border-0">Pengguna</th>
                    <th class="py-3 font-weight-medium border-0 text-center">Jabatan / Role</th>
                    <th class="py-3 font-weight-medium border-0">Wallet Address</th>
                    <th class="px-4 py-3 font-weight-medium border-0 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random&color=fff&rounded=true" alt="Avatar" width="40" height="40" class="rounded-circle shadow-sm">
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                    <div class="small text-secondary">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-center">
                            @php
                                $badgeColor = match(strtolower($user->role)) {
                                    'admin' => 'danger',
                                    'rektor' => 'primary',
                                    'akademik' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-3 py-2 rounded-pill text-uppercase fw-semibold" style="letter-spacing: 0.5px;">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($user->wallet_address)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-wallet2 text-success"></i>
                                    <code class="text-secondary bg-light px-2 py-1 rounded border font-monospace shadow-sm">
                                        {{ substr($user->wallet_address, 0, 8) }}...{{ substr($user->wallet_address, -6) }}
                                    </code>
                                </div>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill border border-warning border-opacity-25 fw-normal">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Belum diatur
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light text-primary hover-lift rounded-circle" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger hover-lift rounded-circle" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;" title="Hapus">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-secondary mb-3"><i class="bi bi-people" style="font-size: 3rem;"></i></div>
                            <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
                            <p class="text-secondary">Tidak ada data pengguna yang cocok dengan filter pencarian.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="card-footer bg-white border-top p-3 pb-0">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
