@extends('layouts.main')

@section('title', $artikel->title . ' - SIPZIS')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 pb-16 pt-8">
    
    <div class="container mx-auto px-4 max-w-7xl">
        
        
        <div class="mb-6">
            <a href="{{ route('artikel.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-orange-600 transition-colors">
                <i class="bi bi-arrow-left-circle mr-2"></i>
                Kembali ke Artikel
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            
            <div class="lg:col-span-2">
                
                
                <div class="mb-8">
                    <span class="inline-block px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold uppercase tracking-wider mb-4">
                        Artikel
                    </span>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                        {{ $artikel->title }}
                    </h1>
                    
                    <div class="flex items-center text-sm text-gray-500 space-x-4 border-b border-gray-200 pb-6">
                        <div class="flex items-center">
                            @php
                                $authorInitial = substr($artikel->author->name ?? 'A', 0, 1);
                            @endphp
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                {{ $authorInitial }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $artikel->author->name ?? 'Admin Lazismu' }}</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center">
                            <i class="bi bi-calendar3 mr-2"></i>
                            {{ $artikel->formatted_date }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center">
                            <i class="bi bi-clock mr-2"></i>
                            {{ ceil(str_word_count(strip_tags($artikel->content)) / 200) }} menit baca
                        </span>
                    </div>
                </div>

                
                <div class="bg-gray-100 rounded-2xl overflow-hidden mb-8 shadow-sm">
                    @if($artikel->image)
                        @php
                            $imageUrl = Str::startsWith($artikel->image, ['http://', 'https://']) ? $artikel->image : Storage::url($artikel->image);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $artikel->title }}" class="w-full h-auto object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center bg-gray-200 text-gray-400">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="px-4 py-2 bg-gray-50 text-xs text-gray-500 italic text-center border-t border-gray-100">
                        {{ $artikel->title }}
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <div class="article-content text-gray-800 leading-loose text-lg text-justify font-light font-sans">
                        @foreach(preg_split('/\r\n|\r|\n/', $artikel->content) as $paragraph)
                            @if(trim($paragraph) !== '')
                                <p class="mb-6">{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>
                    
                    
                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-4">Bagikan Artikel Ini:</h4>
                        <div class="flex gap-3">
                            <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}', '_blank')" class="flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition">
                                <i class="bi bi-facebook mr-2"></i> Facebook
                            </button>
                            <button onclick="window.open('https://api.whatsapp.com/send?text={{ urlencode($artikel->title . ' ' . Request::url()) }}', '_blank')" class="flex items-center px-4 py-2 rounded-lg bg-green-500 text-white text-sm hover:bg-green-600 transition">
                                <i class="bi bi-whatsapp mr-2"></i> WhatsApp
                            </button>
                            <button onclick="window.open('https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode($artikel->title) }}', '_blank')" class="flex items-center px-4 py-2 rounded-lg bg-black text-white text-sm hover:bg-gray-800 transition">
                                <i class="bi bi-twitter-x mr-2"></i> Twitter
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-8">
                    
                    
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="w-1 h-6 bg-orange-600 rounded-full mr-3"></span>
                            Artikel Terbaru
                        </h3>
                        
                        <div class="space-y-6">
                            @php
                                $sidebarArtikel = \App\Models\Artikel::where('id', '!=', $artikel->id)
                                                ->where('is_published', true)
                                                ->latest()
                                                ->take(4)
                                                ->get();
                            @endphp

                            @forelse($sidebarArtikel as $item)
                                <a href="{{ route('artikel.show', $item->slug) }}" class="group flex items-start gap-4">
                                    <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 relative">
                                        @if($item->image)
                                            @php
                                                $sImg = Str::startsWith($item->image, ['http','https']) ? $item->image : Storage::url($item->image);
                                            @endphp
                                            <img src="{{ $sImg }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug mb-1">
                                            {{ $item->title }}
                                        </h4>
                                        <span class="text-xs text-gray-400">
                                            {{ $item->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-gray-500 text-sm py-4">
                                    Belum ada artikel lainnya.
                                </div>
                            @endforelse
                        </div>
                        
                        <a href="{{ route('artikel.index') }}" class="block mt-6 text-center text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                            Lihat Semua Artikel <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>

                    
                    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-2xl p-6 text-white text-center shadow-lg relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        
                        <h3 class="text-xl font-bold mb-2 relative z-10">Dukung Program Kebaikan</h3>
                        <p class="text-orange-100 text-sm mb-6 relative z-10">Salurkan donasi Anda untuk membantu mereka yang membutuhkan.</p>
                        
                        <a href="{{ route('program') }}" class="inline-block w-full py-3 bg-white text-orange-700 font-bold rounded-xl shadow-md hover:bg-orange-50 transition-colors relative z-10">
                            Donasi Sekarang
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Typography Overrides */
    .article-content p {
        margin-bottom: 1.5rem;
        /* Justify handled by class */
    }
</style>
@endsection