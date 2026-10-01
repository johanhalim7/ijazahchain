@extends('layouts.app')
@section('title','Detail Keputusan Rektor - IjazahChain')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-secondary">Dashboard > Riwayat Keputusan > Detail</div>
        <h1 class="h3 fw-bold mb-1">Detail Keputusan Rektor</h1>
        <p class="text-secondary mb-0">Ringkasan keputusan Anda terhadap data ijazah.</p>
    </div>
</div>

<div class="card p-4 mb-3">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1">Status Keputusan</h2>
            <div class="text-secondary small">Keputusan tersimpan sebagai bagian dari audit trail approval.</div>
        </div>
        <span class="badge {{ $decision['badge'] }} fs-6">{{ $decision['status'] }}</span>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="text-secondary small">Tanggal Keputusan</div>
            <div class="fw-semibold">{{ $decision['decided_at'] ? $decision['decided_at']->format('d/m/Y H:i') : '-' }}</div>
        </div>
        <div class="col-md-6">
            <div class="text-secondary small">Diputuskan Oleh</div>
            <div class="fw-semibold">{{ auth()->user()->nama }}</div>
        </div>
        <div class="col-12">
            <div class="text-secondary small">{{ $decision['status'] === 'Ditolak' ? 'Alasan Penolakan' : 'Deskripsi Keputusan' }}</div>
            <div class="border rounded p-3 bg-light">{{ $decision['description'] ?: '-' }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3">Data Ijazah</h2>

            <div class="border rounded p-3 mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-dot"><i class="bi bi-person-vcard"></i></span>
                    <div>
                        <h3 class="h6 fw-bold mb-0">Data Identitas Mahasiswa</h3>
                        <div class="small text-secondary">Informasi pribadi pemilik ijazah.</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-secondary small">Nama</div><div class="fw-semibold">{{ $ijazah->nama }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">NIM / Tahun Masuk</div><div class="fw-semibold">{{ $ijazah->nim }}</div></div>
                    @if($ijazah->nik)
                    <div class="col-md-6"><div class="text-secondary small">NIK</div><div class="fw-semibold">{{ $ijazah->nik }}</div></div>
                    @endif
                    <div class="col-md-6"><div class="text-secondary small">Tempat, Tanggal Lahir</div><div class="fw-semibold">{{ $ijazah->tempat_tanggal_lahir }}</div></div>
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-dot"><i class="bi bi-mortarboard"></i></span>
                    <div>
                        <h3 class="h6 fw-bold mb-0">Data Akademik Ijazah</h3>
                        <div class="small text-secondary">Informasi institusi, program studi, dan kelulusan.</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-secondary small">No. Ijazah</div><div class="fw-semibold">{{ $ijazah->nomor_ijazah }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Tanggal Lulus</div><div class="fw-semibold">{{ optional($ijazah->tanggal_lulus)->format('d/m/Y') }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Institusi</div><div class="fw-semibold">{{ $ijazah->nama_institusi }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Fakultas</div><div class="fw-semibold">{{ $ijazah->fakultas }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Program Studi</div><div class="fw-semibold">{{ $ijazah->prodi }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Gelar</div><div class="fw-semibold">{{ $ijazah->gelar }}</div></div>
                </div>
            </div>

            <div class="border rounded p-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-dot"><i class="bi bi-hash"></i></span>
                    <div>
                        <h3 class="h6 fw-bold mb-0">Hash & Status Blockchain</h3>
                        <div class="small text-secondary">Status integritas dan penyimpanan hash ijazah.</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-secondary small">Status Ijazah</div><span class="badge {{ str_starts_with($ijazah->status,'Pending') ? 'status-Pending' : 'status-'.$ijazah->status }}">{{ $ijazah->status }}</span></div>
                    <div class="col-md-6"><div class="text-secondary small">Status Blockchain</div><span class="badge text-bg-light">{{ $ijazah->blockchain_status }}</span></div>
                    <div class="col-12"><div class="text-secondary small mb-1">Hash SHA-256</div><div class="hash-box">{{ $ijazah->hash }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Transaction Hash</div><div class="fw-semibold text-break">{{ $ijazah->tx_hash ?: '-' }}</div></div>
                    <div class="col-md-6"><div class="text-secondary small">Block Number</div><div class="fw-semibold">{{ $ijazah->block_number ?: '-' }}</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold">Workflow Approval</h2>
            @foreach(['akademik'=>'Approval Akademik','rektor'=>'Approval Rektor','admin'=>'Approval Admin'] as $role => $label)
                @php($sig = ($ijazah->signatures ?: [])[$role] ?? null)
                @php($approvedAt = [
                    'akademik' => $ijazah->approved_akademik_at,
                    'rektor' => $ijazah->approved_rektor_at,
                    'admin' => $ijazah->approved_admin_at,
                ][$role] ?? null)
                <div class="d-flex gap-3 mb-3">
                    <span class="step-dot">{{ $sig ? '✓' : '○' }}</span>
                    <div>
                        <div class="fw-semibold">{{ $label }}</div>
                        <div class="small text-secondary text-break">{{ $sig['wallet_address'] ?? 'Menunggu digital signature' }}</div>
                        @if($sig)
                            <div class="small text-success mt-2">
                                <i class="bi bi-clock"></i>
                                Ditandatangani pada {{ $approvedAt ? $approvedAt->format('d/m/Y H:i') : \Carbon\Carbon::parse($sig['signed_at'])->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                            </div>
                        @else
                            <div class="small text-secondary mt-2">Belum ditandatangani</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
