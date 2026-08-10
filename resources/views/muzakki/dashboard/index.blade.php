@extends('layouts.app')

@section('page-title', 'Dashboard Muzakki')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    @include('components.two-factor-reminder')
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl shadow-[0_8px_30px_-4px_rgba(234,88,12,0.3)] text-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_-4px_rgba(234,88,12,0.4)] group">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-orange-100 text-xs font-semibold uppercase mb-1">Total Zakat</h6>
                    <h4 class="text-2xl font-bold mb-0">Rp {{ number_format($stats['total_zakat_paid'], 0, ',', '.') }}</h4>
                    <small class="text-orange-100 opacity-80 block mt-1">Sepanjang masa</small>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                    <i class="fa-solid fa-wallet text-2xl text-white"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-gradient-to-br from-amber-400 to-amber-500 rounded-3xl shadow-[0_8px_30px_-4px_rgba(251,191,36,0.3)] text-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_-4px_rgba(251,191,36,0.4)] group">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-amber-100 text-xs font-semibold uppercase mb-1">Zakat {{ date('Y') }}</h6>
                    <h4 class="text-2xl font-bold mb-0">Rp {{ number_format($stats['zakat_this_year'], 0, ',', '.') }}</h4>
                    <small class="text-amber-100 opacity-80 block mt-1">Tahun ini</small>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                    <i class="fa-solid fa-calendar-check text-2xl text-white"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-[0_8px_30px_-4px_rgba(59,130,246,0.3)] text-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_-4px_rgba(59,130,246,0.4)] group">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-blue-100 text-xs font-semibold uppercase mb-1">Jumlah Transaksi</h6>
                    <h4 class="text-2xl font-bold mb-0">{{ number_format($stats['payment_count']) }}</h4>
                    <small class="text-blue-100 opacity-80 block mt-1">Kali pembayaran</small>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl text-white"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-[0_8px_30px_-4px_rgba(99,102,241,0.3)] text-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_-4px_rgba(99,102,241,0.4)] group">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-indigo-100 text-xs font-semibold uppercase mb-1">Terakhir Bayar</h6>
                    @if($stats['last_payment'])
                    <h6 class="text-lg font-bold mb-0">{{ $stats['last_payment']->payment_date->format('d M Y') }}</h6>
                    <small class="text-indigo-100 opacity-80 block mt-1">{{ $stats['last_payment']->program ? $stats['last_payment']->program->name : 'Donasi Umum' }}</small>
                    @else
                    <h6 class="text-lg font-bold mb-0">Belum ada</h6>
                    <small class="text-indigo-100 opacity-80 block mt-1">Pembayaran</small>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-inner">
                    <i class="fa-solid fa-clock-rotate-left text-2xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('dashboard.transactions') ?? '#' }}" class="bg-white rounded-3xl border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 hover:border-[#c2410c]/20 text-center p-6 block no-underline h-full group">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 border border-blue-200/50 shadow-sm">
                <i class="fa-solid fa-money-check-dollar text-3xl text-blue-500"></i>
            </div>
            <h5 class="text-lg font-bold text-[#1c0f0a] mb-2">Transaksi Saya</h5>
            <p class="text-[#8b7e74] text-sm mb-0">Lihat riwayat pembayaran donasi Anda</p>
        </a>

        <a href="{{ route('dashboard.recurring') ?? '#' }}" class="bg-white rounded-3xl border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 hover:border-[#c2410c]/20 text-center p-6 block no-underline h-full group">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 border border-orange-200/50 shadow-sm">
                <i class="fa-solid fa-repeat text-3xl text-orange-500"></i>
            </div>
            <h5 class="text-lg font-bold text-[#1c0f0a] mb-2">Donasi Rutin</h5>
            <p class="text-[#8b7e74] text-sm mb-0">Atur donasi otomatis setiap bulan</p>
        </a>

        <a href="{{ route('dashboard.bank-accounts') ?? '#' }}" class="bg-white rounded-3xl border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 hover:border-[#c2410c]/20 text-center p-6 block no-underline h-full group">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 border border-teal-200/50 shadow-sm">
                <i class="fa-solid fa-building-columns text-3xl text-teal-500"></i>
            </div>
            <h5 class="text-lg font-bold text-[#1c0f0a] mb-2">Akun Bank</h5>
            <p class="text-[#8b7e74] text-sm mb-0">Kelola rekening bank Anda</p>
        </a>

        <a href="{{ route('dashboard.management') ?? '#' }}" class="bg-white rounded-3xl border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 hover:border-[#c2410c]/20 text-center p-6 block no-underline h-full group">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 border border-purple-200/50 shadow-sm">
                <i class="fa-solid fa-user-gear text-3xl text-purple-500"></i>
            </div>
            <h5 class="text-lg font-bold text-[#1c0f0a] mb-2">Pengaturan Akun</h5>
            <p class="text-[#8b7e74] text-sm mb-0">Kelola profil dan akun Anda</p>
        </a>
    </div>

    
    <div class="bg-white rounded-3xl border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-6">
        <div class="border-b border-gray-100 px-6 py-5 flex justify-between items-center bg-white/50">
            <h5 class="text-base font-bold text-[#1c0f0a] mb-0 flex items-center">
                <i class="fa-solid fa-clock-rotate-left mr-3 text-[#c2410c]"></i> Aktivitas Terbaru
            </h5>
        </div>
        <div class="p-0">
            @if(isset($recentPayments) && $recentPayments->count() > 0)
            @foreach($recentPayments as $payment)
            <div class="flex justify-between items-center p-5 border-b border-dashed border-gray-100 hover:bg-orange-50/30 transition-colors group">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-[#c2410c] mr-4 group-hover:scale-110 transition-transform border border-orange-100/50">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <h6 class="font-bold text-[#1c0f0a] mb-1 text-sm">{{ $payment->program ? $payment->program->name : 'Donasi Umum' }}</h6>
                        <small class="text-[#8b7e74] font-mono text-xs">{{ $payment->payment_code }}</small>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-[#1c0f0a]">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                    <small class="text-[#8b7e74] text-xs">{{ $payment->payment_date->format('d M Y') }}</small>
                </div>
            </div>
            @endforeach
            @else
            <div class="p-10 text-center text-[#8b7e74]">
                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-box-open text-3xl opacity-50"></i>
                </div>
                <p class="text-sm font-medium mb-0">Belum ada aktivitas pembayaran</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection