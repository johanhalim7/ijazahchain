@extends('layouts.app')
@section('title','Detail Ijazah - IjazahChain')
@section('content')
<div class="mb-4 pb-2 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <div class="text-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 1px;">Manajemen Dokumen &gt; Detail Ijazah</div>
        <h1 class="h3 fw-bold text-dark mb-0">Nomor Registrasi: {{ $ijazah->nomor_ijazah }}</h1>
    </div>
    @php
        $badgeClass = 'bg-light text-secondary border';
        $icon = 'bi-file-earmark-text';
        if ($ijazah->status === 'Aktif') { $badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; $icon = 'bi-check-circle-fill'; }
        elseif (str_starts_with($ijazah->status, 'Pending')) { $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; $icon = 'bi-hourglass-split'; }
        elseif (in_array($ijazah->status, ['Ditolak', 'Revoked'])) { $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; $icon = 'bi-x-circle-fill'; }
    @endphp
    <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill fw-medium fs-6 shadow-sm">
        <i class="bi {{ $icon }} me-1"></i> Status: {{ $ijazah->status }}
    </span>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        @if($ijazah->status === 'Ditolak' && $ijazah->rejected_role === 'rektor')
        <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 rounded-4 p-4 mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-x-circle-fill text-danger fs-3"></i>
                    <div>
                        <h2 class="h5 fw-bold text-danger mb-1">Keputusan Rektor: Ditolak</h2>
                        <div class="text-danger opacity-75 small">Ijazah dikembalikan ke Akademik untuk revisi.</div>
                    </div>
                </div>
            </div>
            <hr class="border-danger opacity-25">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-danger opacity-75 small fw-semibold">Tanggal Penolakan</div>
                    <div class="fw-bold text-danger">{{ optional($ijazah->rejected_at)->format('d/m/Y H:i') ?: '-' }}</div>
                </div>
                <div class="col-12 mt-2">
                    <div class="text-danger opacity-75 small fw-semibold mb-1">Catatan Evaluasi</div>
                    <div class="border border-danger border-opacity-25 rounded p-3 bg-white text-dark shadow-sm">{{ $ijazah->catatan ?: '-' }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">Informasi Lengkap Ijazah</h2>
                @if($ijazah->file_path)
                    <a href="{{ Storage::url($ijazah->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill hover-lift"><i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen Asli</a>
                @endif
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded-4 p-4 h-100 bg-light border-0">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                <i class="bi bi-person-vcard fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0 text-dark">Data Identitas</h3>
                        </div>
                        <div class="vstack gap-3">
                            <div><div class="text-secondary small fw-medium">Nama Lengkap</div><div class="fw-bold text-dark">{{ $ijazah->nama }}</div></div>
                            <div><div class="text-secondary small fw-medium">NIM / Tahun Masuk</div><div class="fw-bold text-dark">{{ $ijazah->nim }}</div></div>
                            @if($ijazah->nik)
                            <div><div class="text-secondary small fw-medium">NIK</div><div class="fw-bold text-dark">{{ $ijazah->nik }}</div></div>
                            @endif
                            <div><div class="text-secondary small fw-medium">Tempat, Tanggal Lahir</div><div class="fw-bold text-dark">{{ $ijazah->tempat_tanggal_lahir }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-4 p-4 h-100 bg-light border-0">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                <i class="bi bi-mortarboard fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0 text-dark">Data Akademik</h3>
                        </div>
                        <div class="row g-3">
                            <div class="col-12"><div class="text-secondary small fw-medium">Institusi</div><div class="fw-bold text-dark">{{ $ijazah->nama_institusi }}</div></div>
                            <div class="col-6"><div class="text-secondary small fw-medium">Fakultas</div><div class="fw-bold text-dark">{{ $ijazah->fakultas }}</div></div>
                            <div class="col-6"><div class="text-secondary small fw-medium">Program Studi</div><div class="fw-bold text-dark">{{ $ijazah->prodi }}</div></div>
                            <div class="col-6"><div class="text-secondary small fw-medium">Gelar</div><div class="fw-bold text-dark">{{ $ijazah->gelar }}</div></div>
                            <div class="col-6"><div class="text-secondary small fw-medium">Tanggal Lulus</div><div class="fw-bold text-dark">{{ optional($ijazah->tanggal_lulus)->format('d F Y') }}</div></div>
                            @if($ijazah->tanggal_diberikan)
                            <div class="col-12"><div class="text-secondary small fw-medium">Tanggal Diberikan</div><div class="fw-bold text-dark">{{ optional($ijazah->tanggal_diberikan)->format('d F Y') }}</div></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-2">
                <i class="bi bi-shield-check fs-4 text-primary"></i>
                <h2 class="h5 fw-bold mb-0 text-dark">Keamanan Cryptographic & Blockchain</h2>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="text-secondary small fw-semibold mb-1">Original Hash (Tersimpan)</div>
                    <div class="hash-box font-monospace bg-light border-0 p-3 rounded-3 text-break shadow-sm">{{ $ijazah->hash }}</div>
                </div>
                <div class="col-12">
                    <div class="text-secondary small fw-semibold mb-1">Live Validation Hash (Generated)</div>
                    <div class="hash-box font-monospace bg-light border-0 p-3 rounded-3 text-break shadow-sm">{{ $regeneratedHash }}</div>
                </div>
            </div>
            
            @if($regeneratedHash === $ijazah->hash)
                <div class="alert alert-success border-success border-opacity-25 bg-success bg-opacity-10 d-flex gap-3 align-items-center rounded-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <strong class="d-block text-success">Validasi Sukses: Hash Identik.</strong>
                        <span class="small text-success opacity-75">Data lokal dalam database belum mengalami modifikasi atau manipulasi.</span>
                    </div>
                </div>
            @else
                <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 d-flex gap-3 align-items-center rounded-3">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                    <div>
                        <strong class="d-block text-danger">Peringatan Kritis: Hash Berbeda!</strong>
                        <span class="small text-danger opacity-75">Sistem mendeteksi adanya perubahan data lokal yang tidak terotorisasi.</span>
                    </div>
                </div>
            @endif

            <hr class="my-4 opacity-10">

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-secondary small fw-semibold mb-1">Blockchain Status</div>
                    <span class="badge {{ $ijazah->blockchain_status === 'Uploaded' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-secondary bg-opacity-10 text-secondary' }} px-3 py-2 rounded-pill fw-medium border-0">
                        <i class="bi bi-link-45deg me-1"></i> {{ $ijazah->blockchain_status }}
                    </span>
                </div>
                <div class="col-md-5">
                    <div class="text-secondary small fw-semibold mb-1">Transaction Hash</div>
                    <div class="text-break font-monospace small">
                        @if($ijazah->tx_hash)
                            <a href="https://sepolia.etherscan.io/tx/{{ $ijazah->tx_hash }}" target="_blank" class="text-decoration-none text-primary fw-bold hover-lift d-inline-block" title="Lihat di Etherscan">
                                {{ substr($ijazah->tx_hash, 0, 18) }}... <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-secondary small fw-semibold mb-1">Block Number</div>
                    <div class="fw-bold text-dark fs-5">{{ $ijazah->block_number ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <!-- Workflow Approval -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
            <!-- decorative background -->
            <div class="position-absolute" style="top: -20px; right: -20px; color: #f8fafc; transform: rotate(15deg); z-index: 0;">
                <i class="bi bi-diagram-3-fill" style="font-size: 8rem;"></i>
            </div>
            
            <h2 class="h5 fw-bold mb-4 position-relative z-1 text-dark border-bottom pb-2">Alur Persetujuan (Workflow)</h2>
            
            <div class="position-relative z-1 mt-3">
                @php($steps = $ijazah->approval_data['workflow_steps'] ?? [])
                @forelse($steps as $index => $role)
                    @php($sig = ($ijazah->approval_data['signatures'] ?? [])[$role] ?? null)
                    @php($approvedAt = ($ijazah->approval_data ?? [])['approved_'.$role.'_at'] ?? null)
                    @php($label = 'Approval ' . ucfirst($role))
                    
                    <div class="d-flex gap-3 mb-4 position-relative">
                        <!-- timeline line -->
                        @if(!$loop->last)
                            <div class="position-absolute bg-secondary opacity-25" style="width: 2px; height: 100%; top: 30px; left: 19px; z-index: -1;"></div>
                        @endif
                        
                        <div class="flex-shrink-0 mt-1 bg-white">
                            @if($sig)
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-lg fs-5"></i>
                                </div>
                            @else
                                <div class="bg-light text-secondary border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-circle"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="pb-1 bg-white w-100 pe-2">
                            <div class="fw-bold text-dark">{{ $label }}</div>
                            @if($sig)
                                <div class="small text-secondary font-monospace mt-1 text-break" style="font-size: 0.75rem;">
                                    <i class="bi bi-wallet2 text-success me-1"></i> {{ substr($sig['wallet_address'], 0, 10) }}...{{ substr($sig['wallet_address'], -8) }}
                                </div>
                                <div class="small fw-semibold text-success mt-1">
                                    <i class="bi bi-clock-history"></i> Disetujui: {{ $approvedAt ? \Carbon\Carbon::parse($approvedAt)->format('d/m/Y H:i') : \Carbon\Carbon::parse($sig['signed_at'])->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                </div>
                            @else
                                <div class="small text-secondary mt-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal border-0">
                                        @if($ijazah->current_approver_role === $role)
                                            <i class="bi bi-hourglass-split text-warning"></i> Menunggu Giliran Ini
                                        @else
                                            <i class="bi bi-clock"></i> Belum Diproses
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning small">Data workflow tidak ditemukan.</div>
                @endforelse
            </div>

            @php($canSign = auth()->user()->role === $ijazah->current_approver_role)
            
            @if($canSign)
                <div class="mt-4 pt-3 border-top position-relative z-1">
                    <form id="signature-form" method="post" action="{{ route('approvals.approve', $ijazah) }}">
                        @csrf
                        <input type="hidden" name="typed_data" value='@json($typedData)'>
                        <input type="hidden" name="wallet_address">
                        <input type="hidden" name="signature">
                    </form>
                    @include('partials.metamask-sign', ['typedData' => $typedData])
                </div>
            @endif
            
            @if(auth()->user()->role === $ijazah->current_approver_role && auth()->user()->role !== 'admin' && auth()->user()->role !== ($steps[0] ?? 'akademik'))
                <div class="mt-4 pt-3 border-top position-relative z-1">
                    <h3 class="h6 fw-bold text-danger mb-3"><i class="bi bi-x-circle me-1"></i> Tolak Ijazah</h3>
                    <form method="post" action="{{ route('approvals.reject', $ijazah) }}" class="vstack gap-3">
                        @csrf
                        <textarea name="catatan" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Masukkan Alasan/Catatan Penolakan..." required></textarea>
                        <button class="btn btn-outline-danger w-100 rounded-pill hover-lift" onclick="return confirm('Tolak ijazah ini dan kembalikan ke Akademik untuk revisi?')">
                            Konfirmasi Penolakan
                        </button>
                    </form>
                </div>
            @endif
            
            @if(auth()->user()->role === ($steps[0] ?? 'akademik') && in_array($ijazah->status, ['Draft','Ditolak'], true))
                <div class="mt-4 pt-3 border-top position-relative z-1">
                    <a class="btn btn-primary w-100 rounded-pill hover-lift shadow-sm" href="{{ route('ijazahs.edit', $ijazah) }}">
                        <i class="bi bi-pencil-square me-1"></i> Revisi Data Ijazah
                    </a>
                </div>
            @endif
        </div>

        @if(auth()->user()->role === 'admin' && $ijazah->status === 'Pending Upload' && isset(($ijazah->approval_data['signatures'] ?: [])['admin']))
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #0f766e, #021a17);" data-bs-theme="dark">
            <div class="d-flex align-items-center gap-3 mb-3">
                <i class="bi bi-cloud-upload fs-3 text-white"></i>
                <h2 class="h5 fw-bold mb-0 text-white">Eksekusi Smart Contract</h2>
            </div>
            <p class="small opacity-75 mb-4">Simpan Ijazah secara permanen ke jaringan Blockchain. Pastikan ekstensi MetaMask Anda sudah terhubung.</p>
            
            <button type="button" class="btn btn-light text-primary w-100 mb-3 fw-bold rounded-pill hover-lift shadow-lg" id="call-contract" data-number="{{ $ijazah->nomor_ijazah }}" data-hash="{{ $ijazah->hash }}">
                <i class="bi bi-cpu"></i> Execute Transaction
            </button>
            <div id="blockchain-upload-status" class="small text-white opacity-75 mb-4 text-center">Klik tombol di atas untuk membuka MetaMask.</div>
            
            <form method="post" action="{{ route('approvals.upload', $ijazah) }}" class="vstack gap-3 border-top border-white border-opacity-25 pt-4">
                @csrf
                <div>
                    <label class="form-label small fw-semibold">Transaction Hash Bukti (0x...)</label>
                    <input id="tx_hash" name="tx_hash" class="form-control bg-white bg-opacity-10 border-0 text-white shadow-none" placeholder="0x..." required style="backdrop-filter: blur(5px);">
                </div>
                <div>
                    <label class="form-label small fw-semibold">Block Number (Opsional)</label>
                    <input id="block_number" name="block_number" class="form-control bg-white bg-opacity-10 border-0 text-white shadow-none" placeholder="Nomor Blok (Opsional)" style="backdrop-filter: blur(5px);">
                </div>
                <button class="btn btn-outline-light rounded-pill hover-lift w-100 mt-2">
                    <i class="bi bi-save2"></i> Konfirmasi Simpan
                </button>
            </form>
        </div>
        @endif

        @if($ijazah->qr_code)
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <h2 class="h6 fw-bold mb-3 text-dark">QR Code Verifikasi Publik</h2>
            <div class="bg-light p-3 rounded-4 d-inline-block mx-auto mb-3 border">
                <img class="img-fluid" alt="QR Code" src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($ijazah->qr_code) }}">
            </div>
            <div class="small">
                <a class="text-primary text-decoration-none text-break fw-semibold hover-lift d-inline-block" href="{{ $ijazah->qr_code }}" target="_blank">{{ $ijazah->qr_code }}</a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
document.getElementById('call-contract')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;
    const status = document.getElementById('blockchain-upload-status');
    const setStatus = (message, className = 'small text-white opacity-100 mb-3 text-center fw-bold') => {
        if (status) {
            status.className = className;
            status.textContent = message;
        }
    };

    const contractAddress = @json(config('blockchain.contract_address'));
    if (!contractAddress) {
        setStatus('Contract address belum diisi di .env.', 'small text-warning mb-3 text-center fw-bold');
        return;
    }

    if (!window.ethereum) {
        setStatus('MetaMask tidak terdeteksi. Buka halaman ini di browser yang sudah memiliki MetaMask.', 'small text-warning mb-3 text-center fw-bold');
        return;
    }

    if (!window.ethers) {
        setStatus('Library ethers.js gagal dimuat. Periksa koneksi internet/CDN browser.', 'small text-warning mb-3 text-center fw-bold');
        return;
    }

    button.disabled = true;
    setStatus('Membuka MetaMask...', 'small text-white opacity-100 mb-3 text-center fw-bold');

    try {
        const version = @json($ijazah->version);
        const workflowId = @json($ijazah->workflow_id);
        const steps = @json($ijazah->approval_data['workflow_steps'] ?? []);
        
        let signatures = [];
        const sigData = @json($ijazah->approval_data['signatures'] ?? []);
        for (let i = 0; i < steps.length; i++) {
            const role = steps[i];
            const sigObj = sigData[role];
            if (!sigObj || !sigObj.signature) {
                setStatus('Tanda tangan untuk role ' + role + ' belum lengkap.', 'small text-warning mb-3 text-center fw-bold');
                button.disabled = false;
                return;
            }
            signatures.push(sigObj.signature);
        }

        const abi = [
            'function approveAdmin(string nomorIjazah, string diplomaHash, uint256 version, uint256 workflowId, bytes[] calldata signatures) external',
            'error Unauthorized()',
            'error AlreadyExists()',
            'error InvalidHash()',
            'error InvalidSignature(uint256 index)',
            'error InvalidWorkflow()'
        ];
        const provider = new ethers.BrowserProvider(window.ethereum);
        await provider.send('eth_requestAccounts', []);

        const network = await provider.getNetwork();
        if (Number(network.chainId) !== 11155111) {
            await window.ethereum.request({
                method: 'wallet_switchEthereumChain',
                params: [{ chainId: '0xaa36a7' }],
            });
        }

        const signer = await provider.getSigner();

        const contract = new ethers.Contract(contractAddress, abi, signer);
        
        // Ethers v6 akan secara otomatis melakukan simulasi (eth_estimateGas) saat memanggil fungsi ini.
        // Jika gagal, akan langsung throw ke blok catch di bawah.

        setStatus('Menunggu konfirmasi transaksi di MetaMask...', 'small text-white opacity-100 mb-3 text-center fw-bold');

        const tx = await contract.approveAdmin(button.dataset.number, button.dataset.hash, version, workflowId, signatures);
        document.getElementById('tx_hash').value = tx.hash;
        setStatus('Transaksi terkirim. Menunggu konfirmasi block...', 'small text-white opacity-100 mb-3 text-center fw-bold');

        const receipt = await tx.wait();
        if (receipt?.blockNumber) {
            document.getElementById('block_number').value = receipt.blockNumber;
            setStatus('Transaksi terkonfirmasi. Transaction hash terisi otomatis.', 'small text-white fw-bold bg-success bg-opacity-50 px-3 py-2 rounded mb-3 text-center border border-success border-opacity-50');
        } else {
            setStatus('Transaksi terkirim, block number cek manual di Etherscan.', 'small text-warning mb-3 text-center fw-bold');
        }
    } catch (error) {
        const message = error?.shortMessage || error?.reason || error?.message || 'Transaksi dibatalkan atau gagal.';
        setStatus(message, 'small text-warning mb-3 text-center fw-bold');
    } finally {
        button.disabled = false;
    }
});
</script>
@endpush
@endsection
