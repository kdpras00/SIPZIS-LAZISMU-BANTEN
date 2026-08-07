@extends('layouts.app')

@section('page-title', 'Transaksi Saya - Dashboard Muzakki')

@section('content')
<div class="py-6 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0ece6]">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-2xs" aria-label="Kembali ke Dashboard">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Transaksi Saya</h1>
                <p class="text-xs text-[#8b7e74] m-0">Riwayat lengkap pembayaran zakat, infaq, dan sedekah Anda.</p>
            </div>
        </div>
    </div>

    @if($payments->count() > 0)
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm">
        <div class="flex items-center justify-between mb-5 border-b border-[#f0ece6] pb-3">
            <h3 class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Riwayat Pembayaran</h3>
            <span class="text-xs text-[#8b7e74] font-medium">{{ now()->translatedFormat('F Y') }}</span>
        </div>

        <!-- Daftar Transaksi -->
        <div class="space-y-3">
            @foreach($payments as $payment)
            <div class="p-4 rounded-xl border border-[#f0ece6] hover:bg-[#fff7ed] hover:border-[#ffedd5] transition-all duration-200 {{ $loop->odd ? 'bg-[#faf8f5]' : 'bg-white' }} flex items-center justify-between gap-4">
                <div class="min-w-0 flex-grow">
                    <small class="text-[10px] text-[#8b7e74] uppercase tracking-wider block mb-1">
                        Donasi • {{ $payment->payment_date->translatedFormat('d F Y') }}
                    </small>
                    <h4 class="text-xs font-bold text-[#1c0f0a] m-0 line-clamp-1">
                        @php
                            $campaign = $payment->campaign;
                        @endphp
                        @if($campaign)
                            {{ $campaign->title }}
                        @elseif($payment->program)
                            {{ $payment->program->name }}
                        @elseif($payment->programType)
                            {{ $payment->programType->name }}
                        @else
                            Donasi Umum
                        @endif
                    </h4>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="mb-1.5">
                        @if($payment->status === 'completed')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                        @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-700 border border-stone-200">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold text-[#c2410c] m-0">
                        Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            {{ $payments->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-12 text-center shadow-sm mb-6">
        <div class="w-14 h-14 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] mx-auto mb-4 text-2xl">
            <i class="bi bi-credit-card-2-front"></i>
        </div>
        <h3 class="text-base font-bold text-[#1c0f0a] mb-1">Belum Ada Transaksi</h3>
        <p class="text-xs text-[#8b7e74] max-w-sm mx-auto mb-5">Anda belum memiliki riwayat transaksi pembayaran zakat atau infaq.</p>
    </div>
    @endif

    <!-- Fixed Bottom Navigation -->
    <nav class="fixed-bottom-nav bg-white border-t border-[#f0ece6]" aria-label="Navigasi Utama">
        <div class="flex justify-between items-center w-full px-2 py-2 overflow-x-auto gap-1 no-scrollbar">
            <a href="{{ route('home') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-house text-lg leading-none"></i>
                <span class="text-[11px]">Home</span>
            </a>
            <a href="{{ route('donation') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-heart text-lg leading-none"></i>
                <span class="text-[11px]">Donasi</span>
            </a>
            <a href="{{ route('fundraising') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-archive text-lg leading-none"></i>
                <span class="text-[11px]">Galang Dana</span>
            </a>
            <a href="{{ route('amalanku') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-person text-lg leading-none"></i>
                <span class="text-[11px]">Amalanku</span>
            </a>
        </div>
    </nav>
</div>

<style>
    .fixed-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 2rem);
        max-width: 896px;
        z-index: 1030;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 -4px 20px rgba(28,15,10,0.06);
    }
    body { padding-bottom: 80px !important; }
</style>
@endsection
