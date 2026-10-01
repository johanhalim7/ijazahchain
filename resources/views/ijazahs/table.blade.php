<div class="table-responsive" style="margin: 0 -0.5rem;">
    <table class="table table-hover align-middle border-0" style="border-collapse: separate; border-spacing: 0 0.35rem;">
        <thead>
            <tr>
                <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-4">No. Ijazah</th>
                <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Mahasiswa</th>
                <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3">Program Studi</th>
                <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Status Validasi</th>
                <th class="border-0 text-secondary fw-semibold small text-uppercase tracking-wider px-3 text-center">Blockchain</th>
                <th class="border-0 px-4 text-end"></th>
            </tr>
        </thead>
        <tbody class="border-top-0">
        @forelse($ijazahs as $ijazah)
            <tr class="bg-white shadow-sm hover-lift" style="transition: all 0.2s;">
                <td class="border-0 rounded-start-4 px-4 py-2">
                    <div class="fw-bold text-dark">{{ $ijazah->nomor_ijazah }}</div>
                </td>
                <td class="border-0 px-3 py-2">
                    <div class="fw-bold text-dark">{{ $ijazah->nama }}</div>
                    <div class="small text-secondary">{{ $ijazah->nim }}</div>
                </td>
                <td class="border-0 px-3 py-2 text-secondary">{{ $ijazah->prodi }}</td>
                <td class="border-0 px-3 py-2 text-center">
                    @php
                        $badgeClass = 'bg-light text-secondary border';
                        $icon = 'bi-file-earmark-text';
                        if ($ijazah->status === 'Aktif') { $badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; $icon = 'bi-check-circle-fill'; }
                        elseif (str_starts_with($ijazah->status, 'Pending')) { $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; $icon = 'bi-hourglass-split'; }
                        elseif (in_array($ijazah->status, ['Ditolak', 'Revoked'])) { $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; $icon = 'bi-x-circle-fill'; }
                    @endphp
                    <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill fw-medium">
                        <i class="bi {{ $icon }} me-1"></i> {{ $ijazah->status }}
                    </span>
                </td>
                <td class="border-0 px-3 py-2 text-center">
                    <span class="badge {{ $ijazah->blockchain_status === 'Uploaded' ? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25' : 'bg-light text-secondary border' }} px-3 py-1 rounded-pill fw-medium">
                        @if($ijazah->blockchain_status === 'Uploaded') <i class="bi bi-link-45deg me-1"></i> @endif
                        {{ $ijazah->blockchain_status }}
                    </span>
                </td>
                <td class="border-0 rounded-end-4 px-4 py-2 text-end">
                    <a class="btn btn-sm btn-light text-primary fw-semibold px-3 rounded-pill" href="{{ route('ijazahs.show', $ijazah) }}">Detail</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-5 border-0 bg-transparent shadow-none">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                Belum ada data ijazah yang ditemukan.
            </td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($ijazahs, 'hasPages') && $ijazahs->hasPages())
<div class="mt-4">
    {{ $ijazahs->links() }}
</div>
@endif
