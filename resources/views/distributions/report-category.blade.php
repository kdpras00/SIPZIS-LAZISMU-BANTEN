@extends('layouts.app')

@section('page-title', 'Laporan Distribusi per Asnaf')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Laporan Distribusi per Asnaf</h2>
        <p class="text-muted">Laporan distribusi zakat berdasarkan kategori mustahik (8 Asnaf)</p>
    </div>
    <div>
        <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- Year Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('distributions.report.category') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="year" class="form-label">Filter Tahun</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-9">
                <div class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Menampilkan data distribusi untuk tahun {{ $year }}
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Kategori</h6>
                        <h3 class="mb-0">{{ count($distributions) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-collection fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Distribusi</h6>
                        <h3 class="mb-0">{{ $distributions->sum('count') }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Mustahik</h6>
                        <h3 class="mb-0">{{ $distributions->sum(function($group) { return $group['mustahik']->count(); }) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Nilai</h6>
                        <h3 class="mb-0">Rp {{ number_format($distributions->sum('total_amount'), 0, ',', '.') }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution Chart -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi per Kategori Asnaf</h5>
            </div>
            <div class="card-body">
                <canvas id="distributionChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Persentase Distribusi</h5>
            </div>
            <div class="card-body">
                <canvas id="percentageChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Laporan Detail per Kategori Asnaf</h5>
    </div>
    <div class="card-body p-0">
        @if($distributions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Kategori Asnaf</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Distribusi</th>
                        <th class="text-center">Jumlah Mustahik</th>
                        <th class="text-end">Total Nilai</th>
                        <th class="text-center">Rata-rata per Distribusi</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $categoryKey => $categoryDesc)
                    @php
                        $categoryData = $distributions->get($categoryKey, ['count' => 0, 'total_amount' => 0, 'mustahik' => collect()]);
                        $totalAllCategories = $distributions->sum('total_amount');
                        $percentage = $totalAllCategories > 0 ? ($categoryData['total_amount'] / $totalAllCategories) * 100 : 0;
                        $averagePerDistribution = $categoryData['count'] > 0 ? $categoryData['total_amount'] / $categoryData['count'] : 0;
                    @endphp
                    <tr class="{{ $categoryData['count'] > 0 ? '' : 'text-muted' }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $categoryData['count'] > 0 ? 'primary' : 'light' }} bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="bi bi-people{{ $categoryData['count'] > 0 ? '-fill' : '' }} text-{{ $categoryData['count'] > 0 ? 'primary' : 'muted' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $categoryKey)) }}</div>
                                    @if($categoryData['count'] > 0)
                                    <span class="badge bg-success-subtle text-success-emphasis">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">Belum Ada Distribusi</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::after($categoryDesc, ' - ') }}</small>
                        </td>
                        <td class="text-center">
                            @if($categoryData['count'] > 0)
                            <span class="badge bg-primary-subtle text-primary-emphasis">{{ number_format($categoryData['count']) }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($categoryData['mustahik']->count() > 0)
                            <span class="badge bg-info-subtle text-info-emphasis">{{ $categoryData['mustahik']->count() }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($categoryData['total_amount'] > 0)
                            <div class="fw-bold">Rp {{ number_format($categoryData['total_amount'], 0, ',', '.') }}</div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($averagePerDistribution > 0)
                            <small class="text-success">Rp {{ number_format($averagePerDistribution, 0, ',', '.') }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($percentage > 0)
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>
                                <small class="fw-semibold">{{ number_format($percentage, 1) }}%</small>
                            </div>
                            @else
                            <span class="text-muted">0%</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr class="fw-bold">
                        <td colspan="2">TOTAL</td>
                        <td class="text-center">{{ number_format($distributions->sum('count')) }}</td>
                        <td class="text-center">{{ $distributions->sum(function($group) { return $group['mustahik']->count(); }) }}</td>
                        <td class="text-end">Rp {{ number_format($distributions->sum('total_amount'), 0, ',', '.') }}</td>
                        <td class="text-center">-</td>
                        <td class="text-center">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Tidak Ada Data Distribusi</h5>
            <p class="text-muted">Belum ada distribusi zakat yang tercatat untuk tahun {{ $year }}</p>
            <a href="{{ route('distributions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Distribusi Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Print-specific styles -->
<style media="print">
    .btn, .card-header .btn, .no-print {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    @page {
        margin: 1cm;
    }
    
    body {
        font-size: 12px;
    }
    
    h2 {
        font-size: 18px;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
        font-size: 11px;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for charts
    const categories = @json($categories);
    const distributions = @json($distributions);
    
    const labels = [];
    const values = [];
    const colors = [
        '#0d6efd', '#6f42c1', '#d63384', '#dc3545',
        '#fd7e14', '#ffc107', '#c2410c', '#20c997'
    ];
    
    Object.keys(categories).forEach((key, index) => {
        const data = distributions[key] || { total_amount: 0, count: 0 };
        if (data.total_amount > 0) {
            labels.push(key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' '));
            values.push(data.total_amount);
        }
    });

    // Distribution Bar Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Distribusi (Rp)',
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
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

    // Percentage Pie Chart
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
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
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