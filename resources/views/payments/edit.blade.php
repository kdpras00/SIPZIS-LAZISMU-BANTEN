@extends('layouts.app')

@section('page-title', 'Edit Pembayaran - ' . $payment->payment_code)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Edit Data Pembayaran</h2>
            <p class="text-sm font-mono" style="color: #8b7e74;">{{ $payment->payment_code }} - {{ $payment->muzakki?->name ?? 'Muzakki Umum' }}</p>
        </div>
        <a href="{{ route('payments.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <form action="{{ route('payments.update', $payment) }}" method="POST" id="paymentEditForm">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                    <i class="bi bi-credit-card-fill text-sm"></i> Informasi Transaksi
                </h6>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="muzakki_id" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Muzakki <span class="text-red-500">*</span>
                        </label>
                        @php
                            $muzakkiOptions = [];
                            foreach ($allMuzakki as $m) {
                                $muzakkiOptions[$m->id] = $m->name . ($m->email ? ' (' . $m->email . ')' : '');
                            }
                        @endphp
                        <x-custom-select
                            id="muzakki_id"
                            name="muzakki_id"
                            placeholder="Pilih Muzakki"
                            :selected="old('muzakki_id', $payment->muzakki_id)"
                            :options="$muzakkiOptions"
                        />
                        @error('muzakki_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Tanggal Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <x-custom-date-picker
                            id="payment_date"
                            name="payment_date"
                            :value="old('payment_date', $payment->payment_date->format('Y-m-d'))"
                            placeholder="Pilih Tanggal Pembayaran"
                        />
                        @error('payment_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="paid_amount" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Jumlah Nominal (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: #8b7e74;">Rp</span>
                            <input type="text" id="paid_amount_display"
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-bold text-[#c2410c] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('paid_amount') border-red-500 @enderror"
                                value="{{ old('paid_amount', number_format($payment->paid_amount, 0, ',', '.')) }}"
                                placeholder="0" required>
                            <input type="hidden" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $payment->paid_amount) }}">
                        </div>
                        @error('paid_amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <x-custom-select
                            id="payment_method"
                            name="payment_method"
                            placeholder="Pilih Metode"
                            :selected="old('payment_method', $payment->payment_method)"
                            :options="[
                                'cash' => 'Tunai',
                                'transfer' => 'Transfer Bank',
                                'check' => 'Cek',
                                'online' => 'Online',
                                'bca_va' => 'BCA Virtual Account',
                                'bri_va' => 'BRI Virtual Account',
                                'bni_va' => 'BNI Virtual Account',
                                'mandiri_va' => 'Mandiri Virtual Account',
                                'qris' => 'QRIS'
                            ]"
                        />
                        @error('payment_method')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="payment_reference" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Referensi Pembayaran / No. Rekening
                        </label>
                        <input type="text" id="payment_reference" name="payment_reference"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('payment_reference') border-red-500 @enderror"
                            value="{{ old('payment_reference', $payment->payment_reference) }}"
                            placeholder="Nomor referensi / bukti transaksi">
                        @error('payment_reference')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Status Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <x-custom-select
                            id="status"
                            name="status"
                            placeholder="Pilih Status"
                            :selected="old('status', $payment->status)"
                            :options="['pending' => 'Menunggu Verifikasi', 'completed' => 'Selesai / Terverifikasi', 'cancelled' => 'Dibatalkan']"
                        />
                        @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                        Catatan
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('notes') border-red-500 @enderror"
                        placeholder="Catatan tambahan mengenai pembayaran ini...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-5 border-t border-[#f0ece6]">
                <a href="{{ route('payments.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold transition-colors" style="background: #f0ece6; color: #1c0f0a;">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white transition-colors shadow-xs" style="background: #c2410c;">
                    <i class="bi bi-check-lg text-sm"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountDisplay = document.getElementById('paid_amount_display');
    const amountRaw = document.getElementById('paid_amount');

    if (amountDisplay && amountRaw) {
        amountDisplay.addEventListener('input', function() {
            let val = this.value.replace(/[^\d]/g, '');
            amountRaw.value = val;
            this.value = val ? parseInt(val).toLocaleString('id-ID') : '';
        });
    }
});
</script>
@endpush
@endsection