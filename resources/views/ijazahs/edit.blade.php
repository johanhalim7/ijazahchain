@extends('layouts.app')
@section('title','Revisi Ijazah - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom">
    <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Manajemen Dokumen</div>
    <h1 class="h3 fw-bold text-dark mb-0">Edit & Verifikasi Data Ijazah</h1>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm">
        <i class="bi bi-robot fs-4 me-3"></i>
        <div>
            <strong>AI Extraction Berhasil!</strong>
            <div class="small">{{ session('success') }}</div>
        </div>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-3 mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="post" action="{{ route('ijazahs.update', $ijazah) }}" class="row g-4" enctype="multipart/form-data">
    @csrf @method('put')
    @include('ijazahs.fields', ['ijazah' => $ijazah])
    <div class="col-12 mt-4 text-end border-top pt-4">
        <a href="{{ route('ijazahs.show', $ijazah) }}" class="btn btn-light btn-lg px-4 rounded-pill shadow-sm me-2 fw-medium text-secondary">Batal</a>
        <button class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-lift">
            <i class="bi bi-arrow-repeat me-1"></i> Update & Regenerate Hash
        </button>
    </div>
</form>
@endsection
