@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')

@push('styles')
<style>
    body { background: #faf8f5 !important; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .appear        { animation: slideUp 0.5s ease-out both; }
    .appear-d1     { animation-delay: 0.08s; }
    .appear-d2     { animation-delay: 0.16s; }
    .appear-d3     { animation-delay: 0.24s; }
    .appear-d4     { animation-delay: 0.32s; }
    .appear-d5     { animation-delay: 0.40s; }
    .appear-d6     { animation-delay: 0.48s; }
    .appear-d7     { animation-delay: 0.56s; }

    @media (prefers-reduced-motion: reduce) {
        .appear { animation: none; opacity: 1; }
    }
</style>
@endpush

@php
    $hour = (int) now('Asia/Jakarta')->format('H');
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $balance = $stats['total_payments_this_year'] - $stats['total_distributions_this_year'];
@endphp

<div class="w-full mx-auto px-4 sm:px-6 py-5" style="max-width: 1280px;">
    @include('components.two-factor-reminder')

    
    <div class="mb-8 appear relative z-20">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold mb-1" style="color: #1c0f0a; letter-spacing: -0.02em;">
                    {{ $greeting }}, Admin
                </h1>
                <p class="text-sm" style="color: #8b7e74;">
                    Ringkasan aktivitas Lazismu Banten hari ini
                </p>
            </div>

            
            <div class="inline-flex items-center rounded-xl overflow-visible" style="background: #fff; border: 1px solid #e8e0d6; box-shadow: 0 1px 3px rgba(28,15,10,0.06);">
                
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center">
                    <x-custom-select 
                        name="year" 
                        placeholder="Pilih Tahun" 
                        :selected="$currentYear" 
                        :options="collect($availableYears)->mapWithKeys(fn($y) => [$y => $y])->toArray()"
                        onChange="this.$refs.hiddenInput.form.submit()" 
                        variant="pill"
                        icon="bi bi-calendar3-fill" />
                </form>

                
                <div class="w-px self-stretch my-2" style="background: #e8e0d6;"></div>

                
                <div class="hidden sm:flex items-center gap-2 px-3 py-2.5 text-sm" style="color: #8b7e74; white-space: nowrap;">
                    <i class="bi bi-clock-fill text-xs"></i>
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('ddd, D MMM YYYY') }}
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        
        <div class="col-span-2 appear appear-d1">
            <div class="rounded-2xl p-5 transition-colors duration-200" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total Donasi {{ $currentYear }}</p>
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full flex-shrink-0" style="background: #f0fdf4; color: #15803d;">
                        <i class="fas fa-arrow-up text-[10px]"></i>
                        bulan ini
                    </span>
                </div>
                <p class="text-3xl font-bold mb-1" style="color: #1c0f0a; letter-spacing: -0.02em;">Rp {{ number_format($stats['total_payments_this_year'], 0, ',', '.') }}</p>
                <p class="text-sm mb-0" style="color: #8b7e74;"><span class="font-medium" style="color: #c2410c;">+Rp {{ number_format($stats['total_payments_this_month'], 0, ',', '.') }}</span> di bulan ini</p>
            </div>
        </div>

        
        <div class="appear appear-d2">
            <div class="rounded-2xl p-5 h-full transition-colors duration-200" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Distribusi</p>
                <p class="text-2xl font-bold mb-1" style="color: #1c0f0a; letter-spacing: -0.02em;">Rp {{ number_format($stats['total_distributions_this_year'], 0, ',', '.') }}</p>
                <p class="text-xs mb-0" style="color: #8b7e74;">Tersalurkan tahun ini</p>
            </div>
        </div>

        
        <div class="appear appear-d3">
            <div class="rounded-2xl p-5 h-full transition-colors duration-200" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Saldo</p>
                <p class="text-2xl font-bold mb-1" style="color: {{ $balance >= 0 ? '#c2410c' : '#dc2626' }}; letter-spacing: -0.02em;">Rp {{ number_format(abs($balance), 0, ',', '.') }}</p>
                <p class="text-xs mb-0" style="color: #8b7e74;">{{ $balance >= 0 ? 'Sisa tersedia' : 'Melebihi donasi' }}</p>
            </div>
        </div>

        
        <div class="appear appear-d4">
            <div class="rounded-2xl p-5 h-full transition-colors duration-200" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Muzakki</p>
                <p class="text-2xl font-bold mb-1" style="color: #1c0f0a;">{{ number_format($stats['total_muzakki']) }}</p>
                <p class="text-xs mb-0" style="color: #8b7e74;">Orang terdaftar & aktif</p>
            </div>
        </div>

        
        <div class="appear appear-d4">
            <div class="rounded-2xl p-5 h-full transition-colors duration-200" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Mustahik</p>
                <p class="text-2xl font-bold mb-1" style="color: #1c0f0a;">{{ number_format($stats['total_mustahik']) }}</p>
                <p class="text-xs mb-0" style="color: #8b7e74;">Penerima manfaat</p>
            </div>
        </div>

        
        <div class="col-span-2 appear appear-d4">
            <div class="rounded-2xl p-5 h-full" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <p class="text-sm text-gray-500 mb-4">Donasi per Jenis</p>
                @php
                    $totalAllTypes = collect($programTypeStats)->sum('total');
                @endphp
                <div class="space-y-3">
                    @forelse($programTypeStats as $stat)
                    @php
                        $pct = $totalAllTypes > 0 ? min(100, ($stat->total / $totalAllTypes) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm truncate pr-3" style="color: #1c0f0a;">{{ $stat->program->name ?? ($stat->program_category ? ucfirst(str_replace('-', ' ', $stat->program_category)) : 'Umum') }}</span>
                            <span class="text-sm font-semibold tabular-nums flex-shrink-0" style="color: #1c0f0a;">Rp {{ number_format($stat->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full rounded-full h-1" style="background: #f0ece6;">
                            <div class="h-1 rounded-full" style="width: {{ number_format($pct, 1) }}%; background: #c2410c;"></div>
                        </div>
                    </div>
                    @empty
                        <p class="text-sm" style="color: #b8ada3;">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

        
        <div class="lg:col-span-2 appear appear-d5">
            <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold mb-0.5" style="color: #1c0f0a;">Tren donasi sepanjang tahun</h3>
                        <p class="text-xs" style="color: #8b7e74;">Pertumbuhan donasi bulanan {{ $currentYear }}</p>
                    </div>
                </div>
                <div style="height: 280px;">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="appear appear-d6">
            <div class="rounded-2xl p-5 h-full" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
                <h3 class="text-base font-bold mb-4" style="color: #1c0f0a;">Kategori Mustahik</h3>
                <div class="space-y-3">
                    @php
                        $catColors = ['#c2410c', '#0369a1', '#15803d', '#9333ea', '#b45309', '#dc2626', '#0f766e', '#6d28d9'];
                    @endphp
                    @forelse($mustahikCategoryStats as $i => $stat)
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full flex-shrink-0" style="background: {{ $catColors[$i % count($catColors)] }};"></div>
                            <span class="text-sm flex-1 truncate" style="color: #1c0f0a;">{{ \App\Models\Mustahik::CATEGORIES[$stat->category] ?? ucfirst($stat->category) }}</span>
                            <span class="text-sm font-bold tabular-nums" style="color: #1c0f0a;">{{ $stat->count }}</span>
                        </div>
                    @empty
                        <p class="text-sm" style="color: #b8ada3;">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 appear appear-d7">

        
        <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
                <h3 class="text-base font-bold mb-0" style="color: #1c0f0a;">Donasi masuk</h3>
                <a href="{{ route('payments.index') }}" class="text-xs font-medium no-underline flex items-center gap-1 transition-colors hover:opacity-80" style="color: #c2410c;">
                    Lihat semua <i class="bi bi-arrow-right-circle-fill text-xs"></i>
                </a>
            </div>
            @forelse($recentPayments as $payment)
                <div class="flex items-center gap-3.5 px-5 py-3.5 transition-colors duration-150 hover:bg-gray-50/50" style="border-bottom: 1px solid #faf5ef;">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background: #c2410c;">
                        {{ strtoupper(substr($payment->muzakki?->name ?? 'T', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium mb-0 truncate" style="color: #1c0f0a;">
                            {{ $payment->muzakki?->name ?? ($payment->is_guest_payment ? 'Donatur Tamu' : 'Anonim') }}
                        </p>
                        <p class="text-xs mb-0" style="color: #8b7e74;">{{ $payment->program?->name ?? ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum') }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold mb-0 tabular-nums" style="color: #1c0f0a;">+Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</p>
                        <p class="text-xs mb-0" style="color: #b8ada3;">{{ $payment->payment_date->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-sm mb-0" style="color: #b8ada3;">Belum ada donasi masuk</p>
                </div>
            @endforelse
        </div>

        
        <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
                <h3 class="text-base font-bold mb-0" style="color: #1c0f0a;">Distribusi keluar</h3>
                <a href="{{ route('distributions.index') }}" class="text-xs font-medium no-underline flex items-center gap-1 transition-colors hover:opacity-80" style="color: #0369a1;">
                    Lihat semua <i class="bi bi-arrow-right-circle-fill text-xs"></i>
                </a>
            </div>
            @forelse($recentDistributions as $distribution)
                <div class="flex items-center gap-3.5 px-5 py-3.5 transition-colors duration-150 hover:bg-gray-50/50" style="border-bottom: 1px solid #faf5ef;">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background: #c2410c;">
                        {{ strtoupper(substr($distribution->mustahik?->name ?? 'M', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium mb-0 truncate" style="color: #1c0f0a;">
                            {{ $distribution->mustahik?->name ?? 'Tidak Diketahui' }}
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($distribution->mustahik)
                                <span class="text-xs px-1.5 py-0.5 rounded" style="background: #fff7ed; color: #c2410c;">{{ ucfirst($distribution->mustahik->category) }}</span>
                            @endif
                            <span class="text-xs" style="color: #8b7e74;">{{ $distribution->distribution_type }}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold mb-0 tabular-nums" style="color: #1c0f0a;">-Rp {{ number_format($distribution->amount, 0, ',', '.') }}</p>
                        <p class="text-xs mb-0" style="color: #b8ada3;">{{ $distribution->distribution_date->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-sm mb-0" style="color: #b8ada3;">Belum ada distribusi</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('paymentsChart');
    if (!ctx) return;

    const chartData = @json($chartData);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Donasi (Rp)',
                data: chartData,
                backgroundColor: '#c2410c',
                borderRadius: 6,
                borderSkipped: false,
                barPercentage: 0.6,
                categoryPercentage: 0.7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1c0f0a',
                    titleColor: '#faf8f5',
                    bodyColor: '#faf8f5',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 12, weight: '600' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#8b7e74', font: { size: 12, weight: '500' } },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0ece6', drawBorder: false },
                    ticks: {
                        color: '#8b7e74',
                        font: { size: 11 },
                        callback: function(v) {
                            if (v >= 1000000) return 'Rp ' + (v/1000000).toFixed(0) + 'jt';
                            if (v >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'rb';
                            return 'Rp ' + v;
                        },
                        maxTicksLimit: 5
                    },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
