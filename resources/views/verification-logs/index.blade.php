@extends('layouts.app')
@section('title','Verification Log - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Audit & Keamanan</div>
        <h1 class="h3 fw-bold text-dark mb-0">Verification Log</h1>
        <p class="text-secondary mb-0 mt-1">Riwayat seluruh percobaan verifikasi ijazah oleh publik.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4 shadow-sm hover-lift fw-medium text-primary">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <form method="get" action="{{ route('verification-logs.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group input-group-lg shadow-sm rounded-pill bg-light border-0 overflow-hidden">
                    <span class="input-group-text bg-transparent border-0 text-secondary ps-4"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none px-2 fs-6" value="{{ request('q') }}" placeholder="Cari Berdasarkan Hash/Tx Hash/IP Address...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="result" class="form-select form-select-lg shadow-sm rounded-pill bg-light border-0 px-4 text-secondary fs-6">
                    <option value="">Semua Hasil</option>
                    <option value="VALID" {{ request('result') === 'VALID' ? 'selected' : '' }}>VALID</option>
                    <option value="INVALID" {{ request('result') === 'INVALID' ? 'selected' : '' }}>INVALID</option>
                    <option value="REVOKED" {{ request('result') === 'REVOKED' ? 'selected' : '' }}>REVOKED</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm hover-lift fw-medium fs-6 h-100">
                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                </button>
            </div>
            @if(request('q') || request('result'))
                <div class="col-12 mt-2 px-3">
                    <a href="{{ route('verification-logs.index') }}" class="small text-danger text-decoration-none fw-semibold hover-lift d-inline-block">
                        <i class="bi bi-x-circle-fill me-1"></i> Reset filter
                    </a>
                </div>
            @endif
        </div>
    </form>

    <div class="table-responsive" style="margin: 0 -0.5rem;">
        <table class="table table-hover align-middle border-0" style="border-collapse: separate; border-spacing: 0 0.35rem;">
            <thead>
                <tr>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-4">Waktu Verifikasi</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Metode</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Hash Dokumen</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Status Hasil</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Transaction Hash</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-4 text-end">IP Address</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($logs as $log)
                <tr class="bg-white shadow-sm hover-lift" style="transition: all 0.2s;">
                    <td class="border-0 rounded-start-4 px-4 py-2">
                        <div class="fw-bold text-dark">{{ ($log->verified_at ?? $log->created_at)->format('d/m/Y H:i') }}</div>
                        <div class="small text-secondary fw-medium"><i class="bi bi-clock me-1"></i>{{ ($log->verified_at ?? $log->created_at)->diffForHumans() }}</div>
                    </td>
                    <td class="border-0 px-3 py-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill fw-medium text-uppercase">
                            {{ $log->verification_type }}
                        </span>
                    </td>
                    <td class="border-0 px-3 py-2">
                        <code class="small text-break bg-light px-2 py-1 rounded text-dark" style="max-width:180px;display:inline-block;vertical-align:middle">{{ Str::limit($log->hash, 20) ?? '-' }}</code>
                    </td>
                    <td class="border-0 px-3 py-2 text-center">
                        @if($log->result === 'VALID')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-check-circle-fill me-1"></i> VALID</span>
                        @elseif($log->result === 'REVOKED')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-slash-circle-fill me-1"></i> REVOKED</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-x-circle-fill me-1"></i> INVALID</span>
                        @endif
                    </td>
                    <td class="border-0 px-3 py-2">
                        <code class="small text-break bg-light px-2 py-1 rounded text-dark" style="max-width:140px;display:inline-block;vertical-align:middle">{{ $log->tx_hash ? Str::limit($log->tx_hash, 16) : '-' }}</code>
                    </td>
                    <td class="border-0 rounded-end-4 px-4 py-2 text-end font-monospace small text-secondary">
                        {{ $log->ip_address ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-5 border-0 bg-transparent shadow-none">
                    <i class="bi bi-shield-slash fs-1 d-block mb-3 opacity-25"></i>
                    Belum ada verification log tersimpan.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
