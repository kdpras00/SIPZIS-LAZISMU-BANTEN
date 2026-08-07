@extends('layouts.app')

@section('page-title', 'Tambah Akun Bank - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.bank-accounts') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left-circle text-xl"></i>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-gray-900 mb-1">Tambah Akun Bank</h5>
            <p class="text-sm text-gray-600 mb-0">Simpan informasi rekening bank Anda untuk transaksi zakat</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 border border-[#f0ece6]">
        <form method="POST" action="{{ route('dashboard.bank-accounts.store') }}" id="bankAccountForm" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                    class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                    placeholder="Contoh: Bank Syariah Indonesia (BSI)" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Nomor Rekening (Angka Saja) <span class="text-red-500">*</span></label>
                <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}"
                    class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                    placeholder="Masukkan nomor rekening (angka saja)" maxlength="20" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#1c0f0a] mb-1.5">Atas Nama (Pemilik Rekening) <span class="text-red-500">*</span></label>
                <input type="text" id="account_holder" name="account_holder" value="{{ old('account_holder') }}"
                    class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                    placeholder="Nama pemilik rekening (huruf saja)" required>
            </div>
            <p class="text-xs text-[#8b7e74]">Informasi rekening disimpan aman dan hanya digunakan untuk mempermudah transaksi zakat Anda.</p>
            <div class="flex items-center justify-between pt-4 border-t border-[#f0ece6]">
                <a href="{{ route('dashboard.bank-accounts') }}" class="px-5 py-2.5 text-xs font-semibold text-[#1c0f0a] bg-[#f0ece6] rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-[#c2410c] rounded-xl transition-colors shadow-xs">Simpan Akun Bank</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountNumberInput = document.getElementById('account_number');
    const accountHolderInput = document.getElementById('account_holder');

    if (accountNumberInput) {
        accountNumberInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });
    }

    if (accountHolderInput) {
        accountHolderInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s\.\'\`-]/g, '');
        });
    }
});
</script>
@endpush
@endsection
