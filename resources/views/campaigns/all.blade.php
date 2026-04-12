@extends('layouts.main')

@section('title', 'Semua Campaign - SIPZIS')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen relative bg-gray-50 pb-20 pt-10 overflow-hidden">
    
    <!-- Background Image & Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center opacity-10"
             style="background-image: url('{{ asset('img/masjid.webp') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/80 to-white/60"></div>
    </div>

    <div class="container relative z-10 mx-auto px-4 max-w-7xl">
        <!-- Header Section -->
        <div class="text-center mb-16 pt-10 animate-fadeInUp">
            <span class="inline-block px-4 py-1.5 rounded-full bg-orange-100 text-orange-600 text-sm font-bold mb-4 border border-white/20 backdrop-blur-sm">
                MARI BERBAGI KEBAIKAN
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Daftar Program & Campaign
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed font-light">
                Salurkan zakat, infaq, dan sedekah Anda melalui berbagai program pilihan yang transparan dan tepat sasaran.
            </p>
        </div>

        <!-- Filter/Navigation (Optional - Implementation for future) -->
        {{-- 
        <div class="flex flex-wrap justify-center gap-4 mb-12 animate-fadeInUp delay-100">
            <a href="#" class="px-6 py-2.5 rounded-full bg-orange-600 text-white font-semibold shadow-lg shadow-green-200 hover:shadow-xl hover:-translate-y-0.5 transition-all">Semua</a>
            <a href="#" class="px-6 py-2.5 rounded-full bg-white text-gray-600 font-medium border border-gray-200 hover:border-orange-300 hover:text-orange-600 transition-all">Pendidikan</a>
            <a href="#" class="px-6 py-2.5 rounded-full bg-white text-gray-600 font-medium border border-gray-200 hover:border-orange-300 hover:text-orange-600 transition-all">Kesehatan</a>
        </div>
        --}}

        <!-- Campaigns Grid -->
        @if($campaigns->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100 animate-fadeInUp">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-inbox text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Campaign</h3>
                <p class="text-gray-500">Mohon maaf, saat ini belum ada campaign yang tersedia.</p>
                <div class="mt-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-orange-600 font-semibold hover:text-orange-700">
                        <i class="bi bi-arrow-left mr-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-10">
                @foreach($campaigns as $campaign)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full animate-fadeInUp" style="animation-delay: {{ $loop->iteration * 100 }}ms">
                        
                        <!-- Image Container -->
                        <div class="relative h-56 overflow-hidden">
                            @php
                                $categoryNames = [
                                    'pendidikan' => 'Pendidikan',
                                    'kesehatan' => 'Kesehatan',
                                    'ekonomi' => 'Ekonomi',
                                    'sosial-dakwah' => 'Sosial & Dakwah',
                                    'kemanusiaan' => 'Kemanusiaan',
                                    'lingkungan' => 'Lingkungan',
                                    'zakat' => 'Zakat',
                                    'infaq' => 'Infaq',
                                    'sedekah' => 'Sedekah'
                                ];
                                
                                $categoryTitle = $categoryNames[$campaign->program_category] ?? ucfirst($campaign->program_category);
                                
                                // Image Logic
                                $imageUrl = asset('img/masjid.webp'); // Default
                                if ($campaign->photo) {
                                    if (filter_var($campaign->photo, FILTER_VALIDATE_URL)) {
                                        $imageUrl = $campaign->photo;
                                    } else {
                                        $imageUrl = asset('storage/' . $campaign->photo);
                                    }
                                }
                            @endphp
                            
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $campaign->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/95 backdrop-blur-sm text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm border border-orange-100">
                                    {{ $categoryTitle }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-snug group-hover:text-orange-600 transition-colors">
                                <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}">
                                    {{ $campaign->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2 flex-1">
                                {{ Str::limit(strip_tags($campaign->description), 100) }}
                            </p>

                            <!-- Progress Section -->
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Terkumpul</span>
                                        <span class="text-sm font-bold text-orange-600">{{ $campaign->formatted_collected_amount }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded-lg">
                                        {{ number_format($campaign->progress_percentage, 0) }}%
                                    </span>
                                </div>
                                
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-4 overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-500 to-orange-500 h-2 rounded-full transition-all duration-1000"
                                         style="width: {{ min($campaign->progress_percentage, 100) }}%">
                                    </div>
                                </div>

                                <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}" 
                                   class="block w-full py-3 px-4 bg-white border border-orange-600 text-orange-600 font-bold rounded-xl text-center hover:bg-orange-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                                    Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination (if applicable) -->
            @if($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8 flex justify-center">
                    {{ $campaigns->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection