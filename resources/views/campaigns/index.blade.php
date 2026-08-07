@extends('layouts.main')

@section('title', 'Campaigns - SIPZIS')

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen" style="background: #faf8f5;">
    <div class="max-w-6xl mx-auto px-4 py-12 pt-28">

        <!-- Back + Header -->
        <div class="mb-10">
            <a href="{{ route('program') }}" class="inline-flex items-center gap-2 text-sm font-medium mb-6 transition-colors" style="color: #8b7e74;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Program
            </a>
            <h1 class="text-3xl md:text-4xl font-bold mb-2" style="color: #1c0f0a; text-wrap: balance;">
                Campaign {{ $categoryDetails['title'] }}
            </h1>
            <p class="text-base" style="color: #8b7e74;">{{ $categoryDetails['subtitle'] }}</p>
        </div>

        <!-- Campaigns List -->
        @if($campaigns->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center" style="box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-base font-medium mb-1" style="color: #1c0f0a;">Belum ada campaign</p>
            <p class="text-sm" style="color: #8b7e74;">Belum ada campaign yang tersedia untuk kategori ini.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
            <div class="bg-white rounded-2xl overflow-hidden transition-shadow duration-200 hover:shadow-md cursor-pointer" style="box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                <div class="h-48 overflow-hidden">
                    <img src="{{ $campaign->image_url }}"
                        alt="{{ $campaign->title }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-base font-semibold mb-2 line-clamp-2" style="color: #1c0f0a;">
                        {{ $campaign->title }}
                    </h3>
                    <p class="text-sm mb-4 line-clamp-3" style="color: #8b7e74;">
                        {{ Str::limit($campaign->description, 100) }}
                    </p>

                    <!-- Progress -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1.5" style="color: #8b7e74;">
                            <span>{{ $campaign->formatted_collected_amount }}</span>
                            <span class="font-medium" style="color: #1c0f0a;">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full" style="width: {{ min($campaign->progress_percentage, 100) }}%; background: #c2410c;"></div>
                        </div>
                    </div>

                    <a href="{{ route('campaigns.show', [$category, $campaign]) }}"
                        class="block w-full text-center text-sm font-semibold text-white py-2.5 px-4 rounded-xl transition-colors duration-200"
                        style="background: #c2410c;">
                        Donasi Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection