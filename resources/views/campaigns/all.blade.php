@extends('layouts.main')

@section('title', 'Semua Campaign - SIPZIS')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen relative bg-gray-50 pb-20 pt-28 overflow-hidden">
    
    
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center opacity-10"
             style="background-image: url('{{ asset('img/masjidbanten.webp') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/80 to-white/60"></div>
    </div>

    <div class="container relative z-10 mx-auto px-4 max-w-7xl">
        
        <div class="text-center mb-16 pt-10 animate-fadeInUp">
            
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Daftar Program & Campaign
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed font-light">
                Salurkan zakat, infaq, dan sedekah Anda melalui berbagai program pilihan yang transparan dan tepat sasaran.
            </p>
        </div>

        
        

        
        @if($campaigns->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100 animate-fadeInUp">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-inbox text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Campaign</h3>
                <p class="text-gray-500">Mohon maaf, saat ini belum ada campaign yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-10">
                @foreach($campaigns as $campaign)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full animate-fadeInUp" style="animation-delay: {{ $loop->iteration * 100 }}ms">
                        
                        
                        <div class="relative h-56 overflow-hidden bg-gray-200 flex items-center justify-center">
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
                                $imageUrl = null;
                                if ($campaign->photo) {
                                    if (filter_var($campaign->photo, FILTER_VALIDATE_URL)) {
                                        $imageUrl = $campaign->photo;
                                    } else {
                                        $imageUrl = asset('storage/' . $campaign->photo);
                                    }
                                }
                            @endphp
                            
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $campaign->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-700">
                            @else
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            @endif
                            
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                            
                            
                            <div class="absolute top-4 left-4">
                                <span class="text-orange-700 text-xs font-bold">
                                    {{ $categoryTitle }}
                                </span>
                            </div>
                        </div>

                        
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-snug group-hover:text-orange-600 transition-colors">
                                <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}">
                                    {{ $campaign->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2 flex-1">
                                {{ Str::limit(strip_tags($campaign->description), 100) }}
                            </p>

                            
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Terkumpul</span>
                                        <span class="text-sm font-bold text-orange-600">{{ $campaign->formatted_collected_amount }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">
                                        {{ number_format($campaign->progress_percentage, 0) }}%
                                    </span>
                                </div>
                                
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-4 overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-500 to-orange-500 h-2 rounded-full transition-all duration-1000"
                                         style="width: {{ min($campaign->progress_percentage, 100) }}%">
                                    </div>
                                </div>

                                <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}" 
                                   class="block w-full py-3 px-4 bg-white border border-orange-600 text-orange-600 font-bold rounded-xl text-center transition-all duration-300">
                                    Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            
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