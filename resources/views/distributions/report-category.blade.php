@extends('layouts.app')

@section('page-title', 'Laporan Distribusi per Asnaf')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Laporan Distribusi per Asnaf</h2>
            <p class="text-sm" style="color: #8b7e74;">Laporan distribusi zakat berdasarkan kategori mustahik (8 Asnaf)</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('distributions.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;" onclick="window.print()">
                <i class="bi bi-printer mr-1.5"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Year Filter --}}
    <div class="rounded-2xl p-5 mb-6" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <form method="GET" action="{{ route('distributions.report.category') }}" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <label for="year" class="text-xs font-bold whitespace-nowrap" style="color: #1c0f0a;">Filter Tahun:</label>
                @php
                    $yearOptions = [];
                    for ($y = date('Y'); $y >= 2020; $y--) {
                        $yearOptions[$y] = (string)$y;
                    }
                @endphp
                <div class="w-[140px]">
                    <x-custom-select 
                        id="year"
                        name="year"
                        :options="$yearOptions"
                        :selected="$year"
                        placeholder="Tahun"
                        onChange="this.$refs.hiddenInput.form.submit()"
                    />
                </div>
            </div>
            <div class="text-xs" style="color: #8b7e74;">
                <i class="bi bi-info-circle-fill me-1" style="color: #c2410c;"></i>
                Menampilkan data distribusi untuk tahun <span class="font-bold" style="color: #1c0f0a;">{{ $year }}</span>
            </div>
        </form>
    </div>

    {{-- Summary Statistics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total Kategori</p>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ count($distributions) }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total Distribusi</p>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ $distributions->sum('count') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total Mustahik</p>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ $distributions->sum(function($group) { return $group['mustahik']->count(); }) }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total Nilai</p>
            <p class="text-xl font-bold mb-0 text-[#c2410c]">Rp {{ number_format($distributions->sum('total_amount'), 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Distribution Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
            <h5 class="text-xs font-bold uppercase tracking-wider mb-4" style="color: #8b7e74;">Distribusi per Kategori Asnaf</h5>
            <div class="h-[300px]">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
        <div class="lg:col-span-1 rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
            <h5 class="text-xs font-bold uppercase tracking-wider mb-4" style="color: #8b7e74;">Persentase Distribusi</h5>
            <div class="h-[300px]">
                <canvas id="percentageChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Detailed Report Table --}}
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        @if($distributions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#f0ece6]">
                <thead style="background: #faf8f5;">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori Asnaf</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Deskripsi</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jumlah Distribusi</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jumlah Mustahik</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Total Nilai</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Rata-rata per Distribusi</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0ece6]">
                    @foreach($categories as $categoryKey => $categoryDesc)
                    @php
                        $categoryData = $distributions->get($categoryKey, ['count' => 0, 'total_amount' => 0, 'mustahik' => collect()]);
                        $totalAllCategories = $distributions->sum('total_amount');
                        $percentage = $totalAllCategories > 0 ? ($categoryData['total_amount'] / $totalAllCategories) * 100 : 0;
                        $averagePerDistribution = $categoryData['count'] > 0 ? $categoryData['total_amount'] / $categoryData['count'] : 0;
                    @endphp
                    <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 border border-[#f0ece6] flex-shrink-0" style="background: #faf8f5;">
                                    <i class="bi bi-people text-sm" style="color: #c2410c;"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold" style="color: #1c0f0a;">{{ ucfirst(str_replace('_', ' ', $categoryKey)) }}</div>
                                    @if($categoryData['count'] > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">Aktif</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-medium" style="background: #f0ece6; color: #8b7e74;">Belum Ada Distribusi</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-xs" style="color: #8b7e74;">
                            {{ Str::after($categoryDesc, ' - ') }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold" style="color: #1c0f0a;">
                            @if($categoryData['count'] > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #f0ece6; color: #1c0f0a;">{{ number_format($categoryData['count']) }}</span>
                            @else
                            <span style="color: #8b7e74;">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold" style="color: #1c0f0a;">
                            @if($categoryData['mustahik']->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #f0ece6; color: #1c0f0a;">{{ $categoryData['mustahik']->count() }}</span>
                            @else
                            <span style="color: #8b7e74;">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right text-xs font-bold text-[#c2410c]">
                            @if($categoryData['total_amount'] > 0)
                            Rp {{ number_format($categoryData['total_amount'], 0, ',', '.') }}
                            @else
                            <span style="color: #8b7e74;">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium" style="color: #1c0f0a;">
                            @if($averagePerDistribution > 0)
                            Rp {{ number_format($averagePerDistribution, 0, ',', '.') }}
                            @else
                            <span style="color: #8b7e74;">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($percentage > 0)
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 bg-[#f0ece6] rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-300" style="background: #c2410c; width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-xs font-bold" style="color: #1c0f0a;">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            @else
                            <span class="text-xs" style="color: #8b7e74;">0%</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background: #faf8f5;">
                    <tr class="font-bold text-xs" style="color: #1c0f0a;">
                        <td colspan="2" class="px-5 py-3.5">TOTAL</td>
                        <td class="px-5 py-3.5 text-center">{{ number_format($distributions->sum('count')) }}</td>
                        <td class="px-5 py-3.5 text-center">{{ $distributions->sum(function($group) { return $group['mustahik']->count(); }) }}</td>
                        <td class="px-5 py-3.5 text-right text-[#c2410c]">Rp {{ number_format($distributions->sum('total_amount'), 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-center">-</td>
                        <td class="px-5 py-3.5 text-center">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-12 px-6">
            <i class="bi bi-inbox text-4xl mb-2 block" style="color: #d1cbc4;"></i>
            <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Tidak Ada Data Distribusi</p>
            <p class="text-xs mt-1 mb-4" style="color: #8b7e74;">Belum ada distribusi zakat yang tercatat untuk tahun {{ $year }}</p>
            <a href="{{ route('distributions.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Distribusi Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<style media="print">
    .no-print { display: none !important; }
    @page { margin: 1cm; }
    body { font-size: 12px; }
    h2 { font-size: 18px; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categories = @json($categories);
    const distributions = @json($distributions);
    
    const labels = [];
    const values = [];
    const colors = [
        '#c2410c', '#ea580c', '#f97316', '#fb923c',
        '#fdba74', '#fed7aa', '#8b7e74', '#1c0f0a'
    ];
    
    Object.keys(categories).forEach((key, index) => {
        const data = distributions[key] || { total_amount: 0, count: 0 };
        if (data.total_amount > 0) {
            labels.push(key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' '));
            values.push(data.total_amount);
        }
    });

    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Distribusi (Rp)',
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    const percentageCtx = document.getElementById('percentageChart').getContext('2d');
    new Chart(percentageCtx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 10, font: { size: 11 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': ' + percentage + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush