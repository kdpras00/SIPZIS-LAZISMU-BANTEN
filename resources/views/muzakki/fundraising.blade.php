@extends('layouts.app')

@section('page-title', 'Galang Dana Saya - SIPZIS Lazismu Banten')

@section('content')
<div class="py-6 px-4 max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0ece6]">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-2xs">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Galang Dana Saya</h1>
                <p class="text-xs text-[#8b7e74] m-0">Kelola dan pantau seluruh campaign penggalangan dana Anda.</p>
            </div>
        </div>
    </div>

    
    @if(isset($campaigns) && $campaigns->count() > 0)
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm">
        <div class="flex items-center justify-between mb-5 border-b border-[#f0ece6] pb-3">
            <h3 class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Campaign Penggalangan Dana</h3>
            <span class="text-xs text-[#8b7e74] font-medium">{{ $campaigns->count() }} Campaign Terdaftar</span>
        </div>

        <div class="space-y-4">
            @foreach($campaigns as $campaign)
            <div class="p-4 rounded-xl border border-[#f0ece6] hover:bg-[#fff7ed] hover:border-[#ffedd5] transition-all duration-200 {{ $loop->odd ? 'bg-[#faf8f5]' : 'bg-white' }} flex items-center justify-between gap-4">
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <h4 class="text-sm font-bold text-[#1c0f0a] m-0 truncate">
                            {{ $campaign->title ?? 'Untitled Campaign' }}
                        </h4>
                        @if(isset($campaign->status) && $campaign->status === 'published')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @elseif(isset($campaign->status) && $campaign->status === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-700 border border-stone-200">{{ ucfirst($campaign->status ?? 'unknown') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-[#8b7e74] line-clamp-2 mb-2 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($campaign->description ?? '', 120) }}
                    </p>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-[#c2410c] font-semibold">
                            Terkumpul: Rp {{ number_format($campaign->collected_amount ?? $campaign->net_collected_amount ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                <a href="{{ route('campaigner.personal', $muzakki->email ?? '') }}" class="w-8 h-8 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] hover:bg-[#c2410c] hover:text-white hover:border-[#c2410c] transition-all flex-shrink-0">
                    <i class="bi bi-chevron-right text-xs"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-[#f0ece6] p-12 text-center shadow-sm mb-6">
        <div class="w-14 h-14 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] mx-auto mb-4 text-2xl">
            <i class="bi bi-archive"></i>
        </div>
        <h3 class="text-base font-bold text-[#1c0f0a] mb-1">Belum Ada Campaign</h3>
        <p class="text-xs text-[#8b7e74] max-w-sm mx-auto mb-5">Anda belum menginisiasi penggalangan dana. Mulai buat campaign pertama Anda sekarang.</p>
       
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
            <a href="{{ route('fundraising') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#c2410c] bg-[#fff7ed] font-semibold no-underline transition-all">
                <i class="bi bi-archive-fill text-lg leading-none"></i>
                <span class="text-[11px]">Galang Dana</span>
            </a>
            <a href="{{ route('amalanku') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-person text-lg leading-none"></i>
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
