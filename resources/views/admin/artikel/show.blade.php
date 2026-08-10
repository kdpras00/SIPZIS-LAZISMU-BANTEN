@extends('layouts.app')

@section('page-title', 'Detail Artikel - ' . $artikel->title)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Artikel</h2>
            <p class="text-sm truncate max-w-[400px]" style="color: #8b7e74;">{{ $artikel->title }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.artikel.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.artikel.edit', $artikel) }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-pencil-fill mr-1.5"></i> Edit Artikel
            </a>
        </div>
    </div>

    
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        
        <div class="relative">
            @if($artikel->image)
            @php
            $rawImage = trim($artikel->image ?? '');
            // Cek apakah image adalah URL penuh (CDN)
            $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
            // Tentukan URL akhir
            $imageUrl = $isFullUrl
            ? $rawImage
            : Storage::url($artikel->image);
            @endphp
            <img src="{{ $imageUrl }}" alt="Article Image" class="w-full h-96 object-cover">
            @else
            <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif

            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                <h1 class="text-3xl font-bold text-white mb-2">{{ $artikel->title }}</h1>
                <div class="flex flex-wrap items-center text-white/90">
                    <span>{{ $artikel->formatted_date }}</span>
                    <span class="mx-3">•</span>
                    <span>Oleh {{ $artikel->author->name }}</span>
                </div>
            </div>
        </div>

        
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($artikel->is_published) bg-orange-100 text-orange-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <i class="fas fa-circle w-2 h-2 mr-2
                            @if($artikel->is_published) text-orange-400
                            @else text-gray-400 @endif"></i>
                        {{ $artikel->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="prose max-w-none">
                {!! $artikel->content !!}
            </div>
        </div>
    </div>

    
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-primary">
            <i class="fas fa-edit mr-2"></i>Edit Artikel
        </a>
    </div>
</div>
@endsection