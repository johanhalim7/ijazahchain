@extends('layouts.app')
@section('title','Revoke Ijazah - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-danger small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;"><i class="bi bi-shield-slash-fill me-1"></i> Keamanan Ekstrem</div>
        <h1 class="h3 fw-bold text-dark mb-0">Workflow Revoke Ijazah</h1>
        <p class="text-secondary mb-0 mt-1">Pengajuan pencabutan, multi-approval Rektor & Akademik, hingga eksekusi blockchain.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4 shadow-sm hover-lift fw-medium text-primary">
        <i class="bi bi-arrow-left me-2"></i> Dashboard
    </a>
</div>

@if(auth()->user()->role === 'admin')
<div class="card border-danger border-opacity-50 shadow-sm rounded-4 p-4 mb-4 bg-danger bg-opacity-10 position-relative overflow-hidden">
    <div class="position-absolute" style="top: -20px; right: 0; color: #dc3545; opacity: 0.1; transform: rotate(-15deg); z-index: 0;">
        <i class="bi bi-slash-circle-fill" style="font-size: 10rem;"></i>
    </div>
    <div class="position-relative z-1">
        <div class="d-flex align-items-center gap-3 mb-4 border-bottom border-danger border-opacity-25 pb-2">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <h2 class="h5 fw-bold mb-0 text-danger">Ajukan Pencabutan (Revoke) Ijazah</h2>
        </div>
        <form method="post" action="{{ route('revoke.store') }}" class="row g-3">
            @csrf
            <div class="col-md-5">
                <label class="form-label fw-semibold text-danger small">Pilih Ijazah Aktif</label>
                <select name="ijazah_id" class="form-select form-select-lg border-danger border-opacity-50 shadow-none bg-white" required>
                    <option value="">-- Pilih dokumen yang akan dicabut --</option>
                    @foreach($activeIjazahs as $ijazah)
                        <option value="{{ $ijazah->id }}">{{ $ijazah->nomor_ijazah }} - {{ $ijazah->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold text-danger small">Alasan Pencabutan (Wajib)</label>
                <input name="reason" class="form-control form-control-lg border-danger border-opacity-50 shadow-none bg-white" placeholder="Masukkan Alasan Spesifik Pencabutan Dokumen" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-danger btn-lg w-100 rounded-pill shadow-sm hover-lift fw-medium">
                    <i class="bi bi-slash-circle me-1"></i> Ajukan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <form action="{{ route('revoke.index') }}" method="get" class="mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group input-group-lg shadow-sm rounded-pill bg-light border-0 overflow-hidden">
                    <span class="input-group-text bg-transparent border-0 text-secondary ps-4"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none px-2 fs-6" value="{{ request('search') }}" placeholder="Cari Berdasarkan No. Ijazah...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-lg shadow-sm rounded-pill bg-light border-0 px-4 text-secondary fs-6">
                    <option value="">Semua Status Revoke</option>
                    <option value="Pending Rektor" {{ request('status') === 'Pending Rektor' ? 'selected' : '' }}>Pending Rektor</option>
                    <option value="Pending Akademik" {{ request('status') === 'Pending Akademik' ? 'selected' : '' }}>Pending Akademik</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved (Siap Eksekusi)</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed (Sukses)</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm hover-lift fw-medium fs-6 h-100">
                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                </button>
            </div>
            @if(request('search') || request('status'))
                <div class="col-12 mt-2 px-3">
                    <a href="{{ route('revoke.index') }}" class="small text-danger text-decoration-none fw-semibold hover-lift d-inline-block">
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
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-4">Dokumen Ijazah</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Alasan Revoke</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Status Proses</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Diajukan Oleh</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Transaction Hash</th>
                    <th class="border-0 px-4 text-end"></th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($requests as $request)
                <tr class="bg-white shadow-sm hover-lift" style="transition: all 0.2s;">
                    <td class="border-0 rounded-start-4 px-4 py-2">
                        <div class="fw-bold text-dark">{{ $request->ijazah->nomor_ijazah }}</div>
                        <div class="small text-secondary fw-medium">{{ $request->ijazah->nama }}</div>
                    </td>
                    <td class="border-0 px-3 py-2 text-secondary" style="max-width: 200px;">
                        {{ $request->reason }}
                    </td>
                    <td class="border-0 px-3 py-2 text-center">
                        @php
                            $statusClass = 'bg-secondary bg-opacity-10 text-secondary border-secondary';
                            $icon = 'bi-hourglass-split';
                            if ($request->status === 'Pending Rektor' || $request->status === 'Pending Akademik' || $request->status === 'Pending Admin') {
                                $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning';
                            } elseif ($request->status === 'Revoked') {
                                $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger';
                                $icon = 'bi-slash-circle-fill';
                            } elseif ($request->status === 'Ditolak') {
                                $statusClass = 'bg-dark bg-opacity-10 text-dark border-dark';
                                $icon = 'bi-x-circle-fill';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }} border border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                            <i class="bi {{ $icon }} me-1"></i> {{ $request->status }}
                        </span>
                    </td>
                    <td class="border-0 px-3 py-2">
                        <div class="fw-semibold text-dark">{{ $request->requester->nama }}</div>
                    </td>
                    <td class="border-0 px-3 py-2">
                        <code class="small text-break bg-light px-2 py-1 rounded text-dark" style="max-width:140px;display:inline-block;vertical-align:middle">{{ $request->tx_hash ? Str::limit($request->tx_hash, 16) : '-' }}</code>
                    </td>
                    <td class="border-0 rounded-end-4 px-4 py-2 text-end">
                        <a class="btn btn-sm btn-light text-primary fw-semibold px-3 rounded-pill" href="{{ route('revoke.show', $request) }}">Proses Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-5 border-0 bg-transparent shadow-none">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                    Belum ada permintaan revoke dokumen.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection
