@php($ijazah = $ijazah ?? null)

<div class="col-12">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white hover-lift" style="transition: transform 0.2s;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                <i class="bi bi-file-earmark-pdf fs-3"></i>
            </div>
            <div>
                <h2 class="h6 fw-bold mb-1 text-dark">Dokumen Ijazah</h2>
                <div class="small text-secondary">Upload file scan ijazah baru (opsional jika hanya merevisi data).</div>
            </div>
        </div>
        <div>
            <label class="form-label fw-semibold small text-dark mb-1">Ganti File (Max 5MB)</label>
            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="form-control form-control-lg bg-light border-0 shadow-none px-3">
            @if($ijazah && $ijazah->file_path)
                <div class="small text-success mt-2 fw-medium"><i class="bi bi-check-circle-fill me-1"></i> File ijazah saat ini sudah tersimpan. Unggah file baru hanya jika ingin menggantinya.</div>
            @endif
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white hover-lift" style="transition: transform 0.2s;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                <i class="bi bi-person-vcard fs-3"></i>
            </div>
            <div>
                <h2 class="h6 fw-bold mb-1 text-dark">Data Identitas Mahasiswa</h2>
                <div class="small text-secondary">Informasi pribadi pemilik ijazah.</div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold small text-dark mb-1">Nama Sesuai Ijazah <span class="text-danger">*</span></label>
                <input name="nama" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('nama', $ijazah?->nama) }}" placeholder="Masukkan Nama Lengkap" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">NIM / Tahun Masuk <span class="text-danger">*</span></label>
                <input name="nim" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('nim', $ijazah?->nim) }}" placeholder="Contoh: 210511011 / 2021" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">NIK</label>
                <input name="nik" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('nik', $ijazah?->nik) }}" placeholder="Masukkan NIK KTP">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small text-dark mb-1">Tempat, Tanggal Lahir <span class="text-danger">*</span></label>
                <input name="tempat_tanggal_lahir" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('tempat_tanggal_lahir', $ijazah?->tempat_tanggal_lahir) }}" placeholder="Contoh: Cirebon, 23 September 2002" required>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white hover-lift" style="transition: transform 0.2s;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                <i class="bi bi-mortarboard fs-3"></i>
            </div>
            <div>
                <h2 class="h6 fw-bold mb-1 text-dark">Data Akademik Ijazah</h2>
                <div class="small text-secondary">Informasi institusi, prodi, dan kelulusan.</div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold small text-dark mb-1">Nama Institusi <span class="text-danger">*</span></label>
                <input name="nama_institusi" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('nama_institusi', $ijazah?->nama_institusi) }}" placeholder="Masukkan Nama Institusi/Universitas" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Fakultas <span class="text-danger">*</span></label>
                <input name="fakultas" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('fakultas', $ijazah?->fakultas) }}" placeholder="Masukkan Nama Fakultas" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Program Studi <span class="text-danger">*</span></label>
                <input name="prodi" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('prodi', $ijazah?->prodi) }}" placeholder="Masukkan Nama Program Studi" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Nomor Seri Ijazah <span class="text-danger">*</span></label>
                <input name="nomor_ijazah" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('nomor_ijazah', $ijazah?->nomor_ijazah) }}" placeholder="Masukkan Nomor Seri Ijazah" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Gelar <span class="text-danger">*</span></label>
                <input name="gelar" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('gelar', $ijazah?->gelar) }}" placeholder="Contoh: Sarjana Teknik (S.T.)" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Tanggal Lulus <span class="text-danger">*</span></label>
                <input name="tanggal_lulus" type="date" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('tanggal_lulus', optional($ijazah?->tanggal_lulus)->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark mb-1">Tanggal Diberikan</label>
                <input name="tanggal_diberikan" type="date" class="form-control form-control-lg bg-light border-0 shadow-none px-3" value="{{ old('tanggal_diberikan', optional($ijazah?->tanggal_diberikan)->format('Y-m-d')) }}">
            </div>
        </div>
    </div>
</div>
