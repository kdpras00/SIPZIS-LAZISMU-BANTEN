@extends('layouts.app')

@section('page-title', 'Edit Mustahik')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Edit Mustahik: {{ $mustahik->name }}</h2>
            <p class="text-sm" style="color: #8b7e74;">Mengubah data mustahik (penerima zakat) dalam sistem</p>
        </div>
        <a href="{{ route('mustahik.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <form action="{{ route('mustahik.update', $mustahik) }}" method="POST" id="mustahikForm">
            @csrf
            @method('PUT')

            {{-- Personal Information Section --}}
            <div class="mb-6">
                <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                    <i class="bi bi-person-fill text-sm"></i> Informasi Personal
                </h6>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap (huruf saja)"
                            value="{{ old('name', $mustahik->name) }}" required>
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nik" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            NIK (Wajib Tepat 16 Angka)
                        </label>
                        <input type="text" id="nik" name="nik"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('nik') border-red-500 @enderror"
                            placeholder="16 digit NIK"
                            maxlength="16"
                            value="{{ old('nik', $mustahik->nik) }}">
                        @error('nik')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="phone" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Nomor Telepon (10 - 15 Angka)
                        </label>
                        <input type="text" id="phone" name="phone"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('phone') border-red-500 @enderror"
                            placeholder="08xxxxxxxxxx"
                            maxlength="15"
                            value="{{ old('phone', $mustahik->phone) }}">
                        @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Kategori Mustahik (8 Asnaf) <span class="text-red-500">*</span>
                        </label>
                        <x-custom-select
                            id="category"
                            name="category"
                            placeholder="Pilih Kategori Asnaf"
                            :selected="old('category', $mustahik->category)"
                            :options="[
                                'fakir' => 'Fakir',
                                'miskin' => 'Miskin',
                                'amil' => 'Amil',
                                'muallaf' => 'Muallaf',
                                'riqab' => 'Riqab (Hamba Sahaya)',
                                'gharim' => 'Gharim (Orang Berhutang)',
                                'fisabilillah' => 'Fisabilillah',
                                'ibnu_sabil' => 'Ibnu Sabil (Musafir)'
                            ]"
                        />
                        @error('category')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="gender" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Jenis Kelamin
                        </label>
                        <x-custom-select
                            id="gender"
                            name="gender"
                            placeholder="Pilih Jenis Kelamin"
                            :selected="old('gender', $mustahik->gender)"
                            :options="['male' => 'Laki-laki', 'female' => 'Perempuan']"
                        />
                        @error('gender')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Status Aktif <span class="text-red-500">*</span>
                        </label>
                        <x-custom-select
                            id="is_active"
                            name="is_active"
                            placeholder="Pilih Status"
                            :selected="old('is_active', $mustahik->is_active ? '1' : '0')"
                            :options="['1' => 'Aktif (Berhak Menerima)', '0' => 'Tidak Aktif']"
                        />
                        @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Address & Details Section --}}
            <div class="mb-6 pt-5 border-t border-[#f0ece6]">
                <h6 class="text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #c2410c;">
                    <i class="bi bi-geo-alt-fill text-sm"></i> Alamat & Detail Tambahan
                </h6>

                <div class="mb-4">
                    <label for="address" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                        Alamat Lengkap
                    </label>
                    <textarea id="address" name="address" rows="3"
                        class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('address') border-red-500 @enderror"
                        placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan">{{ old('address', $mustahik->address) }}</textarea>
                    @error('address')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="city" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Kota / Kabupaten
                        </label>
                        <input type="text" id="city" name="city"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('city') border-red-500 @enderror"
                            placeholder="Kota / Kabupaten"
                            value="{{ old('city', $mustahik->city) }}">
                        @error('city')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Catatan Tambahan
                        </label>
                        <input type="text" id="notes" name="notes"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('notes') border-red-500 @enderror"
                            placeholder="Catatan kondisi/keterangan mustahik"
                            value="{{ old('notes', $mustahik->notes) }}">
                        @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-5 border-t border-[#f0ece6]">
                <a href="{{ route('mustahik.index') }}"
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
    const nameInput = document.getElementById('name');
    const nikInput = document.getElementById('nik');
    const phoneInput = document.getElementById('phone');
    const cityInput = document.getElementById('city');
    const form = document.getElementById('mustahikForm');

    // Strict Realtime Blackbox Input Rules
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s\.\'\`-]/g, '');
        });
    }

    if (cityInput) {
        cityInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s\.\'\`-]/g, '');
        });
    }

    if (nikInput) {
        nikInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').slice(0, 16);
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').slice(0, 15);
        });
    }

    // Form Submission SweetAlert Validation
    form.addEventListener('submit', function(e) {
        if (nikInput && nikInput.value.trim() !== '' && nikInput.value.trim().length !== 16) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Format NIK Salah',
                text: 'NIK harus terdiri dari tepat 16 digit angka!',
                confirmButtonColor: '#c2410c'
            });
            nikInput.focus();
            return false;
        }

        if (phoneInput && phoneInput.value.trim() !== '' && phoneInput.value.trim().length < 10) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Nomor Telepon Kurang',
                text: 'Nomor telepon minimal terdiri dari 10 digit angka!',
                confirmButtonColor: '#c2410c'
            });
            phoneInput.focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection
