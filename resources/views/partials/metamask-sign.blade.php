@props(['typedData', 'button' => 'Tanda Tangan Digital', 'formId' => 'signature-form'])

<div class="d-grid gap-2">
    @php($officialWallet = auth()->user()?->wallet_address)
    @if($officialWallet)
        <button type="button" class="btn btn-primary rounded-pill hover-lift shadow-sm fw-semibold" data-sign-button data-form="{{ $formId }}" data-registered-wallet="{{ strtolower($officialWallet) }}">
            <i class="bi bi-pen me-1"></i> {{ $button }}
        </button>
        <div class="small text-secondary mt-1 text-center"><i class="bi bi-shield-check text-success"></i> Menggunakan wallet MetaMask terdaftar Anda.</div>
    @else
        <a href="{{ route('wallet.show') }}" class="btn btn-warning rounded-pill hover-lift shadow-sm fw-semibold">
            <i class="bi bi-wallet2 me-1"></i> Hubungkan MetaMask di Profil
        </a>
        <div class="small text-danger mt-1 text-center fw-medium">Anda belum menghubungkan Wallet Address.</div>
    @endif
</div>

@once
@push('scripts')
<script>
document.querySelectorAll('[data-sign-button]').forEach((button) => {
    button.addEventListener('click', async () => {
        const form = document.getElementById(button.dataset.form);
        const typedData = JSON.parse(form.querySelector('[name=typed_data]').value);
        if (!window.ethereum) {
            alert('MetaMask tidak ditemukan.');
            return;
        }
        button.disabled = true;
        try {
            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            const registeredWallet = button.dataset.registeredWallet;
            if (registeredWallet && accounts[0].toLowerCase() !== registeredWallet) {
                alert('Wallet MetaMask aktif tidak sesuai dengan wallet resmi role. Ganti akun MetaMask yang benar.');
                button.disabled = false;
                return;
            }
            const signature = await ethereum.request({
                method: 'eth_signTypedData_v4',
                params: [accounts[0], JSON.stringify(typedData)]
            });
            form.querySelector('[name=wallet_address]').value = accounts[0];
            form.querySelector('[name=signature]').value = signature;
            form.submit();
        } catch (error) {
            alert(error.message || 'Signature dibatalkan.');
            button.disabled = false;
        }
    });
});
</script>
@endpush
@endonce
