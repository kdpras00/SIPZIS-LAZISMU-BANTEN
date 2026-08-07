@extends('layouts.app')

@section('page-title', 'Edit Distribusi - ' . $distribution->distribution_code)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Edit Distribusi Zakat</h2>
            <p class="text-sm font-mono" style="color: #8b7e74;">{{ $distribution->distribution_code }} - {{ $distribution->mustahik->name }}</p>
        </div>
        <a href="{{ route('distributions.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form Section --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                @if($distribution->is_received)
                <div class="p-3.5 mb-5 rounded-xl border border-amber-200 bg-amber-50 text-xs text-amber-900 flex items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <strong>Perhatian:</strong> Distribusi ini sudah ditandai sebagai diterima pada {{ $distribution->received_date?->format('d F Y H:i') }}.
                        Perubahan data harus dilakukan dengan hati-hati.
                    </div>
                </div>
                @endif

                <form action="{{ route('distributions.update', $distribution) }}" method="POST" id="distributionForm">
                    @csrf
                    @method('PUT')

                    {{-- Mustahik Information --}}
                    <div class="mb-6">
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                            <i class="bi bi-person-fill text-sm"></i> Informasi Mustahik
                        </h6>

                        <div class="rounded-xl p-4 border border-[#f0ece6]" style="background: #faf8f5;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <p class="mb-0.5" style="color: #8b7e74;">Nama Mustahik</p>
                                    <p class="font-bold mb-0 text-sm" style="color: #1c0f0a;">{{ $distribution->mustahik->name }}</p>
                                </div>
                                <div>
                                    <p class="mb-0.5" style="color: #8b7e74;">Kategori Asnaf</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                                        {{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0.5" style="color: #8b7e74;">Telepon</p>
                                    <p class="font-medium mb-0" style="color: #1c0f0a;">{{ $distribution->mustahik->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="mb-0.5" style="color: #8b7e74;">Alamat</p>
                                    <p class="font-medium mb-0" style="color: #1c0f0a;">{{ $distribution->mustahik->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Distribution Details Section --}}
                    <div class="mb-6 pt-5 border-t border-[#f0ece6]">
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                            <i class="bi bi-box-seam-fill text-sm"></i> Detail Distribusi
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="distribution_type" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Jenis Distribusi <span class="text-red-500">*</span>
                                </label>
                                <x-custom-select
                                    id="distribution_type"
                                    name="distribution_type"
                                    placeholder="Pilih Jenis Distribusi"
                                    :selected="old('distribution_type', $distribution->distribution_type)"
                                    :options="['cash' => 'Tunai', 'goods' => 'Barang', 'voucher' => 'Voucher', 'service' => 'Layanan']"
                                />
                                @error('distribution_type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="amount" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Jumlah Nominal <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: #8b7e74;">Rp</span>
                                    <input type="text" id="amount" name="amount_display"
                                        class="w-full h-11 pl-10 pr-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-bold text-[#c2410c] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('amount') border-red-500 @enderror"
                                        value="{{ old('amount', number_format($distribution->amount, 0, ',', '.')) }}"
                                        placeholder="0" data-amount-input required>
                                    <input type="hidden" id="amount_raw" name="amount" value="{{ old('amount', $distribution->amount) }}">
                                </div>
                                @error('amount')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Goods Description --}}
                        <div class="mb-4 {{ in_array($distribution->distribution_type, ['goods', 'service']) ? '' : 'hidden' }} transition-all duration-300" id="goods-description-field">
                            <label for="goods_description" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                Deskripsi Barang / Layanan
                            </label>
                            <textarea id="goods_description" name="goods_description" rows="3"
                                class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('goods_description') border-red-500 @enderror"
                                placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description', $distribution->goods_description) }}</textarea>
                            @error('goods_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="distribution_date" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Tanggal Distribusi <span class="text-red-500">*</span>
                                </label>
                                <x-custom-date-picker
                                    id="distribution_date"
                                    name="distribution_date"
                                    :value="old('distribution_date', $distribution->distribution_date->format('Y-m-d'))"
                                    placeholder="Tanggal Distribusi"
                                />
                                @error('distribution_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Lokasi Penyerahan
                                </label>
                                <input type="text" id="location" name="location"
                                    class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('location') border-red-500 @enderror"
                                    value="{{ old('location', $distribution->location) }}"
                                    placeholder="Contoh: Kantor Lazismu / Alamat Mustahik">
                                @error('location')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Program Information Section --}}
                    <div class="mb-6 pt-5 border-t border-[#f0ece6]">
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                            <i class="bi bi-journal-text text-sm"></i> Program & Catatan
                        </h6>

                        <div class="mb-4">
                            <label for="program_name" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                Nama Program
                            </label>
                            <input type="text" id="program_name" name="program_name"
                                class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('program_name') border-red-500 @enderror"
                                value="{{ old('program_name', $distribution->program_name) }}"
                                placeholder="Contoh: Bantuan Ramadan, Program Pendidikan, dll.">
                            @error('program_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                Catatan
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes', $distribution->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end gap-3 pt-5 border-t border-[#f0ece6]">
                        <a href="{{ route('distributions.index') }}"
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

        {{-- Sidebar Info Section --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Distribution Status Card --}}
            <div class="rounded-2xl p-5 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: #8b7e74;">Status Penyerahan</p>
                @if($distribution->is_received)
                <div class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-orange-100 text-[#c2410c] border border-orange-200">
                    <i class="bi bi-check-circle-fill me-1.5"></i> Sudah Diterima
                </div>
                <p class="text-xs mt-2 mb-0" style="color: #8b7e74;">Diterima tanggal {{ $distribution->received_date?->format('d M Y H:i') }}</p>
                @else
                <div class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                    <i class="bi bi-clock-history me-1.5"></i> Belum Diterima
                </div>
                <p class="text-xs mt-2 mb-0" style="color: #8b7e74;">Menunggu konfirmasi penyerahan ke mustahik</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const distributionType = document.getElementById('distribution_type');
        const amountInput = document.getElementById('amount');
        const goodsDescriptionField = document.getElementById('goods-description-field');
        const goodsDescField = document.getElementById('goods_description');

        function toggleGoodsField() {
            const typeValue = distributionType.value;
            if (typeValue === 'goods' || typeValue === 'service') {
                goodsDescriptionField.classList.remove('hidden');
            } else {
                goodsDescriptionField.classList.add('hidden');
            }
        }

        if (distributionType) {
            distributionType.addEventListener('change', toggleGoodsField);
        }

        function formatNumberWithCommas(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            input.value = value;
            const hiddenInput = document.getElementById('amount_raw');
            if (hiddenInput) {
                hiddenInput.value = input.value.replace(/[^\d]/g, '');
            }
        }

        if (amountInput) {
            amountInput.addEventListener('input', function() {
                formatNumberWithCommas(this);
            });
        }
    });
</script>
@endpush
