@extends('layouts.app')
@section('title', 'Buat Workflow Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('workflows.index') }}" class="text-decoration-none text-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Manajemen Workflow
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 mx-auto bg-white overflow-hidden" style="max-width: 700px;">
    <div class="card-header bg-white border-bottom p-4 text-center">
        <h2 class="h5 fw-bold text-gray-800 mb-0">Rancang Urutan Persetujuan (Workflow)</h2>
        <p class="text-secondary small mt-2 mb-0">Atur siapa saja yang berhak dan wajib menandatangani Ijazah secara berurutan.</p>
    </div>
    
    <form action="{{ route('workflows.store') }}" method="POST" id="workflow-form">
        @csrf
        
        <div class="card-body p-4 bg-light">
            <!-- Container untuk Steps -->
            <div id="steps-container" class="vstack gap-3">
                <!-- Step 1 (Default: Akademik) -->
                <div class="card border-0 shadow-sm rounded-3 step-card">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold step-number" style="width: 32px; height: 32px;">1</div>
                        <div class="flex-grow-1">
                            <select name="steps[]" class="form-select border-0 bg-light" required>
                                <option value="akademik" selected>Akademik (Pembuat Draft)</option>
                                @foreach($roles as $role)
                                    @if($role !== 'akademik')
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="card border-0 shadow-sm rounded-3 step-card">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold step-number" style="width: 32px; height: 32px;">2</div>
                        <div class="flex-grow-1">
                            <select name="steps[]" class="form-select border-0 bg-light" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ $role === 'rektor' ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-light text-danger btn-remove-step"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Step -->
            <div class="text-center mt-4">
                <button type="button" id="btn-add-step" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold hover-lift">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Tahapan
                </button>
            </div>

            <!-- Tahap Terakhir: Admin (Otomatis) -->
            <div class="mt-4 pt-4 border-top">
                <div class="card border border-success border-opacity-50 shadow-sm rounded-3 bg-success bg-opacity-10">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;"><i class="bi bi-lock-fill"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-success">Admin (Otomatis)</div>
                            <div class="small text-success opacity-75">Sistem akan secara otomatis menambahkan Admin di urutan terakhir untuk mengeksekusi Ijazah ke Blockchain.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 border-0 rounded-3 small shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i> Workflow baru tidak akan langsung aktif. Anda dapat mengaktifkannya di halaman daftar Workflow setelah menyinkronkannya dengan Smart Contract.
            </div>
        </div>

        <div class="card-footer bg-white border-top p-4 text-end">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 hover-lift shadow-sm">
                <i class="bi bi-save me-1"></i> Simpan Workflow
            </button>
        </div>
    </form>
</div>

<!-- Template untuk Step Baru -->
<template id="step-template">
    <div class="card border-0 shadow-sm rounded-3 step-card mt-3">
        <div class="card-body p-3 d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold step-number" style="width: 32px; height: 32px;"></div>
            <div class="flex-grow-1">
                <select name="steps[]" class="form-select border-0 bg-light" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-light text-danger btn-remove-step"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('steps-container');
        const btnAdd = document.getElementById('btn-add-step');
        const template = document.getElementById('step-template');

        function updateStepNumbers() {
            const cards = container.querySelectorAll('.step-card');
            cards.forEach((card, index) => {
                card.querySelector('.step-number').textContent = index + 1;
            });
        }

        btnAdd.addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
            updateStepNumbers();
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-step')) {
                const card = e.target.closest('.step-card');
                card.remove();
                updateStepNumbers();
            }
        });
    });
</script>
@endpush
@endsection
