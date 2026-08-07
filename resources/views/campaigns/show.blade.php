@extends('layouts.main')

@section('title', $campaign->title . ' - SIPZIS')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 pb-12 pt-6"> <!-- pt-24 if navbar is fixed -->
        
        <!-- Breadcrumb & Back -->
        <div class="container mx-auto px-4 max-w-7xl mb-6">
            <a href="{{ route('campaigns.index', $category) }}" 
               class="inline-flex items-center text-gray-500 hover:text-orange-600 transition-colors duration-200">
                <i class="bi bi-arrow-left-circle mr-2"></i>
                Kembali ke Daftar Campaign
            </a>
        </div>

        <div class="container mx-auto px-4 max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Campaign Image & Header -->
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
                        <div class="relative h-[300px] md:h-[400px] w-full group overflow-hidden">
                            <img src="{{ $campaign->image_url }}" 
                                 alt="{{ $campaign->title }}" 
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <!-- Overlay dipergelap agar tulisan putih lebih terbaca -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <!-- Badges on Image -->
                            <div class="absolute bottom-4 left-4 right-4 text-white z-10">
                                <span class="inline-block px-3 py-1 rounded-full bg-orange-600/90 backdrop-blur-sm text-xs font-semibold mb-2">
                                    {{ $categoryDetails['title'] }}
                                </span>
                                <h1 class="text-2xl md:text-3xl font-bold leading-tight text-white mb-2 drop-shadow-md">
                                    {{ $campaign->title }}
                                </h1>
                                <div class="flex items-center text-sm text-orange-50 space-x-4">
                                    <span class="flex items-center drop-shadow-sm">
                                        <i class="bi bi-calendar3 mr-2"></i>
                                        {{ $campaign->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center drop-shadow-sm">
                                        <i class="bi bi-geo-alt mr-2"></i>
                                        Lazismu Pusat
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs/Navigation (Optional, keep simple for now) -->
                    
                    <!-- Description -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="bi bi-card-text mr-3 text-orange-600"></i>
                            Keterangan Lengkap
                        </h3>
                        <div class="prose prose-green max-w-none text-gray-600 leading-relaxed text-justify">
                            {!! nl2br(e($campaign->description)) !!}
                        </div>
                    </div>

                    <!-- Doa & Harapan (Placeholder for improvements) -->
                    <!-- <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Doa Donatur</h3>
                         List of comments could go here 
                    </div> -->

                </div>

                <!-- RIGHT COLUMN: Donation Action (Sticky) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        <!-- Donation Card -->
                        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 relative overflow-hidden">
                            <!-- Background Decoration -->
                            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-50"></div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Target Donasi</h3>
                            
                            <div class="mb-6">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-3xl font-bold text-orange-600">{{ $campaign->formatted_collected_amount }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mb-3">
                                    <span>Terkumpul</span>
                                    {{-- <span>Target: {{ $campaign->formatted_target_amount }}</span> --}}
                                </div>
                                
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-3 rounded-full relative"
                                         style="width: {{ min($campaign->progress_percentage, 100) }}%">
                                         @if($campaign->progress_percentage > 0)
                                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-white rounded-full shadow-sm mr-0.5"></div>
                                         @endif
                                    </div>
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="text-sm font-semibold text-orange-600">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                                </div>
                            </div>

                            <button id="donateButton" 
                                    class="w-full group relative overflow-hidden bg-gradient-to-br from-orange-600 to-orange-700 text-white rounded-xl px-4 py-4 font-bold shadow-green-200 shadow-xl transition-all hover:shadow-green-300 hover:scale-[1.02] active:scale-[0.98]">
                                <div class="relative z-10 flex items-center justify-center">
                                    <span>Donasi Sekarang</span>
                                    <i class="bi bi-heart-fill ml-2 group-hover:animate-pulse"></i>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </button>

                            <div class="mt-6 flex items-center justify-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <i class="bi bi-shield-check text-orange-500 mr-1"></i>
                                    Terverifikasi
                                </span>
                                <span class="flex items-center">
                                    <i class="bi bi-lock text-orange-500 mr-1"></i>
                                    Pembayaran Aman
                                </span>
                            </div>
                        </div>

                        <!-- Donors List Card -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-900">Donatur Terbaru</h3>
                                <span class="text-xs px-2 py-1 bg-orange-50 text-orange-700 rounded-full font-medium">
                                    {{ $campaign->zakatPayments->count() }} Orang
                                </span>
                            </div>

                            <div class="max-h-[300px] overflow-y-auto pr-2 space-y-4 custom-scrollbar">
                                @forelse($campaign->zakatPayments as $payment)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-100 to-orange-50 flex items-center justify-center text-orange-600 font-bold text-sm">
                                                {{ substr($payment->muzakki ? $payment->muzakki->name : 'A', 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-start">
                                                <h4 class="text-sm font-semibold text-gray-900">
                                                    {{ $payment->muzakki ? $payment->muzakki->name : 'Hamba Allah' }}
                                                </h4>
                                                <span class="text-xs font-bold text-orange-600">
                                                    {{ $payment->formatted_amount }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $payment->created_at->diffForHumans() }}
                                            </p>
                                            <p class="text-xs text-gray-600 italic mt-1 line-clamp-2">
                                                "Semoga berkah dan bermanfaat bagi yang membutuhkan"
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="bi bi-heart text-gray-300 text-xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500">Belum ada donatur.</p>
                                        <p class="text-xs text-gray-400">Jadilah yang pertama!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Share Card -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Bagikan Campaign Ini</h4>
                            <div class="flex justify-center space-x-3">
                                <button class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                                    <i class="bi bi-facebook"></i>
                                </button>
                                <button class="w-10 h-10 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors flex items-center justify-center">
                                    <i class="bi bi-whatsapp"></i>
                                </button>
                                <button class="w-10 h-10 rounded-full bg-sky-50 text-sky-500 hover:bg-sky-100 transition-colors flex items-center justify-center">
                                    <i class="bi bi-twitter"></i>
                                </button>
                                <button class="w-10 h-10 rounded-full bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center" onclick="navigator.clipboard.writeText(window.location.href); alert('Link disalin!')">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script for Redirect -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const donateButton = document.getElementById('donateButton');
            if (donateButton) {
                donateButton.addEventListener('click', function() {
                    const campaignId = '{{ $campaign->id }}';
                    const category = '{{ $category }}';
                    window.location.href = "{{ route('guest.payment.create') }}?campaign=" + campaignId + "&category=" + category;
                });
            }
        });
    </script>

    <!-- Additional Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; 
        }
    </style>
@endsection