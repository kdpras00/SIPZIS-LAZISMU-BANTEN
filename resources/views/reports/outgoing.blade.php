@extends('layouts.app')

@section('content')
<div class="px-6 py-5" style="max-width: 1280px;">
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Laporan Keluar</h2>
        <p class="text-sm" style="color: #8b7e74;">Ringkasan data distribusi zakat yang telah disalurkan</p>
    </div>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <i class="fas fa-filter me-1 mr-2"></i>
            Filter Data
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.outgoing') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div>
                        <label for="distribution_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Distribusi</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="distribution_type" name="distribution_type">
                            <option value="">Semua Jenis</option>
                            <option value="cash" {{ request('distribution_type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="goods" {{ request('distribution_type') == 'goods' ? 'selected' : '' }}>Barang</option>
                            <option value="voucher" {{ request('distribution_type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                            <option value="service" {{ request('distribution_type') == 'service' ? 'selected' : '' }}>Layanan</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Mustahik</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="category" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ \App\Models\Mustahik::CATEGORIES[$category] ?? $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari (Kode Distribusi, Nama Mustahik)</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="search" name="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-search me-1 mr-2"></i> Filter
                            </button>
                        <a href="{{ route('reports.outgoing') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-sync me-1 mr-2"></i> Reset
                            </a>
                            <!-- Export Buttons -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                <i class="fas fa-download me-1 mr-2"></i> Export
                            </button>
                            <ul x-show="open" x-transition style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <li>
                                    <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="exportReport('pdf')">
                                        PDF
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="exportReport('excel')">
                                        Excel (CSV)
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #fff7ed;"><i class="fas fa-donate" style="color: #c2410c;"></i></div>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ number_format($stats['total_count'], 0, ',', '.') }}</p>
            <small class="text-xs" style="color: #8b7e74;">Total distribusi</small>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #f0fdf4;"><i class="fas fa-money-bill-wave" style="color: #15803d;"></i></div>
            <p class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
            <small class="text-xs" style="color: #8b7e74;">Total nominal keluar</small>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #eff6ff;"><i class="fas fa-calendar-alt" style="color: #0369a1;"></i></div>
            <p class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</p>
            <small class="text-xs" style="color: #8b7e74;">Bulan ini</small>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #fef3c7;"><i class="fas fa-clock" style="color: #b45309;"></i></div>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ number_format($stats['pending_receipt'], 0, ',', '.') }}</p>
            <small class="text-xs" style="color: #8b7e74;">Belum diterima</small>
        </div>
        <div class="rounded-2xl p-5 col-span-2 lg:col-span-1" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: {{ $stats['available_balance'] > 0 ? '#fff7ed' : '#fef2f2' }};">
                <i class="fas fa-wallet" style="color: {{ $stats['available_balance'] > 0 ? '#c2410c' : '#dc2626' }};"></i>
            </div>
            <p class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}</p>
            <small class="text-xs" style="color: #8b7e74;">Saldo tersedia</small>
        </div>
    </div>

    <!-- Data Table -->
    <div class="rounded-2xl overflow-hidden mb-6" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
            <h5 class="text-base font-bold mb-0" style="color: #1c0f0a;">Data Distribusi Zakat</h5>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Kode Distribusi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Nama Mustahik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Jenis Distribusi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($distributions as $distribution)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $loop->iteration + ($distributions->currentPage() - 1) * $distributions->perPage() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->distribution_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->mustahik->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ \App\Models\Mustahik::CATEGORIES[$distribution->mustahik->category] ?? $distribution->mustahik->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">
                                    @switch($distribution->distribution_type)
                                        @case('cash')
                                            Tunai
                                            @break
                                        @case('goods')
                                            Barang
                                            @break
                                        @case('voucher')
                                            Voucher
                                            @break
                                        @case('service')
                                            Layanan
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->distribution_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">
                                    @if($distribution->is_received)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Sudah Diterima</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 border border-gray-300">Tidak ada data distribusi zakat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-gray-600">
                    Menampilkan {{ $distributions->count() }} dari {{ $distributions->total() }} data
                </div>
                <div>
                    {{ $distributions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportReport(format) {
    // Get current form data
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Build query string
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Add export parameter
    params.append('export', format);
    
    // Redirect to export URL
    window.location.href = "{{ route('reports.outgoing') }}?" + params.toString();
}

    // Bootstrap dropdown initialization removed in favor of Alpine.js
</script>
@endpush
@endsection
