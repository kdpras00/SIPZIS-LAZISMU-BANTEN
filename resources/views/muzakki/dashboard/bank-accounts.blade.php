@extends('layouts.app')

@section('page-title', 'Akun Bank Saya - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
                <i class="bi bi-arrow-left-circle text-xl"></i>
            </a>
            <h5 class="text-xl font-semibold text-gray-900 mb-0">Akun bank</h5>
        </div>
        <a href="{{ route('dashboard.bank-accounts.create') }}" class="px-4 py-2 bg-orange-600 text-white text-sm rounded-full hover:bg-orange-700 transition-colors font-medium">
            <i class="bi bi-plus-circle-fill mr-1"></i>Tambah
        </a>
    </div>

    @if($bankAccounts->isEmpty())
        <div class="bg-white rounded-xl shadow-md mb-6">
            <div class="p-12 text-center">
                <i class="bi bi-bank text-6xl text-gray-400 mb-4 block"></i>
                <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum ada akun bank</h4>
                <p class="text-gray-600 mb-6">Simpan informasi rekening bank Anda untuk memudahkan pembayaran zakat.</p>
                <a href="{{ route('dashboard.bank-accounts.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-full hover:bg-orange-700 transition-colors font-medium">
                    <i class="bi bi-plus-circle-fill mr-2"></i>Tambah Akun Bank
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4 mb-6">
            @foreach($bankAccounts as $account)
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h6 class="text-lg font-semibold text-gray-900 mb-0">{{ $account->bank_name }}</h6>
                            @if($account->is_primary)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Rekening Utama</span>
                            @endif
                        </div>
                        <p class="text-gray-700 text-sm mb-1">{{ $account->account_number }}</p>
                        <p class="text-gray-500 text-sm mb-0">{{ $account->account_holder }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(!$account->is_primary)
                            <form action="{{ route('dashboard.bank-accounts.set-primary', $account) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('dashboard.bank-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus akun bank ini?')">
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
    @endif

    <!-- Bottom Navigation -->
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
