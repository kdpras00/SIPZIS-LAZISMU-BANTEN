@php
    $activeCampaigns = \App\Models\Campaign::active()->withSum('zakatPayments', 'paid_amount')->latest()->take(10)->get();
    $activePrograms = \App\Models\Program::active()->withSum('zakatPayments', 'paid_amount')->latest()->take(10)->get();    
    $heroSlides = $activeCampaigns->concat($activePrograms)->sortByDesc('created_at')->take(15);
@endphp

<div class="relative w-full h-[85vh] md:h-screen bg-gray-50 overflow-hidden" id="beranda">
    <!-- Slider Container -->
    <div id="hero-slider" class="absolute inset-0 w-full h-full cursor-grab">

        <!-- Slide 1: Quran Quote (Static) -->
        <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out z-10 opacity-100" data-slide="0">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/masjidbanten.png') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent md:hidden"></div>
            <div class="relative container h-full mx-auto px-6 md:px-12 flex flex-col justify-center items-start z-10 pt-20">
                @if(Auth::check() && !Auth::user()->two_factor_enabled)
                <div class="mb-6 w-full"></div>
                @endif
                <div class="max-w-2xl">
                    <div class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold tracking-wider uppercase rounded-full mb-6">
                        QS. Al-Baqarah: 43
                    </div>
                    <h1 class="font-bold leading-[1.1] tracking-tight mb-6">
                        <span class="block text-3xl md:text-4xl text-gray-700 mb-2">"Dan laksanakanlah salat,</span>
                        <span class="block text-4xl md:text-6xl text-orange-600 font-black">tunaikanlah zakat."</span>
                    </h1>
                    <p class="text-base md:text-lg text-gray-600 mb-8 leading-relaxed max-w-lg">
                        Tunaikan zakat Anda dengan mudah, transparan, dan sesuai syariat Islam bersama Lazismu Banten.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('calculator.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-7 rounded-full transition-colors text-sm">
                            Kalkulator Zakat
                        </a>
                        <a href="{{ route('program') }}" class="bg-white hover:bg-orange-50 border border-orange-200 text-orange-600 font-semibold py-3 px-7 rounded-full transition-colors text-sm">
                            Mulai Berdonasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Slides (Campaigns & Programs) -->
        @php $slideIndex = 1; @endphp
        @foreach($heroSlides as $item)
        <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0 bg-gray-50" data-slide="{{ $slideIndex++ }}">
            @php
                $isCampaign = $item instanceof \App\Models\Campaign;
                
                // Determine Image
                if ($isCampaign) {
                    $rawImage = trim($item->photo ?? '');
                    $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                    $imageUrl = $isFullUrl ? $rawImage : asset('storage/' . $item->photo);
                    if (!$item->photo) $imageUrl = asset('img/masjidbanten.png');
                } else {
                    $imageUrl = $item->image_url; 
                }

                $title = $isCampaign ? $item->title : $item->name;
                $category = $isCampaign ? ($item->program_category ?? 'Program Unggulan') : ($item->category ?? 'Program Lazismu');
                
                $link = $isCampaign 
                    ? route('campaigns.show', [$item->program_category, $item]) 
                    : route('program.show', $item->slug);

                $collected = $isCampaign ? $item->collected_amount : $item->total_collected;
                $percentage = $item->progress_percentage;
            @endphp

            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $imageUrl }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent md:hidden"></div>

            <div class="relative container h-full mx-auto px-6 md:px-12 flex items-center z-10">
                <div class="max-w-xl lg:max-w-2xl pt-20 md:pt-0">
                    <div class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold tracking-wider uppercase rounded-full mb-5">
                        {{ $category }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black mb-4 leading-tight text-gray-900 capitalize" style="text-wrap: balance;">
                        {{ \Illuminate\Support\Str::limit($title, 60) }}
                    </h1>
                    <p class="text-base text-gray-600 mb-6 line-clamp-2 leading-relaxed hidden md:block">
                        {{ Str::limit(strip_tags($item->description), 160) }}
                    </p>
                    <div class="mb-5">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Dana Terkumpul</p>
                        <span class="text-2xl md:text-3xl font-black text-gray-900 leading-none">Rp {{ number_format($collected, 0, ',', '.') }}</span>
                        <div class="w-full max-w-sm mt-3 relative">
                            <span class="absolute -top-5 right-0 text-xs font-bold text-orange-600">{{ number_format($percentage, 0) }}%</span>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-full rounded-full transition-all duration-700" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $link }}" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-7 rounded-full transition-colors text-sm">
                            Donasi Sekarang
                        </a>
                        <a href="{{ $link }}" class="bg-white hover:bg-orange-50 border border-orange-200 text-orange-600 font-semibold py-3 px-7 rounded-full transition-colors text-sm">
                            Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <!-- Navigation Arrows Removed Per Request -->

    <!-- Pagination Dots -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20 flex space-x-3 p-3 rounded-full backdrop-blur-sm bg-white/50 border border-gray-200 shadow-sm" id="hero-dots">
        <!-- Dots will be generated by JS -->
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('hero-slider');
        const prevBtn = document.getElementById('hero-prev');
        const nextBtn = document.getElementById('hero-next');
        const dotsContainer = document.getElementById('hero-dots');
        const scrollIndicator = document.getElementById('scroll-indicator');
        
        // Get all slides by data attribute
        const slides = slider.querySelectorAll('[data-slide]');
        const totalSlides = slides.length;
        let currentSlide = 0;
        let autoSlideInterval;

        // Create dots
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `w-3 h-3 rounded-full transition-all duration-300 ${i === 0 ? 'bg-orange-600 w-8' : 'bg-gray-400 hover:bg-gray-600'}`;
            dot.ariaLabel = `Go to slide ${i + 1}`;
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }

        const dots = dotsContainer.children;

        function updateSlider() {
            // Update slides opacity
            slides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.remove('opacity-0', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                }
            });
            
            // Update dots
            Array.from(dots).forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.className = 'w-8 h-3 rounded-full bg-orange-600 transition-all duration-300';
                } else {
                    dot.className = 'w-3 h-3 rounded-full bg-gray-400 hover:bg-gray-600 transition-all duration-300';
                }
            });

            // Toggle scroll indicator (only show on first slide)
            if (scrollIndicator) {
                scrollIndicator.style.opacity = currentSlide === 0 ? '1' : '0';
            }
        }

        function goToSlide(index) {
            currentSlide = index;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            if (currentSlide >= totalSlides) currentSlide = 0;
            updateSlider();
            resetAutoSlide();
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 6000); // 6 seconds per slide
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Event Listeners
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        // Touch & Mouse support for swipe
        let isDragging = false;
        let startX = 0;

        const handleStart = (e) => {
            isDragging = true;
            startX = e.type.includes('touch') ? e.touches[0].screenX : e.screenX;
        };

        const handleEnd = (e) => {
            if (!isDragging) return;
            const endX = e.type.includes('touch') ? e.changedTouches[0].screenX : e.screenX;
            const diff = startX - endX;
            
            if (Math.abs(diff) > 50) {
                if (diff > 0) nextSlide();
                else prevSlide();
            }
            isDragging = false;
        };

        const handleMove = (e) => {
            if (!isDragging) return;
            // Optional: visual feedback during drag
        };

        slider.addEventListener('touchstart', handleStart, {passive: true});
        slider.addEventListener('touchend', handleEnd, {passive: true});
        slider.addEventListener('mousedown', handleStart);
        window.addEventListener('mouseup', handleEnd); // Use window for more reliable mouseup
        
        // Prevent default drag behavior on images/links if dragging
        slider.addEventListener('dragstart', (e) => {
            if (isDragging) e.preventDefault();
        });

        // Initialize
        startAutoSlide();
    });
