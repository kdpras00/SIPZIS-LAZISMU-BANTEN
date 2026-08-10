
<div class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-12 animate-fadeInUp flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Berita terkini</h2>
                    <p class="text-base text-gray-500">Kegiatan zakat dan program sosial Lazismu Banten</p>
                </div>
                <a href="{{ route('berita.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1 whitespace-nowrap">
                    Lihat semua
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            
            <div class="relative">
                
                <button id="news-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Previous news">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button id="news-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Next news">
                    <i class="fas fa-chevron-right"></i>
                </button>

                
                <div class="group">
                    <div id="news-slider"
                        class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth cursor-grab">
                        @foreach (\App\Models\News::published()->latest()->take(10)->get() as $news)
                            <div class="flex-shrink-0 w-72 snap-start">
                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-md">
                                    @if ($news->image)
                                        <div class="h-40 overflow-hidden">
                                            @php
                                                $rawImage = trim($news->image ?? '');
                                                // Cek apakah image adalah URL penuh (CDN)
                                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                // Tentukan URL akhir
                                                $imageUrl = $isFullUrl ? $rawImage : asset('storage/' . $news->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="News: {{ $news->title }}"
                                                class="w-full h-full object-cover" loading="lazy">
                                        </div>
                                    @else
                                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex items-center text-xs text-gray-400 mb-2">
                                            <span>{{ $news->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $news->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $news->excerpt }}</p>
                                        <a href="{{ route('news.show', $news->slug) }}"
                                            class="text-orange-600 hover:text-orange-700 font-medium text-sm flex items-center">
                                            Baca Selengkapnya
                                            <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    
                    <div class="flex justify-center mt-4 space-x-2 news-indicators">
                        
                    </div>
                </div>
            </div>

            
        </div>
    </div>
