@extends('layouts.app')

@section('page-title', 'Program Donasi - SIPZIS Lazismu Banten')

@section('content')
<main class="py-6 px-4 max-w-4xl mx-auto" role="main">
    <!-- Header -->
    <header class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0ece6]">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-[#e8e0d6] text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#f0ece6] transition-all shadow-2xs" aria-label="Kembali ke Dashboard">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1c0f0a] tracking-tight mb-0.5">Program Donasi & Zakat</h1>
                <p class="text-xs text-[#8b7e74] m-0">Pilih program kebaikan untuk menyalurkan infaq, zakat, dan sedekah Anda.</p>
            </div>
        </div>

    </header>

    <!-- Programs List Grid -->
    @if($programs->count() > 0)
    <section class="bg-white rounded-2xl border border-[#f0ece6] p-6 mb-6 shadow-sm" aria-labelledby="active-programs-heading">
        <header class="flex items-center justify-between mb-5 border-b border-[#f0ece6] pb-3">
            <h2 id="active-programs-heading" class="text-sm font-bold text-[#1c0f0a] m-0 tracking-tight">Daftar Program Aktif</h2>
            <span class="text-xs text-[#8b7e74] font-medium">{{ $programs->count() }} Program Tersedia</span>
        </header>

        <div class="space-y-4">
            @foreach($programs as $program)
            <article>
                <a href="{{ route('program.show', $program->slug) }}" class="block group no-underline">
                    <div class="p-4 rounded-xl border border-[#f0ece6] hover:bg-[#fff7ed] hover:border-[#ffedd5] transition-all duration-200 {{ $loop->odd ? 'bg-[#faf8f5]' : 'bg-white' }} flex items-center justify-between gap-4">
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-[#fff7ed] text-[#c2410c] border border-[#ffedd5]">
                                    {{ ucfirst($program->category ?? 'Program Utama') }}
                                </span>
                                <h3 class="text-sm font-bold text-[#1c0f0a] group-hover:text-[#c2410c] transition-colors truncate m-0">
                                    {{ $program->name }}
                                </h3>
                            </div>
                            <p class="text-xs text-[#8b7e74] line-clamp-2 mb-2 leading-relaxed">
                                {{ Str::limit($program->description, 120) }}
                            </p>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-[#c2410c] font-semibold">
                                    Terkumpul: Rp {{ number_format($program->net_total_collected ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="w-8 h-8 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] group-hover:bg-[#c2410c] group-hover:text-white group-hover:border-[#c2410c] transition-all flex-shrink-0" aria-hidden="true">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </section>
    @else
    <section class="bg-white rounded-2xl border border-[#f0ece6] p-12 text-center shadow-sm mb-6">
        <div class="w-14 h-14 rounded-full bg-[#faf8f5] border border-[#e8e0d6] flex items-center justify-center text-[#8b7e74] mx-auto mb-4 text-2xl" aria-hidden="true">
            <i class="bi bi-heart"></i>
        </div>
        <h2 class="text-base font-bold text-[#1c0f0a] mb-1">Belum Ada Program</h2>
        <p class="text-xs text-[#8b7e74] max-w-sm mx-auto mb-5">Tidak ada program donasi aktif yang tersedia saat ini.</p>
    </section>
    @endif

    <!-- Fixed Bottom Navigation -->
    <nav class="fixed-bottom-nav bg-white border-t border-[#f0ece6]" aria-label="Navigasi Utama">
        <div class="flex justify-between items-center w-full px-2 py-2 overflow-x-auto gap-1 no-scrollbar">
            <a href="{{ route('home') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all" aria-label="Beranda">
                <i class="bi bi-house text-lg leading-none" aria-hidden="true"></i>
                <span class="text-[11px]">Home</span>
            </a>
            <a href="{{ route('donation') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#c2410c] bg-[#fff7ed] font-semibold no-underline transition-all" aria-current="page" aria-label="Donasi">
                <i class="bi bi-heart-fill text-lg leading-none" aria-hidden="true"></i>
                <span class="text-[11px]">Donasi</span>
            </a>
            <a href="{{ route('fundraising') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all" aria-label="Galang Dana">
                <i class="bi bi-archive text-lg leading-none" aria-hidden="true"></i>
                <span class="text-[11px]">Galang Dana</span>
            </a>
            <a href="{{ route('amalanku') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all" aria-label="Amalanku">
                <i class="bi bi-person text-lg leading-none" aria-hidden="true"></i>
                <span class="text-[11px]">Amalanku</span>
            </a>
        </div>
    </nav>
</main>

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
