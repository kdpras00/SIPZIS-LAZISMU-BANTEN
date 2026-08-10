@extends('layouts.app')

@section('page-title', 'Amalanku - Dashboard Muzakki')

@section('content')
<div class="py-6 px-4 max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0ece6]">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-2xs">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Amalanku</h1>
                <p class="text-xs text-[#8b7e74] m-0">Ringkasan riwayat infaq, zakat, dan kebaikan Anda.</p>
            </div>
        </div>

        <a href="{{ route('dashboard.transactions') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-[#e8e0d6] text-[#1c0f0a] hover:bg-[#f0ece6] font-semibold text-xs rounded-xl transition-all shadow-2xs">
            <i class="bi bi-[#c2410c] bi-receipt text-sm text-[#c2410c]"></i> Seluruh Transaksi
        </a>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-5 rounded-2xl bg-white border border-[#f0ece6] shadow-sm">
            <span class="text-xs font-semibold text-[#8b7e74] uppercase tracking-wider block mb-1">Total Donasi</span>
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-[#1c0f0a] tracking-tight">{{ $stats['total_count'] }}</span>
                <span class="text-xs text-[#8b7e74]">kali</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#f0ece6] shadow-sm">
            <span class="text-xs font-semibold text-[#8b7e74] uppercase tracking-wider block mb-1">Total Nominal Terakumulasi</span>
            <div class="text-2xl font-bold text-[#c2410c] tracking-tight">
                Rp {{ number_format($stats['total_donated'], 0, ',', '.') }}
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#f0ece6] shadow-sm">
            <span class="text-xs font-semibold text-[#8b7e74] uppercase tracking-wider block mb-1">Total Tahun Ini</span>
            <div class="text-2xl font-bold text-[#1c0f0a] tracking-tight">
                Rp {{ number_format($stats['this_year'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    
    @if($payments->count() > 0)
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm">
        <div class="flex items-center justify-between mb-5 border-b border-[#f0ece6] pb-3">
            <h3 class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Donasi Terbaru</h3>
            <a href="{{ route('dashboard.transactions') }}" class="text-xs font-semibold text-[#c2410c] hover:underline">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @foreach($payments as $payment)
            <div class="p-4 rounded-xl border border-[#f0ece6] hover:bg-[#fff7ed] hover:border-[#ffedd5] transition-all duration-200 {{ $loop->odd ? 'bg-[#faf8f5]' : 'bg-white' }} flex items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-bold text-[#1c0f0a] m-0 mb-1">
                        {{ $payment->program ? $payment->program->name : 'Donasi Umum' }}
                    </h4>
                    <span class="text-[11px] text-[#8b7e74]">
                        {{ $payment->payment_date->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-[#c2410c] m-0 mb-1">
                        Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-12 text-center shadow-sm mb-6">
        <div class="w-14 h-14 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] mx-auto mb-4 text-2xl">
            <i class="bi bi-heart"></i>
        </div>
        <h3 class="text-base font-bold text-[#1c0f0a] mb-1">Belum Ada Riwayat Donasi</h3>
        <p class="text-xs text-[#8b7e74] max-w-sm mx-auto mb-5">Mulai berdonasi untuk mencatat jejak amal kebaikan Anda di sini.</p>
    </div>
    @endif

    
    <div class="fixed-bottom-nav bg-white border-t border-[#f0ece6]">
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
            <a href="{{ route('amalanku') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#c2410c] bg-[#fff7ed] font-semibold no-underline transition-all">
                <i class="bi bi-person-fill text-lg leading-none"></i>
                <span class="text-[11px]">Amalanku</span>
            </a>
        </div>
    </div>
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
