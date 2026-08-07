@extends('layouts.app')

@section('page-title', 'Tambah Distribusi Zakat')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Tambah Distribusi Zakat</h2>
            <p class="text-sm" style="color: #8b7e74;">Catat distribusi zakat kepada mustahik yang berhak menerima</p>
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
                <form action="{{ route('distributions.store') }}" method="POST" id="distributionForm">
                    @csrf

                    {{-- Mustahik Selection Section --}}
                    <div class="mb-6">
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                            <i class="bi bi-person-fill text-sm"></i> Informasi Mustahik
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                            <div class="md:col-span-4">
                                <label for="category_filter" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Filter Kategori
                                </label>
                                @php
                                    $catOptions = [];
                                    foreach ($categories as $cat) {
                                        $catOptions[$cat] = ucfirst(str_replace('_', ' ', $cat));
                                    }
                                @endphp
                                <x-custom-select
                                    id="category_filter"
                                    name="category_filter"
                                    placeholder="Semua Kategori"
                                    :options="$catOptions"
                                />
                            </div>

                            <div class="md:col-span-8">
                                <label for="mustahik_id" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                    Pilih Mustahik <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full h-11 px-4 pr-9 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all appearance-none outline-none cursor-pointer @error('mustahik_id') border-red-500 @enderror"
                                        id="mustahik_id" name="mustahik_id" required>
                                        <option value="">Pilih Mustahik</option>
                                        @foreach ($allMustahik as $m)
                                        <option value="{{ $m->id }}" data-category="{{ $m->category }}"
                                            data-address="{{ $m->address }}" data-phone="{{ $m->phone }}"
                                            {{ old('mustahik_id', $mustahik?->id) == $m->id ? 'selected' : '' }}>
                                            {{ $m->name }} - {{ ucfirst(str_replace('_', ' ', $m->category)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5" style="color: #8b7e74;">
                                        <i class="bi bi-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                @error('mustahik_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Mustahik Details Display --}}
                        <div id="mustahik-details" class="hidden transition-all duration-300">
                            <div class="rounded-xl p-4 border border-[#f0ece6]" style="background: #faf8f5;">
                                <div class="flex items-center mb-3">
                                    <i class="bi bi-info-circle-fill me-2" style="color: #c2410c;"></i>
                                    <span class="text-xs font-bold" style="color: #1c0f0a;">Detail Mustahik Terpilih</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="bg-white rounded-lg p-2.5 border border-[#f0ece6]">
                                        <p class="mb-0.5" style="color: #8b7e74;">Kategori</p>
                                        <p id="mustahik-category" class="font-bold mb-0" style="color: #1c0f0a;"></p>
                                    </div>
                                    <div class="bg-white rounded-lg p-2.5 border border-[#f0ece6]">
                                        <p class="mb-0.5" style="color: #8b7e74;">Telepon</p>
                                        <p id="mustahik-phone" class="font-bold mb-0" style="color: #1c0f0a;"></p>
                                    </div>
                                    <div class="md:col-span-2 bg-white rounded-lg p-2.5 border border-[#f0ece6]">
                                        <p class="mb-0.5" style="color: #8b7e74;">Alamat</p>
                                        <p id="mustahik-address" class="font-bold mb-0" style="color: #1c0f0a;"></p>
                                    </div>
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
                                    :selected="old('distribution_type')"
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
                                        value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                        placeholder="0" data-amount-input required>
                                    <input type="hidden" id="amount_raw" name="amount" value="{{ old('amount') }}">
                                </div>
                                @error('amount')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="amount-warning" class="hidden mt-2 p-2.5 text-xs text-red-800 rounded-xl bg-red-50 border border-red-200">
                                    <i class="bi bi-exclamation-triangle-fill mr-1 text-red-600"></i> Jumlah melebihi saldo tersedia!
                                </div>
                            </div>
                        </div>

                        {{-- Goods Description --}}
                        <div class="mb-4 hidden transition-all duration-300" id="goods-description-field">
                            <label for="goods_description" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                                Deskripsi Barang / Layanan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="goods_description" name="goods_description" rows="3"
                                class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('goods_description') border-red-500 @enderror"
                                placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description') }}</textarea>
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
                                    :value="old('distribution_date', date('Y-m-d'))"
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
                                    value="{{ old('location') }}"
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
                                value="{{ old('program_name') }}"
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
                                placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes') }}</textarea>
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
                            <i class="bi bi-check-lg text-sm"></i> Simpan Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Info Section --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Available Balance Card --}}
            <div class="rounded-2xl p-5 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #8b7e74;">Saldo Tersedia</p>
                <h3 class="text-2xl font-bold text-[#c2410c] mb-1" id="available-balance">
                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                </h3>
                <p class="text-xs mb-0" style="color: #8b7e74;">
                    {{ $availableBalance > 0 ? 'Dapat didistribusikan' : 'Saldo tidak mencukupi' }}
                </p>
            </div>

            {{-- Guidelines Card --}}
            <div class="rounded-2xl p-5 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <h6 class="text-xs font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: #1c0f0a;">
                    <i class="bi bi-info-circle-fill text-sm" style="color: #c2410c;"></i> Panduan Distribusi
                </h6>
                
                <div class="space-y-2 text-xs mb-4" style="color: #8b7e74;">
                    <p class="mb-1 font-semibold text-[#1c0f0a]">Jenis Distribusi:</p>
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-[#c2410c]">•</span>
                        <span><strong class="text-[#1c0f0a]">Tunai:</strong> Bantuan berupa uang tunai</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-[#c2410c]">•</span>
                        <span><strong class="text-[#1c0f0a]">Barang:</strong> Sembako, pakaian, perlengkapan</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-[#c2410c]">•</span>
                        <span><strong class="text-[#1c0f0a]">Voucher:</strong> Kupon belanja/layanan</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-bold text-[#c2410c]">•</span>
                        <span><strong class="text-[#1c0f0a]">Layanan:</strong> Beasiswa, pengobatan, dll.</span>
                    </div>
                </div>

                <div class="p-3 rounded-xl border border-amber-200 bg-amber-50 text-xs text-amber-900">
                    <i class="bi bi-exclamation-triangle-fill me-1 text-amber-600"></i>
                    <strong>Penting:</strong> Pastikan mustahik terverifikasi sebelum penyaluran.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mustahikSelect = document.getElementById('mustahik_id');
        const categoryFilter = document.getElementById('category_filter');
        const distributionType = document.getElementById('distribution_type');
        const amountInput = document.getElementById('amount');
        const goodsDescriptionField = document.getElementById('goods-description-field');
        const goodsDescField = document.getElementById('goods_description');
        const mustahikDetails = document.getElementById('mustahik-details');
        const amountWarning = document.getElementById('amount-warning');
        const availableBalance = {{ $availableBalance ?? 0 }};

        const originalOptions = [];
        Array.from(mustahikSelect.options).slice(1).forEach(option => {
            originalOptions.push({
                value: option.value,
                text: option.text,
                category: option.dataset.category,
                address: option.dataset.address,
                phone: option.dataset.phone,
                selected: option.selected
            });
        });

        let isUpdatingFromMustahikSelection = false;

        function filterMustahikList(selectedCategory, preserveSelectedId = null) {
            mustahikSelect.innerHTML = '<option value="">Pilih Mustahik</option>';

            originalOptions.forEach(optionData => {
                if (!selectedCategory || optionData.category === selectedCategory) {
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    option.dataset.category = optionData.category || '';
                    option.dataset.address = optionData.address || '';
                    option.dataset.phone = optionData.phone || '';
                    
                    if (preserveSelectedId && optionData.value === preserveSelectedId) {
                        option.selected = true;
                    }
                    
                    mustahikSelect.appendChild(option);
                }
            });
        }

        function showMustahikDetails() {
            const selectedOption = mustahikSelect.options[mustahikSelect.selectedIndex];
            if (mustahikSelect.value && selectedOption) {
                const category = selectedOption.dataset.category || '-';
                const address = selectedOption.dataset.address || '-';
                const phone = selectedOption.dataset.phone || '-';

                document.getElementById('mustahik-category').textContent = category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                document.getElementById('mustahik-address').textContent = address;
                document.getElementById('mustahik-phone').textContent = phone;
                mustahikDetails.classList.remove('hidden');

                if (category && categoryFilter && categoryFilter.value !== category && !isUpdatingFromMustahikSelection) {
                    isUpdatingFromMustahikSelection = true;
                    const currentMustahikId = mustahikSelect.value;
                    categoryFilter.value = category;
                    filterMustahikList(category, currentMustahikId);
                    if (mustahikSelect.value !== currentMustahikId) {
                        mustahikSelect.value = currentMustahikId;
                    }
                    isUpdatingFromMustahikSelection = false;
                }
            } else {
                mustahikDetails.classList.add('hidden');
            }
        }

        mustahikSelect.addEventListener('change', showMustahikDetails);

        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                if (isUpdatingFromMustahikSelection) return;
                const selectedCategory = this.value;
                const previouslySelectedMustahikId = mustahikSelect.value;
                filterMustahikList(selectedCategory, previouslySelectedMustahikId);

                if (previouslySelectedMustahikId) {
                    const stillExists = Array.from(mustahikSelect.options).some(opt => opt.value === previouslySelectedMustahikId);
                    if (stillExists) {
                        mustahikSelect.value = previouslySelectedMustahikId;
                        showMustahikDetails();
                    } else {
                        mustahikSelect.value = '';
                        mustahikDetails.classList.add('hidden');
                    }
                } else {
                    mustahikSelect.value = '';
                    mustahikDetails.classList.add('hidden');
                }
            });

            if (categoryFilter.value) {
                filterMustahikList(categoryFilter.value);
                if (mustahikSelect.value) {
                    showMustahikDetails();
                }
            }
        }

        function toggleGoodsField() {
            const typeValue = distributionType.value;
            if (typeValue === 'goods' || typeValue === 'service') {
                goodsDescriptionField.classList.remove('hidden');
                goodsDescField.setAttribute('required', 'required');
            } else {
                goodsDescriptionField.classList.add('hidden');
                goodsDescField.removeAttribute('required');
                goodsDescField.value = '';
            }
        }

        if (distributionType) {
            distributionType.addEventListener('change', function() {
                toggleGoodsField();
                validateAmount();
            });
            if (distributionType.value) {
                toggleGoodsField();
            }
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
            validateAmount();
        }

        if (amountInput) {
            if (amountInput.value) {
                formatNumberWithCommas(amountInput);
            }
            amountInput.addEventListener('input', function() {
                formatNumberWithCommas(this);
            });
        }

        function validateAmount() {
            const rawValue = amountInput.value.replace(/[^\d]/g, '');
            const amount = parseFloat(rawValue) || 0;

            if (distributionType.value === 'cash' && amount > availableBalance) {
                amountWarning.classList.remove('hidden');
                amountInput.classList.add('border-red-500');
            } else {
                amountWarning.classList.add('hidden');
                amountInput.classList.remove('border-red-500');
            }
        }

        document.getElementById('distributionForm').addEventListener('submit', function(e) {
            const rawValue = amountInput.value.replace(/[^\d]/g, '');
            const amount = parseFloat(rawValue) || 0;
            const hiddenInput = document.getElementById('amount_raw');
            if (hiddenInput) {
                hiddenInput.value = rawValue || '0';
            }

            if (distributionType.value === 'cash' && amount > availableBalance) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Saldo Tidak Mencukupi',
                    text: 'Jumlah distribusi tunai melebihi saldo tersedia!',
                    confirmButtonColor: '#c2410c'
                });
                amountInput.focus();
                return;
            }

            if ((distributionType.value === 'goods' || distributionType.value === 'service') && !goodsDescField.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Deskripsi Wajib Diisi',
                    text: 'Deskripsi barang/layanan wajib diisi!',
                    confirmButtonColor: '#c2410c'
                });
                goodsDescField.focus();
                return;
            }
        });

        @if($mustahik)
        mustahikSelect.value = "{{ $mustahik->id }}";
        mustahikSelect.dispatchEvent(new Event('change'));
        @endif
    });
</script>
@endpush