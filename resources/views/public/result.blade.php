@extends('layouts.app')
@section('title','Hasil Verifikasi - '.$result)
@section('content')
<section class="py-5">
    <div class="container" style="max-width:900px">
        <!-- Loading Animation (Artificial) -->
        <div id="initial-loading" class="text-center py-5 my-5">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
            <h2 class="h5 mt-3 fw-bold text-secondary">Memverifikasi Data...</h2>
            <p class="text-secondary small">Sistem sedang mencocokkan data kriptografi dengan database dan blockchain.</p>
        </div>

        <!-- Result Content (Hidden Initially) -->
        <div id="result-content" class="d-none">
            <a href="{{ route('verify.form') }}" class="btn btn-link px-0"><i class="bi bi-arrow-left"></i> Kembali ke Verifikasi</a>
            <div class="card p-4 text-center">
            @if($result === 'VALID')
                <i class="bi bi-patch-check-fill text-success display-3"></i>
                <h1 class="h3 fw-bold mt-3">VALID</h1>
                <p class="text-secondary">Ijazah ini telah terdaftar dan aktif di blockchain.</p>
            @elseif($result === 'REVOKED')
                <i class="bi bi-slash-circle-fill text-danger display-3"></i>
                <h1 class="h3 fw-bold mt-3">REVOKED</h1>
                <p class="text-secondary">Ijazah pernah terdaftar, namun validitasnya telah dicabut.</p>
            @else
                <i class="bi bi-x-circle-fill text-danger display-3"></i>
                <h1 class="h3 fw-bold mt-3">INVALID</h1>
                <p class="text-secondary">Data tidak ditemukan atau hash tidak sesuai dengan data blockchain.</p>
            @endif
            <div class="small fw-semibold text-secondary mt-3 text-start">Ijazah Hash (SHA-256)</div>
            <div class="hash-box text-start mt-1">{{ $hash }}</div>
        </div>
        @if($ijazah)
        @if($dbTampered)
            <div class="alert alert-warning border-warning border-opacity-50 rounded-4 p-4 mb-4 shadow-sm">
                <div class="d-flex gap-3 align-items-start">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                    <div>
                        <h2 class="h5 fw-bold text-dark mb-1">DATA LOKAL DIMANIPULASI</h2>
                        <div class="small text-dark opacity-75" style="line-height: 1.6;">
                            @if($method === 'file')
                                Selamat! Dokumen PDF ijazah yang Anda unggah terbukti <strong>ASLI</strong> dan sah di jaringan Blockchain. Namun, sistem IjazahChain mendeteksi bahwa data teks di database lokal kampus telah diubah secara ilegal oleh pihak yang tidak bertanggung jawab.<br><br>
                                <strong>Abaikan teks data diri di bawah ini</strong>, dan silakan merujuk murni pada teks yang tercetak di dalam dokumen PDF Anda.
                            @else
                                Sistem IjazahChain mendeteksi bahwa data teks di database lokal kampus telah diubah secara ilegal oleh pihak yang tidak bertanggung jawab. Walaupun hash aslinya sah di Blockchain, data teks yang ditampilkan di bawah ini <strong>TIDAK BISA DIPERCAYA</strong>.<br><br>
                                Harap minta pemilik ijazah untuk memberikan dokumen PDF aslinya dan verifikasi ulang menggunakan metode Upload Dokumen.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="card p-4 mt-3">
            <h2 class="h5 fw-bold mb-3">Informasi Ijazah</h2>
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
            <div class="border rounded p-3">
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
                    <div class="col-md-6"><div class="text-secondary small">Transaction Hash</div>
                        <div class="fw-semibold text-break">
                            <a href="https://sepolia.etherscan.io/tx/{{ $ijazah->tx_hash }}" target="_blank" class="text-decoration-none text-primary" title="Lihat di Etherscan">
                                {{ substr($ijazah->tx_hash, 0, 20) }}... <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6"><div class="text-secondary small">Block Number</div><div class="fw-semibold">{{ $ijazah->block_number ?: '-' }}</div></div>
                </div>
            </div>
        </div>

        @if($ijazah->tx_hash)
        <div class="card p-4 mt-3" id="blockchain-check-card">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="step-dot"><i class="bi bi-shield-check"></i></span>
                <div>
                    <h2 class="h5 fw-bold mb-0">Cross-Check Blockchain</h2>
                    <div class="small text-secondary">Verifikasi langsung ke smart contract Ethereum Sepolia.</div>
                </div>
            </div>
            <div id="bc-loading" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="ms-2 text-secondary">Menghubungi smart contract...</span>
            </div>
            <div id="bc-result" class="d-none">
                <div id="bc-alert" class="alert mb-3"></div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-secondary small">Hash Cocok</div>
                        <div id="bc-hash-match" class="fw-semibold">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-secondary small">Status On-Chain</div>
                        <div id="bc-status" class="fw-semibold">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-secondary small">Sumber Verifikasi</div>
                        <div class="fw-semibold text-break small">Smart Contract<br>
                            <a href="https://sepolia.etherscan.io/address/{{ config('blockchain.contract_address') }}" target="_blank" class="text-decoration-none text-primary" title="Lihat Contract di Etherscan">
                                {{ substr(config('blockchain.contract_address'), 0, 10) }}... <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bc-error" class="d-none">
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <span id="bc-error-msg"></span>
                </div>
            </div>
        </div>
        @endif
        @endif
        </div> <!-- End of #result-content -->
    </div>
