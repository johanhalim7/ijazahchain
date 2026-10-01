@extends('layouts.app')
@section('title','Riwayat Keputusan Rektor - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Otoritas Approval</div>
            <h1 class="h3 fw-bold text-dark mb-0">Riwayat Keputusan Rektor</h1>
            <p class="text-secondary mb-0 mt-1">Daftar seluruh keputusan persetujuan maupun penolakan ijazah yang telah Anda lakukan.</p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <form action="{{ route('rector-decisions.index') }}" method="get" class="mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group input-group-lg shadow-sm rounded-pill bg-light border-0 overflow-hidden">
                    <span class="input-group-text bg-transparent border-0 text-secondary ps-4"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none px-2 fs-6" value="{{ request('search') }}" placeholder="Cari Berdasarkan No. Ijazah/Nama/NIM...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-lg shadow-sm rounded-pill bg-light border-0 px-4 text-secondary fs-6">
                    <option value="">Semua Status Keputusan</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm hover-lift fw-medium fs-6 h-100">
                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </form>
    
    <div class="table-responsive" style="margin: 0 -0.5rem;">
        <table class="table table-hover align-middle border-0" style="border-collapse: separate; border-spacing: 0 0.35rem;">
            <thead>
                <tr>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-4">No. Ijazah</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Mahasiswa</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Program Studi</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Status Keputusan</th>
                    <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Waktu Eksekusi</th>
                    <th class="border-0 px-4 text-end"></th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($decisions as $decision)
                @php($isRejectedByYou = $decision->rejected_by === auth()->id() && $decision->rejected_role === 'rektor')
                @php($decisionAt = $isRejectedByYou ? $decision->rejected_at : $decision->approved_rektor_at)
                <tr class="bg-white shadow-sm hover-lift" style="transition: all 0.2s;">
                    <td class="border-0 rounded-start-4 px-4 py-2">
                        <div class="fw-bold text-dark">{{ $decision->nomor_ijazah }}</div>
                    </td>
                    <td class="border-0 px-3 py-2">
                        <div class="fw-bold text-dark">{{ $decision->nama }}</div>
                        <div class="small text-secondary">{{ $decision->nim }}</div>
                    </td>
                    <td class="border-0 px-3 py-2 text-secondary">{{ $decision->prodi }}</td>
                    <td class="border-0 px-3 py-2 text-center">
                        <span class="badge {{ $isRejectedByYou ? 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25' : 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' }} px-3 py-1 rounded-pill fw-medium">
                            @if($isRejectedByYou) <i class="bi bi-x-circle-fill me-1"></i> Ditolak @else <i class="bi bi-check-circle-fill me-1"></i> Disetujui @endif
                        </span>
                    </td>
                    <td class="border-0 px-3 py-2 text-center font-monospace small text-secondary">
                        {{ $decisionAt ? $decisionAt->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="border-0 rounded-end-4 px-4 py-2 text-end">
                        <a class="btn btn-sm btn-light text-primary fw-semibold px-3 rounded-pill" href="{{ route('rector-decisions.show', $decision) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-5 border-0 bg-transparent shadow-none">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                    Belum ada riwayat keputusan.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        @if(method_exists($decisions, 'links')){{ $decisions->links() }}@endif
    </div>
</div>
@endsection
