@extends('layouts.app')
@section('title', 'Input Ijazah Baru (AI Mode)')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Input Ijazah</h1>
            <p class="text-secondary mt-1">Sistem akan membaca isi ijazah menggunakan Artificial Intelligence.</p>
        </div>
        <a href="{{ route('ijazahs.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-robot fs-5"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Upload Dokumen Ijazah Asli</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('ijazahs.store') }}" method="POST" enctype="multipart/form-data" id="ai-upload-form">
                        @csrf
                        
                        <div class="border rounded-4 p-5 text-center bg-light position-relative" id="upload-zone" style="border: 2px dashed #cbd5e1 !important; cursor: pointer; transition: all 0.3s;">
                            <i class="bi bi-cloud-arrow-up text-primary mb-3 d-block" style="font-size: 4rem;"></i>
                            <h3 class="h5 fw-bold text-dark mb-2">Pilih File Ijazah</h3>
                            <p class="small text-secondary mb-4">Mendukung format PDF (Maksimal 5MB)</p>
                            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="form-control form-control-lg shadow-sm mx-auto" style="max-width: 400px; position: relative; z-index: 2;" required id="file-input">
                        </div>

                        <div class="alert alert-info mt-4 d-flex align-items-center rounded-3">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div class="small">
                                <strong>Penting:</strong> Pastikan hasil scan/foto jelas dan tidak buram agar Google Gemini AI dapat membaca teks di dalamnya dengan akurat. Anda dapat mengedit hasilnya nanti.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" id="btn-submit" onclick="this.innerHTML='<i class=\'spinner-border spinner-border-sm me-2\'></i> AI Sedang Membaca...'; this.classList.add('disabled'); this.form.submit();">
                                <i class="bi bi-stars me-2"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
