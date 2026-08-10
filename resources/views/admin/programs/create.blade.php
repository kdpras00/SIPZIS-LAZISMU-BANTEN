@extends('layouts.app')

@section('page-title', 'Tambah Program')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">

    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Tambah Program Baru</h2>
            <p class="text-sm" style="color: #8b7e74;">Isi detail program yang akan ditambahkan ke sistem</p>
        </div>
        <a href="{{ route('admin.programs.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            
            <div class="lg:col-span-2 space-y-5">
                <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">

                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-semibold mb-1.5" style="color: #1c0f0a;">
                            Nama Program <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-sm font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                            placeholder="Nama program">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div class="mb-5">
                        <label for="description" class="block text-sm font-semibold mb-1.5" style="color: #1c0f0a;">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full p-4 rounded-xl border border-[#e8e0d6] bg-white text-sm font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                            placeholder="Deskripsi singkat program...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label for="category" class="block text-sm font-semibold mb-1.5" style="color: #1c0f0a;">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <x-custom-select 
                                id="category" 
                                name="category" 
                                placeholder="Pilih Kategori" 
                                :selected="old('category', '')" 
                                :options="['zakat'=>'Zakat','infaq'=>'Infaq','shadaqah'=>'Shadaqah','pendidikan'=>'Pendidikan','kesehatan'=>'Kesehatan','ekonomi'=>'Ekonomi','sosial-dakwah'=>'Sosial & Dakwah','kemanusiaan'=>'Kemanusiaan','lingkungan'=>'Lingkungan']" />
                            @error('category')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold mb-1.5" style="color: #1c0f0a;">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <x-custom-select 
                                id="status" 
                                name="status" 
                                placeholder="Pilih Status" 
                                :selected="old('status', 'active')" 
                                :options="['active' => 'Aktif', 'inactive' => 'Tidak Aktif']" />
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    
                    <div class="mb-5">
                        <label for="target_amount" class="block text-sm font-semibold mb-1.5" style="color: #1c0f0a;">Target Dana (Rp)</label>
                        <input type="text" id="target_amount" name="target_amount_display"
                            value="{{ old('target_amount') ? number_format(old('target_amount'), 0, ',', '.') : '' }}"
                            placeholder="0"
                            class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-sm font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none">
                        <input type="hidden" id="target_amount_raw" name="target_amount" value="{{ old('target_amount') }}">
                        @error('target_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.programs.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold transition-colors duration-200"
                        style="background: #f0ece6; color: #1c0f0a;">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-white font-semibold rounded-xl transition-colors duration-200 text-xs shadow-xs"
                        style="background: #c2410c;">
                        <i class="bi bi-check-lg text-sm"></i> Simpan Program
                    </button>
                </div>
            </div>

            
            <div>
                <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                    <p class="text-sm font-semibold mb-3" style="color: #1c0f0a;">Foto Program</p>
                    <div class="rounded-xl overflow-hidden mb-3 border border-[#f0ece6]" style="background: #faf8f5;">
                        <img id="preview"
                            src="{{ asset('img/masjidbanten.png') }}"
                            alt="Preview Foto"
                            class="w-full object-cover"
                            style="height: 200px;">
                    </div>
                    <label for="photo" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-xs font-semibold cursor-pointer transition-colors duration-200"
                        style="background: #f0ece6; color: #1c0f0a;">
                        <i class="bi bi-upload"></i> Pilih Foto
                    </label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                    <p class="mt-2 text-[11px] text-center" style="color: #8b7e74;">JPG, PNG, GIF · Maksimal 2MB</p>
                    @error('photo')
                        <p class="mt-1 text-xs text-center text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('preview').src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    }

    // Format amount input with thousand separators
    const amountInput = document.getElementById('target_amount');
    const hiddenInput = document.getElementById('target_amount_raw');

    function formatAmount(input) {
        if (!input) return;
        const raw = input.value.replace(/[^\d]/g, '');
        input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        if (hiddenInput) hiddenInput.value = raw;
    }

    if (amountInput) {
        if (amountInput.value) formatAmount(amountInput);
        amountInput.addEventListener('input', () => formatAmount(amountInput));

        const form = amountInput.closest('form');
        if (form && hiddenInput) {
            form.addEventListener('submit', function() {
                hiddenInput.value = amountInput.value.replace(/[^\d]/g, '') || '0';
            });
        }
    }
});
</script>
@endpush
