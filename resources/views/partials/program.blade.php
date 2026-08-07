<div class="relative bg-gray-50 min-h-screen" id="program">
    <!-- Hidden element to pass activeTab from Laravel to JavaScript -->
    @if(isset($activeTab))
    <div id="laravel-active-tab" data-tab="{{ $activeTab }}" style="display: none;"></div>
    @endif
    <!-- Clean background tint -->
    <div class="absolute inset-0 bg-gray-50"></div>

    <div class="relative z-10 py-20">
        <div class="container mx-auto px-4 py-16">
            <!-- Enhanced Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    Kategori Donasi
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Pilih kategori donasi yang sesuai dengan nilai dan prioritas Anda
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex justify-center mb-12 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-button active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-base" data-tab="zakat">
                        Zakat
                    </button>
                    <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-base" data-tab="infaq">
                        Infaq
                    </button>
                    <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-base" data-tab="shadaqah">
                        Shadaqah
                    </button>
                    <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-base" data-tab="pilar">
                        Program Pilar
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Zakat Tab -->
                <div class="tab-panel active" id="zakat">
                    <div class="relative py-4">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($zakatPrograms as $program)
                                <!-- {{ $program->name }} Category -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="bg-white rounded-2xl overflow-hidden block transition-shadow duration-200 hover:shadow-md" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        $imageUrl = $program->image_url ?? asset('img/masjidbanten.png');
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #c2410c;">Zakat</span>
                                        </div>
                                        @if($program->isCompleted())
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #15803d;">Target Tercapai</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-base font-semibold mb-1.5 line-clamp-2" style="color: #1c0f0a;">{{ $program->name }}</h3>
                                        <p class="text-sm mb-4 line-clamp-2" style="color: #8b7e74;">{{ $program->description }}</p>
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs">
                                                <span style="color: #8b7e74;">Terkumpul</span>
                                                <span class="font-semibold" style="color: #c2410c;">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full rounded-full h-1.5" style="background: #f0ece6;">
                                                <div class="h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}" style="background: #c2410c;"></div>
                                            </div>
                                            <span class="text-xs font-medium" style="color: #8b7e74;">{{ number_format($program->progress_percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Infaq Tab -->
                <div class="tab-panel" id="infaq">
                    <div class="relative py-4">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($infaqPrograms as $program)
                                <!-- {{ $program->name }} -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="bg-white rounded-2xl overflow-hidden block transition-shadow duration-200 hover:shadow-md" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        $imageUrl = $program->image_url ?? asset('img/masjidbanten.png');
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #c2410c;">Infaq</span>
                                        </div>
                                        @if($program->isCompleted())
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #15803d;">Target Tercapai</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-base font-semibold mb-1.5 line-clamp-2" style="color: #1c0f0a;">{{ $program->name }}</h3>
                                        <p class="text-sm mb-4 line-clamp-2" style="color: #8b7e74;">{{ $program->description }}</p>
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs">
                                                <span style="color: #8b7e74;">Terkumpul</span>
                                                <span class="font-semibold" style="color: #c2410c;">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full rounded-full h-1.5" style="background: #f0ece6;">
                                                <div class="h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}" style="background: #c2410c;"></div>
                                            </div>
                                            <span class="text-xs font-medium" style="color: #8b7e74;">{{ number_format($program->progress_percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shadaqah Tab -->
                <div class="tab-panel" id="shadaqah">
                    <div class="relative py-4">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($shadaqahPrograms as $program)
                                <!-- {{ $program->name }} -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="bg-white rounded-2xl overflow-hidden block transition-shadow duration-200 hover:shadow-md" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        $imageUrl = $program->image_url ?? asset('img/masjidbanten.png');
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #c2410c;">Shadaqah</span>
                                        </div>
                                        @if($program->isCompleted())
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #15803d;">Target Tercapai</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-base font-semibold mb-1.5 line-clamp-2" style="color: #1c0f0a;">{{ $program->name }}</h3>
                                        <p class="text-sm mb-4 line-clamp-2" style="color: #8b7e74;">{{ $program->description }}</p>
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs">
                                                <span style="color: #8b7e74;">Terkumpul</span>
                                                <span class="font-semibold" style="color: #c2410c;">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full rounded-full h-1.5" style="background: #f0ece6;">
                                                <div class="h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}" style="background: #c2410c;"></div>
                                            </div>
                                            <span class="text-xs font-medium" style="color: #8b7e74;">{{ number_format($program->progress_percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Pilar Tab -->
                <div class="tab-panel" id="pilar">
                    <div class="relative py-4">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($pilarPrograms as $program)
                                <!-- {{ $program->name }} Category -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="bg-white rounded-2xl overflow-hidden block transition-shadow duration-200 hover:shadow-md" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        $imageUrl = $program->image_url ?? asset('img/masjidbanten.png');
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #c2410c;">Program Pilar</span>
                                        </div>
                                        @if($program->isCompleted())
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-block text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background: #15803d;">Target Tercapai</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-base font-semibold mb-1.5 line-clamp-2" style="color: #1c0f0a;">{{ $program->name }}</h3>
                                        <p class="text-sm mb-4 line-clamp-2" style="color: #8b7e74;">{{ $program->description }}</p>
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs">
                                                <span style="color: #8b7e74;">Terkumpul</span>
                                                <span class="font-semibold" style="color: #c2410c;">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full rounded-full h-1.5" style="background: #f0ece6;">
                                                <div class="h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}" style="background: #c2410c;"></div>
                                            </div>
                                            <span class="text-xs font-medium" style="color: #8b7e74;">{{ number_format($program->progress_percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tab styles */
        .tab-button {
            cursor: pointer;
            border-color: transparent;
            color: #6b7280; /* text-gray-500 */
            transition: all 0.2s ease;
        }

        .tab-button:hover {
            border-color: #d1d5db; /* border-gray-300 */
            color: #374151; /* text-gray-700 */
        }

        .tab-button.active {
            border-color: #ea580c !important; /* border-orange-600 */
            color: #ea580c !important; /* text-orange-600 */
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Optimize for performance */
        #program {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanels = document.querySelectorAll('.tab-panel');

            // Function to activate a specific tab
            function activateTab(tabId) {
                // Remove active class from all buttons and panels
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanels.forEach(panel => panel.classList.remove('active'));

                // Add active class to the selected button
                const activeButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
                if (activeButton) {
                    activeButton.classList.add('active');
                }

                // Show corresponding panel
                const activePanel = document.getElementById(tabId);
                if (activePanel) {
                    activePanel.classList.add('active');
                }
            }

            // Check if there's an activeTab parameter passed from the route
            const urlParams = new URLSearchParams(window.location.search);
            const activeTabParam = urlParams.get('tab');

            // Also check if activeTab is passed via the view data (Laravel)
            // We need to check if the variable exists before using it
            let laravelActiveTab = '';
            // Set active tab from Laravel if available
            if (document.getElementById('laravel-active-tab')) {
                laravelActiveTab = document.getElementById('laravel-active-tab').getAttribute('data-tab');
            }

            // Activate the appropriate tab based on route parameter or view data
            if (activeTabParam) {
                activateTab(activeTabParam);
            } else if (laravelActiveTab) {
                activateTab(laravelActiveTab);
            }

            // Add click event listeners to tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    activateTab(tabId);

                    // Update URL without page reload
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.pushState({}, '', url);
                });
            });

            // Set progress bar widths dynamically
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                if (width) {
                    bar.style.width = width + '%';
                }
            });

            // Set background images dynamically
            const bgElements = document.querySelectorAll('.bg-cover[data-bg-url]');
            bgElements.forEach(element => {
                const bgUrl = element.getAttribute('data-bg-url');
                if (bgUrl) {
                    element.style.backgroundImage = 'url(' + bgUrl + ')';
                    element.style.backgroundSize = 'cover';
                    element.style.backgroundPosition = 'center';
                }
            });
        });
    </script>
</div>

@include('partials.footer')