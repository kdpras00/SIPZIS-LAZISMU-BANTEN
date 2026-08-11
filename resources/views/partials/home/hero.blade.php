<div class="relative w-full h-[85vh] md:h-screen bg-gray-50 overflow-hidden" id="beranda">
    
    <div id="hero-slider" class="absolute inset-0 w-full h-full cursor-grab">

        
        <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out z-10 opacity-100" data-slide="0">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/masjidbanten.webp') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent md:hidden"></div>
            <div class="relative container h-full mx-auto px-6 md:px-12 flex flex-col justify-center items-start z-10 pt-20">
                @if(Auth::check() && !Auth::user()->two_factor_enabled)
                <div class="mb-6 w-full"></div>
                @endif
                <div class="max-w-2xl">
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

        
        @php $slideIndex = 1; @endphp
        @foreach($heroSlides as $item)
        <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0 bg-gray-50" data-slide="{{ $slideIndex++ }}">
            @php
                $isCampaign = $item instanceof \App\Models\Campaign;
                
                // Determine Image
                $imageUrl = $item->image_url;

                $title = $isCampaign ? $item->title : $item->name;
                $category = $isCampaign ? ($item->program_category ?? 'Program Unggulan') : ($item->category ?? 'Program Lazismu');
                
                $link = $isCampaign 
                    ? route('campaigns.show', [$item->program_category, $item]) 
                    : route('program.show', $item->slug);

                $collected = $isCampaign ? $item->collected_amount : $item->total_collected;
                $percentage = $item->progress_percentage;
            @endphp

            @if($imageUrl)
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $imageUrl }}');"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent md:hidden"></div>

            <div class="relative container h-full mx-auto px-6 md:px-12 flex items-center z-10">
                <div class="max-w-xl lg:max-w-2xl pt-20 md:pt-0">
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

    

    
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20 flex items-center gap-2" id="hero-dots">
        
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
        dotsContainer.innerHTML = '';
        
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `transition-all duration-300 ease-in-out rounded-full focus:outline-none ${i === 0 ? 'w-8 h-2.5 bg-orange-600' : 'w-2.5 h-2.5 bg-gray-300 hover:bg-gray-400'}`;
            dot.ariaLabel = `Go to slide ${i + 1}`;
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }

        const dots = dotsContainer.querySelectorAll('button');

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
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.className = 'transition-all duration-300 ease-in-out h-2.5 w-8 bg-orange-600 rounded-full focus:outline-none';
                } else {
                    dot.className = 'transition-all duration-300 ease-in-out h-2.5 w-2.5 bg-gray-300 hover:bg-gray-400 rounded-full focus:outline-none';
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
