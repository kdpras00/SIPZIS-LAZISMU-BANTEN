@extends('layouts.main')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-4">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Artikel Lazismu Banten</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Temukan informasi terbaru seputar zakat, infaq, dan sedekah untuk membantu Anda memahami pentingnya kegiatan sosial ini.</p>
            <div class="w-16 h-1 bg-orange-600 rounded-full mx-auto mt-6"></div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @forelse($artikels as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col">
                    <div class="relative h-56 bg-gray-200 flex items-center justify-center">
                        @if ($item->image)
                            @php
                                $rawImage = trim($item->image ?? '');
                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                $imageUrl = $isFullUrl ? $rawImage : Storage::url($rawImage);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span class="font-medium text-orange-600">Artikel</span>
                            <span class="mx-2">•</span>
                            <span>{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-snug">
                            <a href="{{ route('artikel.show', $item->slug) }}" class="hover:text-orange-600 transition-colors">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed">
                            {{ $item->excerpt }}
                        </p>
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $item->author->name ?? 'Admin' }}</span>
                            </div>
                            <a href="{{ route('artikel.show', $item->slug) }}" class="text-orange-600 hover:text-orange-700 font-medium text-sm flex items-center group">
                                Baca 
                                <i class="fas fa-chevron-right ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-xl border border-gray-100">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada artikel</h3>
                    <p class="text-gray-500 mt-1">Artikel akan muncul di sini setelah ditambahkan.</p>
                </div>
            @endforelse
        </div>

        
        @if ($artikels->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $artikels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
