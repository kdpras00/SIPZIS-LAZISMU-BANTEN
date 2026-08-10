@extends('layouts.app')

@section('page-title', 'Donasi Rutin Saya - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="mr-3 transition-colors hover:opacity-75" style="color: #1c0f0a;">
                <i class="bi bi-arrow-left-circle text-xl"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold mb-0.5" style="color: #1c0f0a;">Donasi Rutin</h2>
                <p class="text-xs" style="color: #8b7e74;">Pengaturan pembayaran infaq & sedekah otomatis Anda</p>
            </div>
        </div>
        <a href="{{ route('dashboard.recurring.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Buat Donasi Rutin
        </a>
    </div>

    @if($recurringDonations->isEmpty())
        <div class="rounded-2xl mb-6 p-12 text-center" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
            <i class="bi bi-calendar-check text-5xl mb-3 block" style="color: #8b7e74;"></i>
            <h4 class="text-base font-bold mb-1" style="color: #1c0f0a;">Belum ada donasi rutin</h4>
            <p class="text-xs mb-5" style="color: #8b7e74;">Buat donasi otomatis agar ibadah berbagi Anda tetap rutin dan istiqomah.</p>
            <a href="{{ route('dashboard.recurring.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-plus-circle-fill mr-1.5"></i> Buat Donasi Rutin
            </a>
        </div>
    @else
        <div class="space-y-4 mb-6">
            @foreach($recurringDonations as $donation)
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-gray-500 mb-1">{{ ucfirst($donation->frequency) }}</p>
                        <h6 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ $donation->program?->name ?? 'Program Pilihan' }}
                        </h6>
                        <p class="text-orange-600 font-semibold mb-1">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-sm mb-0">Mulai {{ optional($donation->start_date)->translatedFormat('d F Y') ?? 'segera' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('dashboard.recurring-donations.toggle', $donation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 text-sm font-medium {{ $donation->is_active ? 'text-yellow-600 border border-yellow-200 hover:bg-yellow-50' : 'text-orange-600 border border-orange-200 hover:bg-orange-50' }} rounded-lg transition-colors">
                                {{ $donation->is_active ? 'Jeda' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('dashboard.recurring-donations.destroy', $donation) }}" method="POST" onsubmit="return confirm('Hapus donasi rutin ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-right mb-6">
            <a href="{{ route('dashboard.recurring.create') }}" class="inline-flex items-center px-5 py-2.5 bg-orange-600 text-white rounded-full hover:bg-orange-700 text-sm font-medium">
                <i class="bi bi-plus-circle-fill mr-2"></i>Tambah lagi
            </a>
        </div>
    @endif

    
    <nav class="fixed-bottom-nav bg-white border-t border-[#f0ece6]" aria-label="Navigasi Utama">
                <div class="flex justify-between items-center w-full px-2 py-2 overflow-x-auto gap-1 no-scrollbar">
            <a href="{{ route('home') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-house-fill text-lg leading-none"></i>
                <span class="text-[11px]">Home</span>
            </a>
            <a href="{{ route('donation') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-heart-fill text-lg leading-none"></i>
                <span class="text-[11px]">Donasi</span>
            </a>
            <a href="{{ route('fundraising') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-archive-fill text-lg leading-none"></i>
                <span class="text-[11px]">Galang Dana</span>
            </a>
            <a href="{{ route('amalanku') }}" class="flex flex-shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-[#8b7e74] hover:text-[#1c0f0a] hover:bg-[#faf8f5] font-medium no-underline transition-all">
                <i class="bi bi-person-fill text-lg leading-none"></i>
                <span class="text-[11px]">Amalanku</span>
            </a>
        </div>
    </nav>

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
