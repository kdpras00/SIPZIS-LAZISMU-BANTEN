@extends('layouts.app')

@section('page-title', 'Tambah Berita')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Tambah Berita Baru</h2>
            <p class="text-sm" style="color: #8b7e74;">Isi detail berita yang akan dipublikasikan ke publik</p>
        </div>
        <a href="{{ route('admin.news.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
            style="background: #f0ece6; color: #1c0f0a;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl p-5 sm:p-6 bg-white border border-[#f0ece6]" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                
                <div class="lg:col-span-2 space-y-5">
                    
                    <div>
                        <label for="title" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('title') border-red-500 @enderror"
                               placeholder="Masukkan judul berita..."
                               value="{{ old('title') }}" required>
                        @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="excerpt" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Ringkasan (Opsional)
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3"
                                  class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('excerpt') border-red-500 @enderror"
                                  placeholder="Ringkasan singkat berita (maksimal 500 karakter)...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="content" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Konten Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea id="content" name="content" rows="12"
                                  class="w-full p-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none @error('content') border-red-500 @enderror"
                                  placeholder="Tulis konten berita di sini..." required>{{ old('content') }}</textarea>
                        @error('content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                
                <div class="lg:col-span-1 space-y-5">
                    
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                            Gambar Berita
                        </label>
                        <div class="rounded-xl border border-dashed border-[#e8e0d6] p-4 text-center bg-[#faf8f5]">
                            <i class="bi bi-cloud-arrow-up text-3xl mb-2 block" style="color: #c2410c;"></i>
                            <label for="image" class="cursor-pointer text-xs font-semibold text-[#c2410c] hover:underline block mb-1">
                                Pilih file gambar
                            </label>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*">
                            <p class="text-[11px]" style="color: #8b7e74;">PNG, JPG, GIF hingga 2MB</p>
                        </div>
                        @error('image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div id="image-preview" class="hidden">
                        <label class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">Preview Gambar</label>
                        <img id="preview-img" src="" alt="Preview" class="w-full h-44 object-cover rounded-xl border border-[#f0ece6]">
                    </div>

                    
                    <div class="p-4 rounded-xl border border-[#f0ece6] bg-[#faf8f5]">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" 
                                   class="w-4 h-4 rounded border-gray-300 text-[#c2410c] focus:ring-[#c2410c]"
                                   {{ old('is_published') ? 'checked' : '' }}>
                            <span class="text-xs font-semibold" style="color: #1c0f0a;">Publikasikan berita sekarang</span>
                        </label>
                    </div>
                </div>
            </div>

            
            <div class="flex justify-end gap-3 pt-5 border-t border-[#f0ece6]">
                <a href="{{ route('admin.news.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold transition-colors" style="background: #f0ece6; color: #1c0f0a;">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white transition-colors shadow-xs" style="background: #c2410c;">
                    <i class="bi bi-check-lg text-sm"></i> Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection