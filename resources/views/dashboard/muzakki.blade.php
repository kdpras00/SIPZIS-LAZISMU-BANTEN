@extends('layouts.app')

@section('page-title', 'Dashboard Muzakki')

@section('content')
<style>
    body { background: #faf8f5 !important; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .appear { animation: slideUp 0.5s ease-out both; }
    .appear-d1 { animation-delay: 0.08s; }
    .appear-d2 { animation-delay: 0.16s; }
    .appear-d3 { animation-delay: 0.24s; }

    .fixed-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 2rem);
        max-width: 680px;
        z-index: 1030;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 -4px 20px rgba(28,15,10,0.06);
    }
    body { padding-bottom: 80px !important; }
</style>

@php
    $hour = (int) now('Asia/Jakarta')->format('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $firstName = explode(' ', $muzakki->name)[0];
@endphp

<div class="min-h-screen" style="background: #faf8f5;">
    <div class="max-w-2xl mx-auto px-4 py-5">

        {{-- Profile Section --}}
        <div class="rounded-2xl p-5 mb-4 appear" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">

            {{-- Greeting --}}
            <p class="text-xs mb-3" style="color: #8b7e74;">{{ $greeting }}, {{ $firstName }}</p>

            {{-- Profile Info --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    @if($muzakki->profile_photo)
                        <img src="{{ asset('storage/' . $muzakki->profile_photo) }}" alt="Foto"
                            class="w-12 h-12 rounded-full object-cover flex-shrink-0" style="border: 2px solid #f0ece6;">
                    @else
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white flex-shrink-0" style="background: #c2410c;">
                            {{ strtoupper(substr($muzakki->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-semibold mb-0 truncate" style="color: #1c0f0a;">{{ $muzakki->name }}</p>
                        <p class="text-xs mb-0 truncate" style="color: #8b7e74;">{{ $muzakki->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-xs font-medium no-underline px-3 py-1.5 rounded-lg transition-colors" style="color: #c2410c; border: 1px solid #f0ece6;">
                    Edit
                </a>
            </div>

            {{-- Profile Progress --}}
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs" style="color: #8b7e74;">Kelengkapan profil</span>
                    <span class="text-xs font-semibold" style="color: #1c0f0a;">{{ $profileCompleteness }}%</span>
                </div>
                <div class="w-full h-1.5 rounded-full overflow-hidden" style="background: #f0ece6;">
                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $profileCompleteness }}%; background: {{ $profileCompleteness >= 70 ? '#c2410c' : ($profileCompleteness >= 30 ? '#b45309' : '#dc2626') }};"></div>
                </div>
            </div>

            {{-- Donation Summary --}}
            <div class="rounded-xl p-4" style="background: linear-gradient(135deg, #c2410c, #ea580c); color: white;">
                <p class="text-xs mb-1 opacity-80">Total kontribusi Anda</p>
                <p class="text-xl font-bold mb-1" style="letter-spacing: -0.02em;">Rp {{ number_format($stats['total_zakat_paid'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs opacity-80">{{ $stats['payment_count'] ?? 0 }} kali berdonasi</p>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 gap-3 mb-4 appear appear-d1">
            <div class="rounded-2xl p-4" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs mb-1" style="color: #8b7e74;">Tahun {{ date('Y') }}</p>
                <p class="text-lg font-bold" style="color: #1c0f0a;">Rp {{ number_format($stats['zakat_this_year'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl p-4" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs mb-1" style="color: #8b7e74;">Terakhir donasi</p>
                <p class="text-lg font-bold" style="color: #1c0f0a;">
                    {{ $stats['last_payment'] ? $stats['last_payment']->payment_date->diffForHumans() : 'Belum ada' }}
                </p>
            </div>
        </div>

        {{-- Activities --}}
        <div class="rounded-2xl overflow-hidden mb-4 appear appear-d2" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
                <h3 class="text-sm font-bold mb-0" style="color: #1c0f0a;">Aktivitas</h3>
            </div>

            <a href="{{ route('dashboard.transactions') }}" class="flex items-center gap-3 px-5 py-3.5 no-underline transition-colors hover:bg-gray-50" style="border-bottom: 1px solid #faf5ef;">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #fff7ed;">
                    <i class="bi bi-receipt text-base" style="color: #c2410c;"></i>
                </div>
                <span class="text-sm font-medium flex-1" style="color: #1c0f0a;">Riwayat transaksi</span>
                <i class="bi bi-chevron-right text-xs" style="color: #b8ada3;"></i>
            </a>

            <a href="{{ route('amalanku') }}" class="flex items-center gap-3 px-5 py-3.5 no-underline transition-colors hover:bg-gray-50">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #f0fdf4;">
                    <i class="bi bi-heart text-base" style="color: #15803d;"></i>
                </div>
                <span class="text-sm font-medium flex-1" style="color: #1c0f0a;">Jejak amal saya</span>
                <i class="bi bi-chevron-right text-xs" style="color: #b8ada3;"></i>
            </a>
        </div>

        {{-- Recent Payments --}}
        @if($recentPayments->count() > 0)
        <div class="rounded-2xl overflow-hidden mb-4 appear appear-d3" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
                <h3 class="text-sm font-bold mb-0" style="color: #1c0f0a;">Donasi terakhir</h3>
            </div>

            @foreach($recentPayments as $payment)
            <div class="flex items-center gap-3 px-5 py-3" style="border-bottom: 1px solid #faf5ef;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: #f0fdf4;">
                    <i class="bi bi-check2 text-sm" style="color: #15803d;"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium mb-0 truncate" style="color: #1c0f0a;">{{ $payment->programType?->name ?? 'Donasi Umum' }}</p>
                    <p class="text-xs mb-0" style="color: #b8ada3;">{{ $payment->payment_date->diffForHumans() }}</p>
                </div>
                <p class="text-sm font-bold mb-0 tabular-nums flex-shrink-0" style="color: #1c0f0a;">
                    Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Bottom Nav --}}
        <div class="fixed-bottom-nav" style="background: #fff; border-top: 1px solid #f0ece6;">
            <div class="flex justify-around items-center py-3 px-4">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 no-underline" style="color: #8b7e74;">
                    <i class="bi bi-house text-lg"></i>
                    <span class="text-[10px] font-medium">Beranda</span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 no-underline" style="color: #c2410c;">
                    <i class="bi bi-grid-1x2 text-lg"></i>
                    <span class="text-[10px] font-medium">Dashboard</span>
                </a>
                <a href="{{ route('amalanku') }}" class="flex flex-col items-center gap-1 no-underline" style="color: #8b7e74;">
                    <i class="bi bi-heart text-lg"></i>
                    <span class="text-[10px] font-medium">Amalanku</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
