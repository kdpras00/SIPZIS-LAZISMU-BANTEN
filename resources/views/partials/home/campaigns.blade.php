
<div class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-12 animate-fadeInUp flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Campaign aktif</h2>
                <p class="text-base text-gray-500">Zakat, infaq, dan sedekah yang sedang berjalan</p>
            </div>
            <a href="{{ route('campaigns.index', 'all') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1 whitespace-nowrap">
                Lihat semua
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        
        <div class="relative">
            
            <button id="campaigns-prev"
                class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                aria-label="Previous campaign">
                <i class="fas fa-chevron-left"></i>
            </button>

            <button id="campaigns-next"
                class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                aria-label="Next campaign">
                <i class="fas fa-chevron-right"></i>
            </button>

            
            <div class="group">
                <div id="campaigns-slider"
                    class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth cursor-grab">
                    @foreach ($activeCampaigns as $campaign)
                        <div class="flex-shrink-0 w-[70vw] md:w-72 snap-start">
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-md h-full flex flex-col">
                                @if ($campaign->photo)
                                    <div class="h-40 overflow-hidden">
                                        @php
                                            $rawImage = trim($campaign->photo ?? '');
                                            // Cek apakah image adalah URL penuh (CDN)
                                            $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                            // Tentukan URL akhir
                                            $imageUrl = $isFullUrl ? $rawImage : asset('storage/' . $campaign->photo);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="Campaign: {{ $campaign->title }}"
                                            class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                @else
                                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4 flex flex-col flex-grow">
                                    
                                    <h3 class="text-base font-bold text-gray-800 mb-1 line-clamp-2 flex-grow-0">
                                        {{ $campaign->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">
                                        {{ Str::limit(strip_tags($campaign->description), 80) }}</p>

                                    
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span>{{ $campaign->donors_count ?? 0 }} donatur</span>
                                        <span class="mx-1">·</span>
                                        @if ($campaign->end_date)
                                            @if ($campaign->remaining_days > 0)
                                                <span>{{ $campaign->remaining_days }} hari lagi</span>
                                            @elseif($campaign->remaining_days == 0)
                                                <span>Hari terakhir</span>
                                            @elseif($campaign->remaining_days == -1)
                                                @if ($campaign->status == 'completed')
                                                    <span>Selesai</span>
                                                @else
                                                    <span>Waktu Habis</span>
                                                @endif
                                            @endif
                                        @else
                                            <span>Tidak ada batas waktu</span>
                                        @endif
                                    </div>

                                    
                                    <div class="mb-3 flex-grow-0 relative">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Terkumpul</span>
                                            <span>{{ 'Rp ' . number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                        </div>
                                        @php
                                            $progress = $campaign->progress_percentage;
                                            if ($progress > 100) {
                                                $progress = 100;
                                            }
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full progress-bar bg-orange-600"
                                                style="width: <?php echo number_format($progress, 0); ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-600 mt-1">
                                            
                                            <span>{{ number_format($campaign->progress_percentage, 1) }}%</span>
                                        </div>

                                    </div>

                                    <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}"
                                        class="block w-full text-center bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold py-2 px-3 rounded-full transition-colors flex-grow-0">
                                        Lihat Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        
    </div>
