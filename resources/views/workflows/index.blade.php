@extends('layouts.app')
@section('title', 'Manajemen Workflow')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-1 fw-bold text-gray-800">Manajemen Workflow</h2>
        <p class="text-secondary small mb-0">Atur urutan penandatanganan ijazah dinamis ke Smart Contract.</p>
    </div>
    <a href="{{ route('workflows.create') }}" class="btn btn-primary shadow-sm hover-lift rounded-pill px-4">
        <i class="bi bi-plus-circle-fill me-2"></i> Buat Workflow Baru
    </a>
</div>

<div class="row g-4">
    @forelse($workflows as $workflow)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 {{ $workflow->is_active ? 'border-success' : '' }}" style="{{ $workflow->is_active ? 'border: 2px solid #198754 !important;' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            Versi {{ $workflow->created_at->format('d/m/Y H:i') }}
                        </h5>
                        @if($workflow->is_active)
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> AKTIF</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">TIDAK AKTIF</span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <div class="small fw-semibold text-secondary mb-2">URUTAN (FLOW BISNIS):</div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @foreach($workflow->steps as $index => $step)
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded">{{ $step }}</span>
                                @if(!$loop->last)
                                    <i class="bi bi-arrow-right text-muted small"></i>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 p-4 pt-0 d-flex gap-2">
                    @if(!$workflow->is_active)
                        <button onclick="activateWorkflow({{ $workflow->id }})" class="btn btn-success flex-grow-1 rounded-pill fw-semibold hover-lift shadow-sm">
                            <i class="bi bi-play-fill me-1"></i> Aktifkan & Sync Blockchain
                        </button>
                        <form action="{{ route('workflows.destroy', $workflow) }}" method="POST" onsubmit="return confirm('Hapus workflow ini?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-light text-danger rounded-pill px-3 hover-lift" title="Hapus">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    @else
                        <button class="btn btn-success flex-grow-1 rounded-pill fw-semibold" disabled>
                            <i class="bi bi-broadcast me-1"></i> Sedang Digunakan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-secondary mb-3"><i class="bi bi-diagram-3" style="font-size: 3rem;"></i></div>
            <h5 class="fw-bold text-dark">Belum Ada Workflow</h5>
            <p class="text-secondary">Anda belum membuat pengaturan urutan penandatanganan.</p>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
    const CONTRACT_ADDRESS = '{{ config('blockchain.contract_address') }}';
    const CONTRACT_ABI = [
        "function setWorkflow(uint256 workflowId, address[] calldata _signers) external"
    ];

    async function activateWorkflow(id) {
        if (!confirm('Anda akan mengirim transaksi ke Blockchain untuk mengubah urutan persetujuan. Lanjutkan?')) return;
        
        try {
            // 1. Dapatkan daftar wallet address dari server
            const response = await fetch(`/admin/workflows/${id}/signers`);
            const data = await response.json();
            
            if (!response.ok) {
                alert('Gagal: ' + (data.error || 'Terjadi kesalahan'));
                return;
            }
            
            const signersAddresses = data.signers;
            console.log("Signers to sync:", signersAddresses);

            // 2. Hubungkan ke MetaMask
            if (typeof window.ethereum === 'undefined') {
                alert('MetaMask tidak terdeteksi!');
                return;
            }
            
            await window.ethereum.request({ method: 'eth_requestAccounts' });
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const contract = new ethers.Contract(CONTRACT_ADDRESS, CONTRACT_ABI, signer);

            // 3. Panggil fungsi Smart Contract
            alert('Silakan konfirmasi transaksi di MetaMask Anda...');
            const tx = await contract.setWorkflow(id, signersAddresses);
            
            alert('Transaksi sedang diproses di Blockchain... Mohon tunggu.');
            await tx.wait(); // Tunggu sampai transaksi masuk ke block

            // 4. Update status di database lokal
            const resDb = await fetch(`/admin/workflows/${id}/activate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const dbData = await resDb.json();
            
            if (dbData.success) {
                alert('Berhasil! Workflow baru telah aktif dan tersinkronisasi dengan Blockchain.');
                window.location.reload();
            } else {
                alert('Transaksi Blockchain sukses, tapi gagal update database.');
            }
            
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan: ' + (error.message || 'Error tidak diketahui'));
        }
    }
</script>
@endpush
