@extends('layouts.app')
@section('title','Data Ijazah - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Manajemen Dokumen</div>
            <h1 class="h3 fw-bold text-dark mb-0">Daftar Ijazah</h1>
        </div>
        @if(auth()->user()->role === 'akademik')
            <a class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift fw-medium" href="{{ route('ijazahs.create') }}">
                <i class="bi bi-plus-circle-fill me-2"></i> Buat Ijazah Baru
            </a>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <form action="{{ route('ijazahs.index') }}" method="get" class="row g-3 mb-4">
        <div class="col-md-5">
            <div class="input-group input-group-lg shadow-sm rounded-pill bg-light border-0 overflow-hidden">
                <span class="input-group-text bg-transparent border-0 text-secondary ps-4"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none px-2 fs-6" value="{{ request('q') }}" placeholder="Cari Berdasarkan Nama, NIM, atau No. Ijazah...">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select form-select-lg shadow-sm rounded-pill bg-light border-0 px-4 text-secondary fs-6">
                <option value="">Semua Status</option>
                @foreach(['Draft','Pending Rektor','Pending Admin','Aktif','Ditolak','Revoked'] as $status)
                    <option @selected(request('status')===$status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm hover-lift fw-medium fs-6 h-100">
                <i class="bi bi-funnel me-1"></i> Terapkan Filter
            </button>
        </div>
        @if(request('q') || request('status'))
            <div class="col-12 mt-2 px-3">
                <a href="{{ route('ijazahs.index') }}" class="small text-danger text-decoration-none fw-semibold hover-lift d-inline-block">
                    <i class="bi bi-x-circle-fill me-1"></i> Reset filter
                </a>
            </div>
        @endif
    </form>
    
    @include('ijazahs.table')
</div>
@endsection