</script>
@endpush

<!-- Campaigns Terbaru Section -->
<div class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-12 animate-fadeInUp flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Campaign aktif</h2>
                <p class="text-base text-gray-500">Zakat, infaq, dan sedekah yang sedang berjalan</p>
            </div>
            <a href="{{ route('campaigns.index', 'all') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1 whitespace-nowrap">
                Lihat semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Slider Container -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <button id="campaigns-prev"
                class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                aria-label="Previous campaign">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button id="campaigns-next"
                class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                aria-label="Next campaign">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Slider Wrapper -->
            <div class="group">
                <div id="campaigns-slider"
                    class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth cursor-grab">
                    @foreach ($activeCampaigns as $campaign)
                        <div class="flex-shrink-0 w-72 snap-start">
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
                                    <div class="mb-2">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700">
                                            {{ $campaign->program_category ?? 'Zakat' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-800 mb-1 line-clamp-2 flex-grow-0">
                                        {{ $campaign->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">
                                        {{ Str::limit(strip_tags($campaign->description), 80) }}</p>

                                    <!-- Donor count and time remaining -->
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

                                    <!-- Progress Bar -->
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
                                            {{-- <span>Target:
                                                {{ 'Rp ' . number_format($campaign->target_amount, 0, ',', '.') }}</span> --}}
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

                <!-- Slider Indicators -->
                <div class="flex justify-center mt-4 space-x-2 campaigns-indicators">
                    <!-- Indicators will be populated by JavaScript -->
                </div>
            </div>
        </div>

        {{-- "Lihat Semua Campaign" moved to section header --}}
    </div>


<!-- Berita Terbaru Section -->
<div class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-12 animate-fadeInUp flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Berita terkini</h2>
                    <p class="text-base text-gray-500">Kegiatan zakat dan program sosial Lazismu Banten</p>
                </div>
                <a href="{{ route('berita.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1 whitespace-nowrap">
                    Lihat semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Slider Container -->
            <div class="relative">
                <!-- Navigation Buttons -->
                <button id="news-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Previous news">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <button id="news-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Next news">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>

                <!-- Slider Wrapper -->
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
                                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 mb-2">Berita</span>
                                        <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $news->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $news->excerpt }}</p>
                                        <a href="{{ route('news.show', $news->slug) }}"
                                            class="text-orange-600 hover:text-orange-700 font-medium text-sm flex items-center">
                                            Baca Selengkapnya
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Indicators -->
                    <div class="flex justify-center mt-4 space-x-2 news-indicators">
                        <!-- Indicators will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            {{-- "Lihat Semua Berita" moved to section header --}}
        </div>
    </div>

<!-- Artikel Terbaru Section -->
<div class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-12 animate-fadeInUp flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Bacaan pilihan</h2>
                    <p class="text-base text-gray-500">Edukasi zakat, infaq, dan sedekah untuk Anda</p>
                </div>
                <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1 whitespace-nowrap">
                    Lihat semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Slider Container -->
            <div class="relative">
                <!-- Navigation Buttons -->
                <button id="artikel-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Previous article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <button id="artikel-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-orange-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    aria-label="Next article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>

                <!-- Slider Wrapper -->
                <div class="group">
                    <div id="artikel-slider"
                        class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth cursor-grab">
                        @foreach (\App\Models\Artikel::published()->latest()->take(10)->get() as $artikel)
                            <div class="flex-shrink-0 w-72 snap-start">
                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-md">
                                    @if ($artikel->image)
                                        <div class="h-40 overflow-hidden">
                                            @php
                                                $rawImage = trim($artikel->image ?? '');
                                                // Cek apakah image adalah URL penuh (CDN)
                                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                // Tentukan URL akhir
                                                $imageUrl = $isFullUrl
                                                    ? $rawImage
                                                    : asset('storage/' . $artikel->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Article: {{ $artikel->title }}"
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
                                            <span>{{ $artikel->created_at->format('d M Y') }}</span>
                                        </div>
                                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 mb-2">Artikel</span>
                                        <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $artikel->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $artikel->excerpt }}</p>
                                        <a href="{{ route('artikel.show', $artikel->slug) }}"
                                            class="text-orange-600 hover:text-orange-700 font-medium text-sm flex items-center">
                                            Baca Selengkapnya
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Indicators -->
                    <div class="flex justify-center mt-4 space-x-2 artikel-indicators">
                        <!-- Indicators will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            {{-- "Lihat Semua Artikel" moved to section header --}}
        </div>
    </div>

    <!-- CHATBOT FLOATING BUTTON + POPUP -->
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">

        <!-- Popup Chat (Awalnya disembunyikan) -->
        <div id="chatbot-popup"
            class="hidden flex-col bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-80 max-h-[500px] border border-orange-200 overflow-hidden">
            <div class="bg-orange-600 text-white p-3 font-bold text-center">
                Lazismu Banten
            </div>
            <div id="chat-messages"
                class="flex-1 p-3 overflow-y-auto flex flex-col text-sm text-gray-800 chat-messages-container">
                <div class="text-center text-gray-400 text-xs animate-fadeInUp">Mulai percakapan...</div>
            </div>
        <div class="p-3 border-t border-gray-200">
                <div class="flex items-end gap-2">
                    <textarea id="chat-input"
                        placeholder="Ketik pesan..."
                        rows="1"
                        class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none overflow-hidden leading-relaxed"
                        style="max-height: 120px;"></textarea>
                    <button id="send-btn"
                        class="flex-shrink-0 bg-orange-600 text-white w-9 h-9 rounded-xl hover:bg-orange-700 transition-colors flex items-center justify-center"
                        aria-label="Kirim pesan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 pl-0.5">Enter untuk kirim · Shift+Enter baris baru</p>
            </div>
        </div>

        <!-- Tombol Action Container -->
        <div class="flex items-center gap-3">
            <!-- WhatsApp Button -->
            <a href="https://api.whatsapp.com/send/?phone=628561626222&text=Assalamu%E2%80%99alaikum+Warahmatullahi+Wabarakatuh%2C+hallo+tim+Lazismu+%5Bwebsite%5D&type=phone_number&app_absent=0" 
               target="_blank" 
               class="bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-lg transition transform hover:scale-110 flex items-center justify-center w-14 h-14"
               aria-label="Chat WhatsApp">
                <i class="fab fa-whatsapp text-2xl"></i>
            </a>

            <!-- Tombol Chat -->
            <button id="chatbot-button"
                class="bg-orange-600 hover:bg-orange-700 text-white rounded-full p-4 shadow-lg transition-colors flex items-center justify-center w-14 h-14"
                aria-label="Buka Chatbot">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Marked.js untuk format markdown di chatbot -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Custom CSS for Animations -->
    <style>
        /* Prevent FOUC (Flash of Unstyled Content) */
        body:not(.page-loaded) .animate-fadeInUp,
        body:not(.page-loaded) .animate-fadeInDown {
            opacity: 0;
        }

        .animate-fadeInUp,
        .animate-fadeInDown {
            animation-fill-mode: both;
        }

        /* Only animate when page is loaded */
        body.page-loaded .animate-fadeInUp,
        body.page-loaded .animate-fadeInDown {
            animation-play-state: running;
            visibility: visible;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.3s ease-out forwards;
            animation-play-state: paused;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.3s ease-out forwards;
            animation-play-state: paused;
        }

        /* Delay classes with animation-fill-mode */
        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        /* Optimize animations */
        .animate-fadeInUp,
        .animate-fadeInDown {
            will-change: opacity, transform;
        }

        /* Drag interactions */
        .cursor-grab {
            cursor: grab;
        }

        .cursor-grabbing {
            cursor: grabbing !important;
        }

        /* Respect user's motion preferences */
        @media (prefers-reduced-motion: reduce) {

            .animate-fadeInUp,
            .animate-fadeInDown {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }

        /* Gradient text effect */
        .bg-clip-text {
            background-clip: text;
            -webkit-background-clip: text;
        }

        /* Glass morphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Floating chat icon */
        .fixed.bottom-6.right-6 {
            right: 1.5rem !important;
            bottom: 1.5rem !important;
        }

        #chatbot-popup {
            width: 320px !important;
            max-width: 90vw;
        }

        .chat-messages-container {
            max-height: 350px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for chat */
        .chat-messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }

        /* Chat message bubbles */
        .message-user {
            align-self: flex-end;
            background-color: #ea580c;
            color: white;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-left-radius: 18px;
            border-top-right-radius: 4px;
        }

        .message-bot {
            align-self: flex-start;
            background-color: #F3F4F6;
            color: #1F2937;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-right-radius: 18px;
            border-top-left-radius: 4px;
        }

        .message-bubble {
            max-width: 85%;
            padding: 10px 14px;
            margin-bottom: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.3s ease-out forwards;
        }

        /* Add bounce animation for arrows */
        @keyframes bounce-x {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(4px);
            }
        }

        .animate-bounce-x {
            animation: bounce-x 1s infinite;
        }

        /* Enhanced card hover effect */
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 0.6s ease-in-out;
        }

        /* Scroll snap alignment for better mobile experience */
        .snap-start {
            scroll-snap-align: center;
        }

        /* Hide scrollbar for sliders but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 767px) {
            .group:hover .opacity-0 {
                opacity: 0 !important;
            }
        }
    </style>

    <!-- JavaScript for Chatbot Functionality -->
    <script>
        // Chatbot functionality
        document.addEventListener("DOMContentLoaded", () => {
            // Initialize chatbot directly (no need to wait for Puter.js)
            function initializeChatbot() {
                const sendBtn = document.getElementById("send-btn");
                const input = document.getElementById("chat-input");
                const messages = document.getElementById("chat-messages");
                const chatbotBtn = document.getElementById('chatbot-button');
                const chatbotPopup = document.getElementById('chatbot-popup');

                // If any required element is missing, exit
                if (!sendBtn || !input || !messages || !chatbotBtn || !chatbotPopup) {
                    console.error('Chatbot elements not found');
                    return;
                }

                let isFirstOpen = true;

                // Improved scroll to bottom function with delay for better rendering
                function scrollToBottom() {
                    setTimeout(() => {
                        messages.scrollTop = messages.scrollHeight;
                    }, 100);
                }

                chatbotBtn.addEventListener('click', () => {
                    // Toggle tampilan chatbot (muncul/sembunyi)
                    const isHidden = chatbotPopup.classList.toggle('hidden');

                    if (!isHidden) {
                        // Chatbot baru dibuka
                        if (isFirstOpen) {
                            messages.innerHTML = '';
                            appendMessage(
                                `
                <p>Selamat datang di <strong>Lazismu Banten</strong>! 👋</p>
                <p>Saya siap membantu Anda dengan pertanyaan seputar <em>zakat</em>, cara pembayaran, program yang tersedia, dan informasi lainnya.</p>
                <p>Apa yang ingin Anda tanyakan hari ini?</p>
                `,
                                "bot"
                            );
                            isFirstOpen = false;
                        }

                        // Fokuskan ke input setelah chatbot muncul
                        setTimeout(() => input.focus(), 500);

                        // ❌ Tidak scroll otomatis ke bawah
                        // Jadi pesan selamat datang tetap kelihatan penuh
                    } else {
                        // Chatbot ditutup → bisa tambahkan efek jika mau
                        input.blur(); // opsional, supaya keyboard tertutup di mobile
                    }
                });


                // Function to append messages in HTML format
                function appendMessage(htmlContent, sender) {
                    const div = document.createElement("div");
                    div.classList.add("message-bubble", "animate-fadeInUp");

                    if (sender === "user") {
                        div.classList.add("message-user");
                    } else {
                        div.classList.add("message-bot");
                    }

                    // Set the HTML content properly
                    div.innerHTML = htmlContent;
                    messages.appendChild(div);

                    // Auto-scroll to bottom with improved behavior
                    scrollToBottom();
                }

                // Format response to HTML
                function formatResponseToHtml(text) {
                    // Validasi input
                    if (!text || typeof text !== 'string') {
                        return '<p>⚠️ Respon tidak valid dari AI. Silakan coba lagi.</p>';
                    }

                    try {
                        // Gunakan Marked.js untuk render Markdown jadi HTML
                        let html = marked.parse(text);

                        // Tambahkan styling agar tetap terlihat rapi
                        html = html.replace(/<h1>/g, '<h1 class="text-lg font-bold mb-2">')
                            .replace(/<h2>/g, '<h2 class="text-base font-semibold mb-1">')
                            .replace(/<ul>/g, '<ul class="list-disc pl-5 space-y-1">')
                            .replace(/<p>/g, '<p class="mb-2 leading-relaxed">');
                        return html;
                    } catch (error) {
                        console.error('Error parsing markdown:', error);
                        // Fallback: return as plain text with escaping
                        return '<p class="mb-2 leading-relaxed">' + text.replace(/</g, '&lt;').replace(/>/g,
                            '&gt;') + '</p>';
                    }
                }



                // Send message handler
                async function sendMessage() {
                    const userText = input.value.trim();
                    if (!userText) return;

                    // Display user message
                    appendMessage(`<p>${userText.replace(/\n/g, '<br>')}</p>`, "user");
                    input.value = "";
                    // Reset textarea height
                    input.style.height = 'auto';
                    input.style.height = input.scrollHeight + 'px';

                    // Show typing indicator
                    const loadingMsg = document.createElement("div");
                    loadingMsg.id = "typing-indicator";
                    loadingMsg.classList.add("message-bubble", "message-bot");
                    loadingMsg.innerHTML = '<p class="text-gray-400 italic text-xs">Mengetik...</p>';
                    messages.appendChild(loadingMsg);

                    // Scroll to show typing indicator
                    scrollToBottom();

                    try {
                        // Check if we should use custom responses for common zakat questions
                        let replyHtml = '';

                        if (userText.toLowerCase().includes('zakat') && userText.toLowerCase().includes(
                                'apa')) {
                            replyHtml = `
                            <p class="mb-2">Zakat adalah rukun Islam kelima yang wajib dilaksanakan oleh setiap Muslim yang memenuhi syarat.</p>
                            <p class="mb-2">Zakat berasal dari bahasa Arab yang berarti "bersih" atau "tumbuh". Zakat merupakan bentuk ibadah sekaligus sistem ekonomi dalam Islam yang bertujuan untuk membersihkan harta dan menyejahterakan umat.</p>
                            <p class="mb-2"><strong>Syarat wajib zakat:</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Muslim</li>
                                <li>Baligh (dewasa)</li>
                                <li>Merdeka (bukan budak)</li>
                                <li>Kaya (melebihi nisab)</li>
                                <li>Memiliki harta selama satu tahun (haul)</li>
                            </ul>
                        `;
                        } else if (userText.toLowerCase().includes('bayar') || userText.toLowerCase().includes(
                                'cara')) {
                            replyHtml = `
                            <p class="mb-2">Untuk membayar zakat melalui platform kami, Anda dapat mengikuti langkah-langkah berikut:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Klik tombol "BAYAR ZAKAT SEKARANG" di halaman utama</li>
                                <li>Pilih jenis zakat yang ingin Anda bayarkan</li>
                                <li>Isi formulir dengan data diri dan nominal zakat</li>
                                <li>Pilih metode pembayaran yang tersedia</li>
                                <li>Konfirmasi pembayaran dan simpan bukti transfer</li>
                            </ul>
                            <p class="mt-2">Pembayaran zakat bisa dilakukan kapan saja sepanjang tahun. Namun, banyak umat Muslim yang memilih membayarnya saat bulan Ramadhan karena keutamaannya.</p>
                        `;
                        } else if (userText.toLowerCase().includes('jenis') && (userText.toLowerCase().includes(
                                'zakat') || userText.toLowerCase().includes('macam'))) {
                            replyHtml = `
                            <p class="mb-2">Ada beberapa jenis zakat yang wajib dan sunnah dibayarkan:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Zakat Mal</strong> - Zakat atas harta yang dimiliki</li>
                                <li><strong>Zakat Fitrah</strong> - Zakat yang wajib dibayar saat Ramadhan</li>
                                <li><strong>Zakat Profesi</strong> - Zakat atas penghasilan/profesi</li>
                                <li><strong>Zakat Emas/Perak</strong> - Zakat atas kepemilikan logam mulia</li>
                                <li><strong>Zakat Perniagaan</strong> - Zakat atas aset perdagangan</li>
                                <li><strong>Zakat Pertanian</strong> - Zakat atas hasil pertanian</li>
                                <li><strong>Zakat Peternakan</strong> - Zakat atas hewan ternak</li>
                            </ul>
                            <p class="mt-2">Untuk memudahkan perhitungan, Anda dapat menggunakan Kalkulator Zakat yang tersedia di platform kami.</p>
                        `;
                        } else if (userText.toLowerCase().includes('manfaat') || userText.toLowerCase()
                            .includes('guna')) {
                            replyHtml = `
                            <p class="mb-2">Zakat memiliki manfaat besar bagi kedua belah pihak:</p>
                            <p class="mb-2"><strong>Bagi Muzakki (Pembayar Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Membersihkan harta dari kotoran dan sifat kikir</li>
                                <li>Mendapatkan pahala dan ridha Allah SWT</li>
                                <li>Melatih sikap peduli terhadap sesama</li>
                                <li>Mendapat perlindungan dari bencana dan musibah</li>
                            </ul>
                            <p class="mt-2"><strong>Bagi Mustahik (Penerima Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Memenuhi kebutuhan dasar hidup</li>
                                <li>Meningkatkan taraf hidup dan kesejahteraan</li>
                                <li>Mendapat kesempatan untuk berkembang secara ekonomi</li>
                                <li>Merasakan kepedulian dan kasih sayang dari sesama Muslim</li>
                            </ul>
                        `;
                        } else {
                            // For other questions, use Gemini API via backend
                            try {
                                const response = await fetch('{{ route('chatbot.ask') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        message: userText
                                    })
                                });

                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }

                                const data = await response.json();

                                // Extract response text from Gemini API response
                                let responseText = '';
                                
                                if (data.choices && data.choices[0] && data.choices[0].message) {
                                    responseText = data.choices[0].message.content || '';
                                } else if (data.error) {
                                    responseText = 'Maaf, terjadi kesalahan: ' + data.error;
                                }

                                if (responseText) {
                                    appendMessage(formatResponseToHtml(responseText), "bot");
                                } else {
                                    appendMessage(
                                        '<p>⚠️ Format respon dari AI tidak dikenali. Silakan coba lagi.</p>',
                                        "bot");
                                }

                            } catch (apiError) {
                                console.error('Gemini API Error:', apiError);
                                appendMessage(
                                    '<p>⚠️ Terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.</p>',
                                    "bot");
                            }
                        }

                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        if (replyHtml.trim() !== '') {
                            appendMessage(replyHtml, "bot");
                        }
                        // Focus input after bot response
                        setTimeout(() => input.focus(), 300);
                    } catch (err) {
                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        // Display error message
                        appendMessage(
                            '<p>⚠️ Terjadi kesalahan saat memproses pesan Anda. Silakan coba lagi nanti.</p>',
                            "bot");
                        console.error(err);

                        // Focus input after error
                        setTimeout(() => input.focus(), 300);
                    }
                }

                sendBtn.addEventListener("click", sendMessage);
                input.addEventListener("keydown", e => {
                    if (e.key === "Enter" && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Auto-resize textarea as user types
                input.addEventListener("input", () => {
                    input.style.height = 'auto';
                    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
                });

                // Focus input when chat container is clicked
                document.querySelector('.p-3.border-t').addEventListener('click', () => {
                    input.focus();
                });

                // Ensure input remains focused when user interacts with chat
                messages.addEventListener('click', () => {
                    input.focus();
                });
            }

            // Initialize chatbot directly
            initializeChatbot();
        });

        // Prevent blinking on page load
        (function() {
            // Mark page as loaded to trigger animations
            function markPageLoaded() {
                document.body.classList.add('page-loaded');
            }

            // Check if DOM is already loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', markPageLoaded);
            } else {
                // DOM already loaded, mark immediately
                markPageLoaded();
            }
        })();

        // Slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all sliders
            initSlider('campaigns');
            initSlider('news');
            initSlider('artikel');

            // Set up auto-scroll for sliders
            setupAutoScroll('campaigns');
            setupAutoScroll('news');
            setupAutoScroll('artikel');
        });

        function initSlider(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const prevBtn = document.getElementById(`${sliderName}-prev`);
            const nextBtn = document.getElementById(`${sliderName}-next`);
            const indicatorsContainer = document.querySelector(`.${sliderName}-indicators`);

            if (!slider) return;

            // Create indicators
            const items = slider.querySelectorAll('.flex-shrink-0');
            const itemCount = items.length;

            if (indicatorsContainer && itemCount > 0) {
                indicatorsContainer.innerHTML = ''; // Clear existing
                for (let i = 0; i < itemCount; i++) {
                    const indicator = document.createElement('span');
                    indicator.classList.add('w-2', 'h-2', 'rounded-full', 'cursor-pointer', 'transition-all',
                        'duration-300');
                    if (i === 0) {
                        indicator.classList.add('bg-orange-600', 'w-4');
                    } else {
                        indicator.classList.add('bg-gray-300');
                    }
                    indicator.dataset.index = i;
                    indicatorsContainer.appendChild(indicator);

                    // Add click event to indicators
                    indicator.addEventListener('click', () => {
                        scrollToSlide(slider, i);
                        updateIndicators(indicatorsContainer, i);
                    });
                }
            }

            // Navigation button events
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    scrollSlider(slider, -1);
                    updateIndicatorsOnScroll(slider, indicatorsContainer);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    scrollSlider(slider, 1);
                    updateIndicatorsOnScroll(slider, indicatorsContainer);
                });
            }

            // Update indicators when scrolling
            slider.addEventListener('scroll', () => {
                updateIndicatorsOnScroll(slider, indicatorsContainer);
            });

            // Hide navigation buttons on mobile
            function toggleNavigationButtons() {
                if (window.innerWidth < 768) { // md breakpoint
                    if (prevBtn) prevBtn.classList.add('hidden');
                    if (nextBtn) nextBtn.classList.add('hidden');
                } else {
                    if (prevBtn) prevBtn.classList.remove('hidden');
                    if (nextBtn) nextBtn.classList.remove('hidden');
                }
            }

            // Initial check
            toggleNavigationButtons();

            // Check on resize
            window.addEventListener('resize', toggleNavigationButtons);

            // Mouse drag-to-scroll support
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('cursor-grabbing');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 2; // scroll-fast factor
                slider.scrollLeft = scrollLeft - walk;
            });
        }

        function scrollSlider(slider, direction) {
            const scrollAmount = slider.clientWidth * 0.8; // Scroll by 80% of visible area
            slider.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        function scrollToSlide(slider, index) {
            const slideWidth = slider.querySelector('.flex-shrink-0').offsetWidth + 16; // width + gap
            slider.scrollTo({
                left: index * slideWidth,
                behavior: 'smooth'
            });
        }

        function updateIndicators(indicatorsContainer, activeIndex) {
            const indicators = indicatorsContainer.querySelectorAll('span');
            indicators.forEach((indicator, index) => {
                if (index === activeIndex) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-orange-600', 'w-4');
                } else {
                    indicator.classList.remove('bg-orange-600', 'w-4');
                    indicator.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function updateIndicatorsOnScroll(slider, indicatorsContainer) {
            if (!indicatorsContainer) return;

            const scrollLeft = slider.scrollLeft;
            const slideWidth = slider.querySelector('.flex-shrink-0').offsetWidth + 16; // width + gap
            const activeIndex = Math.round(scrollLeft / slideWidth);

            updateIndicators(indicatorsContainer, activeIndex);
        }

        function setupAutoScroll(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const nextBtn = document.getElementById(`${sliderName}-next`);
            const prevBtn = document.getElementById(`${sliderName}-prev`);

            if (!slider) return;

            let autoScrollInterval;

            function startAutoScroll() {
                autoScrollInterval = setInterval(() => {
                    // Check if we're at the end of the slider
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        // Scroll to beginning
                        slider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        // Scroll to next slide
                        scrollSlider(slider, 1);
                    }
                }, 5000); // 5 seconds
            }

            function stopAutoScroll() {
                if (autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                }
            }

            // Start auto scroll
            startAutoScroll();

            // Stop auto scroll on hover
            slider.addEventListener('mouseenter', stopAutoScroll);
            slider.addEventListener('mouseleave', startAutoScroll);

            // Stop auto scroll when navigation buttons are used
            if (prevBtn) {
                prevBtn.addEventListener('click', stopAutoScroll);
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', stopAutoScroll);
            }
        }
    </script>
