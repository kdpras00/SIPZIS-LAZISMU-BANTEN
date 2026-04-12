@extends('layouts.main')

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="relative bg-gradient-to-br from-orange-50 via-orange-50 to-orange-100 min-h-screen overflow-hidden" style="background-image: url('{{ asset('img/masjid.webp') }}'); opacity: 0.95; background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-white/60 via-white/40 to-white/60"></div>
        <div class="absolute top-10 left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-bounce animation-delay-1000"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-bounce animation-delay-3000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-bounce animation-delay-5000"></div>
    </div>

    <div class="relative z-10 py-20">
        <div class="container mx-auto px-4 py-16">
            <!-- Page Header -->
            <div class="text-center mb-20 relative">
                <div class="inline-block">
                    <div class="bg-orange-100 backdrop-blur-sm rounded-full px-6 py-2 mb-6">
                        <span class="text-orange-600 text-sm font-medium tracking-wider uppercase">Semua Berita</span>
                    </div>
                </div>
                <h1 class="text-5xl md:text-7xl font-black mb-6 relative">
                    <span class="bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600 bg-clip-text text-transparent drop-shadow-2xl">
                        Berita SIPZ
                    </span>
                    <div class="absolute -top-2 -right-2 w-4 h-4 bg-orange-400 rounded-full animate-pulse"></div>
                </h1>
                <div class="max-w-4xl mx-auto">
                    <p class="text-2xl md:text-3xl font-light text-gray-600 mb-6 leading-relaxed">
                        Update terbaru seputar program zakat, infaq, dan sedekah
                    </p>
                </div>
            </div>

            <!-- News Grid -->
            <div class="relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-10 mb-12 border border-white/20">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-50/50 via-orange-50/50 to-orange-50/50 rounded-3xl"></div>
                <div class="relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($news as $item)
                        <div class="group relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-500 border border-white/20">
                            <div class="relative">
                                @if($item->image)
                                @php
                                $rawImage = trim($item->image ?? '');
                                // Cek apakah image adalah URL penuh (CDN)
                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                // Tentukan URL akhir
                                $imageUrl = $isFullUrl
                                ? $rawImage
                                : Storage::url($item->image);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                @else
                                <img src="https://via.placeholder.com/400x250" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                @endif
                                <div class="absolute top-4 left-4">
                                    @php
                                    $categoryColors = [
                                    'zakat' => 'from-orange-500 to-orange-700',
                                    'infaq' => 'from-blue-500 to-blue-700',
                                    'sedekah' => 'from-purple-500 to-purple-700'
                                    ];
                                    @endphp
                                    <span class="inline-block bg-gradient-to-r {{ $categoryColors[$item->category] ?? 'from-gray-500 to-gray-700' }} text-white text-xs px-4 py-2 rounded-full uppercase font-bold tracking-wide shadow-lg">
                                        {{ $item->category_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-black text-gray-800 mb-3 leading-tight">{{ $item->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $item->excerpt }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500 text-xs">{{ $item->formatted_date }}</span>
                                    <a href="{{ route('news.show', $item->slug) }}" class="text-orange-600 hover:text-orange-800 text-sm font-bold flex items-center group">
                                        Baca Selengkapnya
                                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Berita</h3>
                            <p class="text-gray-500">Berita akan muncul di sini setelah admin menambahkannya.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($news->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $news->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-1000 {
        animation-delay: 1s;
    }

    .animation-delay-3000 {
        animation-delay: 3s;
    }

    .animation-delay-5000 {
        animation-delay: 5s;
    }
</style>
@endsection