@extends('layouts.app')

@section('page-title', 'Detail Berita - ' . $news->title)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Berita</h2>
            <p class="text-sm truncate max-w-[400px]" style="color: #8b7e74;">{{ $news->title }}</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.news.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
               style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('news.show', $news->slug) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-eye-fill mr-1.5"></i> Lihat Publik
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-3xl shadow-lg shadow-gray-100 overflow-hidden border border-gray-100">
                
                <div class="lg:hidden p-4 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Status Publikasi</span>
                        @if($news->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>

                
                @if($news->image)
                    <div class="relative w-full h-64 md:h-96 bg-gray-100 group overflow-hidden">
                        @php
                            $rawImage = trim($news->image ?? '');
                            $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                            $imageUrl = $isFullUrl ? $rawImage : Storage::url($news->image);
                        @endphp
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $news->title }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                            <h2 class="text-2xl md:text-3xl font-bold leading-tight mb-2 drop-shadow-sm">{{ $news->title }}</h2>
                            <div class="flex items-center gap-4 text-sm md:text-base text-gray-200">
                                <span class="flex items-center gap-1"><i class="bi bi-person-fill"></i> {{ $news->author->name }}</span>
                                <span class="flex items-center gap-1"><i class="bi bi-calendar-fill"></i> {{ $news->formatted_date }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-8 border-b border-gray-100">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $news->title }}</h2>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span class="flex items-center gap-1"><i class="bi bi-person-fill"></i> {{ $news->author->name }}</span>
                            <span class="flex items-center gap-1"><i class="bi bi-calendar-fill"></i> {{ $news->formatted_date }}</span>
                        </div>
                    </div>
                @endif
                
                
                <div class="p-6 md:p-8">
                    @if($news->excerpt)
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 mb-8">
                            <h3 class="text-sm font-semibold text-blue-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="bi bi-info-circle-fill text-blue-500"></i> Ringkasan
                            </h3>
                            <p class="text-blue-800 leading-relaxed">{{ $news->excerpt }}</p>
                        </div>
                    @endif

                    <div class="prose prose-lg max-w-none text-gray-600 prose-headings:text-gray-900 prose-a:text-orange-600 hover:prose-a:text-orange-500 prose-img:rounded-2xl prose-img:shadow-md">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                    
                    @if($news->created_at != $news->updated_at)
                        <div class="mt-8 pt-6 border-t border-gray-100 text-sm text-gray-400 italic flex items-center gap-2">
                            <i class="bi bi-pencil-fill"></i>
                            Diperbarui terakhir pada {{ $news->updated_at->format('d M Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
            
             
             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-search-heart-fill text-purple-500"></i> Metadata & SEO
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid gap-4">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Slug URL</label>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100 group-hover:border-purple-200 transition-colors">
                                <code class="text-sm text-purple-700 font-mono break-all">{{ $news->slug }}</code>
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Link Publik</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-600 text-sm truncate">
                                    {{ route('news.show', $news->slug) }}
                                </div>
                                <button onclick="copyToClipboard()" class="p-3 text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded-xl border border-gray-100 transition-all" title="Salin Link">
                                    <i class="bi bi-clipboard-fill"></i>
                                </button>
                                <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="p-3 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl border border-gray-100 transition-all" title="Buka Link">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl shadow-lg shadow-gray-100 border border-gray-100 overflow-hidden">
                <div class="p-6 text-center border-b border-gray-100 {{ $news->is_published ? 'bg-orange-50/50' : 'bg-amber-50/50' }}">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $news->is_published ? 'bg-orange-100 text-orange-600' : 'bg-amber-100 text-amber-600' }} mb-4">
                        <i class="bi {{ $news->is_published ? 'bi-check-circle-fill' : 'bi-clock-fill' }} text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold {{ $news->is_published ? 'text-orange-900' : 'text-amber-900' }} mb-1">
                        {{ $news->is_published ? 'Terpublikasi' : 'Draft' }}
                    </h3>
                    <p class="{{ $news->is_published ? 'text-orange-600' : 'text-amber-600' }} text-sm">
                        {{ $news->is_published ? 'Berita ini dapat diakses publik' : 'Berita belum dipublikasikan' }}
                    </p>
                </div>
                
                <div class="p-6 space-y-3">
                    <form action="{{ route('admin.news.toggle-publish', $news) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if($news->is_published)
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-white border-2 border-amber-100 text-amber-700 font-semibold hover:bg-amber-50 hover:border-amber-200 transition-all flex items-center justify-center gap-2 group">
                                <i class="bi bi-eye-slash-fill group-hover:scale-110 transition-transform"></i>
                                Batalkan Publikasi
                            </button>
                        @else
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold shadow-lg shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-rocket-takeoff-fill"></i>
                                Publikasikan Sekarang
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">Statistik</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <span class="text-gray-500 text-sm flex items-center gap-2"><i class="bi bi-fonts"></i> Kata</span>
                        <span class="font-mono font-medium text-gray-900">{{ str_word_count(strip_tags($news->content)) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <span class="text-gray-500 text-sm flex items-center gap-2"><i class="bi bi-keyboard"></i> Karakter</span>
                        <span class="font-mono font-medium text-gray-900">{{ strlen(strip_tags($news->content)) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <span class="text-gray-500 text-sm flex items-center gap-2"><i class="bi bi-eye-fill"></i> Dilihat</span>
                        <span class="font-mono font-medium text-gray-900">-</span> 
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.news.edit', $news) }}" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-gray-900 text-white font-medium hover:bg-gray-800 transition-all shadow-md shadow-gray-200">
                        <i class="bi bi-pencil-fill mr-2"></i> Edit Berita
                    </a>
                    
                    <a href="{{ route('admin.news.create') }}" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        <i class="bi bi-plus-circle-fill mr-2"></i> Buat Baru
                    </a>
                    
                    <hr class="border-gray-100 my-2">
                    
                    <form action="{{ route('admin.news.destroy', $news) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-red-50 text-red-600 font-medium border border-transparent hover:bg-red-100 hover:border-red-200 transition-all">
                            <i class="bi bi-trash-fill mr-2"></i> Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
            
            
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 text-xs text-gray-500 space-y-2">
                <div class="flex justify-between">
                    <span>Dibuat:</span>
                    <span class="font-medium">{{ $news->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Oleh:</span>
                    <span class="font-medium">{{ $news->author->name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom override for prose if needed */
    .prose blockquote {
        font-style: normal;
        border-left-color: #ea580c;
        background-color: #f0fdf4;
        padding: 1rem;
        border-radius: 0 0.5rem 0.5rem 0;
        color: #065f46;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard() {
        const url = "{{ route('news.show', $news->slug) }}";
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disalin!',
                text: 'Link berita publik telah disalin ke clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menyalin link. Silakan salin manual.',
            });
        });
    }
</script>
@endpush