</section>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.getElementById('initial-loading').classList.add('d-none');
            document.getElementById('result-content').classList.remove('d-none');
        }, 1000); // Tahan animasi loading selama 1 detik
    });
</script>
@if($ijazah && $ijazah->tx_hash)
<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
(async function() {
    const loading = document.getElementById('bc-loading');
    const resultEl = document.getElementById('bc-result');
    const errorEl = document.getElementById('bc-error');
    const alertEl = document.getElementById('bc-alert');

    const contractAddress = @json(config('blockchain.contract_address'));
    const rpcUrl = @json(config('blockchain.rpc_url'));
    const nomorIjazah = @json($ijazah->nomor_ijazah);
    const documentHash = @json($hash);
    const isDbManipulated = @json($dbTampered);

    if (!contractAddress || !rpcUrl) {
        loading.classList.add('d-none');
        errorEl.classList.remove('d-none');
        document.getElementById('bc-error-msg').textContent = 'Konfigurasi blockchain (contract address / RPC URL) belum diatur.';
        return;
    }

    try {
        const provider = new ethers.JsonRpcProvider(rpcUrl);
        const abi = [
            'function verify(string nomorIjazah, string diplomaHash) external view returns (bool valid, bool revoked)',
        ];
        const contract = new ethers.Contract(contractAddress, abi, provider);

        const [valid, revoked] = await contract.verify(nomorIjazah, documentHash);

        loading.classList.add('d-none');
        resultEl.classList.remove('d-none');

        const hashMatchEl = document.getElementById('bc-hash-match');
        const statusEl = document.getElementById('bc-status');

        if (valid) {
            alertEl.className = 'alert alert-success mb-3';
            alertEl.innerHTML = '<i class="bi bi-patch-check-fill me-2"></i><strong>Terverifikasi di Blockchain.</strong> Hash data cocok dengan hash yang tersimpan di smart contract dan ijazah berstatus aktif.';
            hashMatchEl.innerHTML = '<span class="badge status-Aktif">Cocok</span>';
            statusEl.innerHTML = '<span class="badge status-Aktif">Aktif (Uploaded)</span>';
        } else if (revoked) {
            alertEl.className = 'alert alert-danger mb-3';
            alertEl.innerHTML = '<i class="bi bi-slash-circle-fill me-2"></i><strong>Dicabut di Blockchain.</strong> Ijazah ini pernah terdaftar di smart contract, namun statusnya telah di-revoke.';
            hashMatchEl.innerHTML = '<span class="badge status-Aktif">Cocok</span>';
            statusEl.innerHTML = '<span class="badge status-Revoked">Revoked</span>';
        } else {
            alertEl.className = 'alert alert-danger mb-3';
            alertEl.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i><strong>Tidak ditemukan di Blockchain.</strong> Hash tidak cocok atau data tidak terdaftar di smart contract. Ini berarti data yang Anda verifikasi palsu.';
            hashMatchEl.innerHTML = '<span class="badge status-Ditolak">Tidak Cocok</span>';
            statusEl.innerHTML = '<span class="badge status-Ditolak">Tidak Ditemukan</span>';
        }
    } catch (err) {
        loading.classList.add('d-none');
        errorEl.classList.remove('d-none');
        document.getElementById('bc-error-msg').textContent = 'Gagal menghubungi blockchain: ' + (err.shortMessage || err.message || 'Unknown error');
    }
})();
</script>
@endif
@endpush
@endsection
