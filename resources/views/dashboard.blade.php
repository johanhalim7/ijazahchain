@extends('layouts.app')
@section('title','Dashboard - IjazahChain')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 pb-2 border-bottom">
    <div>
        <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Control Panel</div>
        <h1 class="h3 fw-bold text-dark mb-0">{{ auth()->user()->role === 'admin' ? 'Dashboard Administrator' : (auth()->user()->role === 'rektor' ? 'Dashboard Rektor' : 'Dashboard Akademik') }}</h1>
    </div>
    @if(auth()->user()->role === 'admin')
        <div class="card px-4 py-2 border-0 shadow-sm rounded-pill bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex flex-column text-end">
                    <span class="small text-secondary fw-medium lh-1 mb-1">Status RPC Node</span>
                    <span class="fw-bold text-dark lh-1">{{ $blockchainConnection['network'] }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle {{ $blockchainConnection['connected'] ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}" style="width: 40px; height: 40px;">
                    <i class="bi bi-broadcast fs-5"></i>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role === 'akademik')
        <a class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift fw-medium" href="{{ route('ijazahs.create') }}">
            <i class="bi bi-plus-circle-fill me-2"></i> Buat Ijazah Baru
        </a>
    @endif
</div>
<div class="row g-4 mb-4">
    @foreach([['Total Ijazah',$stats['total'],'bi-mortarboard-fill', 'primary'],['Draft',$stats['draft'],'bi-file-earmark-text-fill', 'secondary'],['Menunggu Giliran Saya',$stats['pending_my_approval'],'bi-hourglass-split', 'warning'],['Menunggu Upload Admin',$stats['pending_admin'],'bi-person-badge-fill', 'info'],['Aktif',$stats['aktif'],'bi-patch-check-fill', 'success'],['Ditolak',$stats['ditolak'],'bi-x-circle-fill', 'danger'],['Revoked',$stats['revoked'],'bi-slash-circle-fill', 'danger'],['Verifikasi Publik',$stats['verifications'],'bi-search', 'primary']] as $stat)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card px-4 py-3 h-100 justify-content-center border-0 shadow-sm rounded-4 bg-white hover-lift" style="transition: transform 0.2s;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="bg-{{ $stat[3] }} bg-opacity-10 text-{{ $stat[3] }} rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi {{ $stat[2] }} fs-5"></i>
                </div>
                <span class="h4 fw-bold text-dark mb-0">{{ $stat[1] }}</span>
            </div>
            <span class="text-secondary small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">{{ $stat[0] }}</span>
        </div>
    </div>
    @endforeach
</div>
<div class="row g-4 mb-6">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">Daftar Ijazah Terbaru</h2>
                <a href="{{ route('ijazahs.index') }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            @include('ijazahs.table', ['ijazahs' => $ijazahs])
        </div>
        
        @if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'akademik')
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">Riwayat Keputusan Terbaru Anda</h2>
                <!-- Link ke semua keputusan (bisa ditambahkan route khusus nanti) -->
            </div>
            <div class="table-responsive" style="margin: 0 -0.5rem;">
                <table class="table table-hover align-middle border-0" style="border-collapse: separate; border-spacing: 0 0.35rem;">
                    <thead>
                        <tr>
                            <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">No. Ijazah</th>
                            <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Mahasiswa</th>
                            <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Status Keputusan</th>
                            <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Tanggal</th>
                            <th class="border-0 px-3 text-end"></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($myDecisions as $decision)
                        @php($isRejectedByYou = ($decision->approval_data['rejected_by'] ?? null) === auth()->id() && ($decision->approval_data['rejected_role'] ?? null) === auth()->user()->role)
                        @php($decisionAt = $isRejectedByYou ? $decision->approval_data['rejected_at'] : ($decision->approval_data['approved_'.auth()->user()->role.'_at'] ?? null))
                        @if($decisionAt)
                            @php($decisionAt = \Carbon\Carbon::parse($decisionAt))
                        @endif
                        <tr class="bg-light hover-lift" style="transition: all 0.2s;">
                            <td class="border-0 rounded-start-4 px-3 py-2 fw-bold text-dark">{{ $decision->nomor_ijazah }}</td>
                            <td class="border-0 px-3 py-2">
                                <div class="fw-bold text-dark">{{ $decision->nama }}</div>
                                <div class="small text-secondary">{{ $decision->nim }}</div>
                            </td>
                            <td class="border-0 px-3 py-2 text-center">
                                <span class="badge {{ $isRejectedByYou ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }} border-0 px-3 py-1 rounded-pill fw-medium">
                                    @if($isRejectedByYou) <i class="bi bi-x-circle-fill me-1"></i> Ditolak @else <i class="bi bi-check-circle-fill me-1"></i> Disetujui @endif
                                </span>
                            </td>
                            <td class="border-0 px-3 py-2 text-center font-monospace small text-secondary">{{ $decisionAt ? $decisionAt->format('d/m/Y H:i') : '-' }}</td>
                            <td class="border-0 rounded-end-4 px-3 py-2 text-end">
                                <a class="btn btn-sm btn-white text-primary bg-white shadow-sm fw-semibold px-3 rounded-pill" href="{{ route('ijazahs.show', $decision) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4 border-0 bg-transparent">Belum ada riwayat keputusan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">Activity Log</h2>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="vstack gap-0">
                @forelse($logs as $log)
                <div class="d-flex gap-2 align-items-start py-2 {{ !$loop->last ? 'border-bottom border-light' : '' }}">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mt-1 flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-activity" style="font-size: 0.75rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $log->activity }}</div>
                        <div class="text-secondary mt-1" style="font-size: 0.8rem; line-height: 1.4;">{{ $log->description }}</div>
                        <div class="text-muted mt-1 fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i> {{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-secondary text-center py-4"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i> Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
