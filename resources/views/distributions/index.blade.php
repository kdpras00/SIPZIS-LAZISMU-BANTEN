@extends('layouts.app')

@section('page-title', 'Manajemen Distribusi ZIS')

@section('content')
<div class="px-6 py-5" style="max-width: 1280px;">
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Data Distribusi ZIS</h2>
        <p class="text-sm" style="color: #8b7e74;">Kelola penyaluran zakat kepada mustahik</p>
    </div>
    <a href="{{ route('distributions.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors text-sm" style="background: #c2410c;">
        <i class="bi bi-plus-circle mr-2"></i> Tambah Distribusi
    </a>
</div>

<div class="rounded-2xl mb-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
    <div class="px-5 py-3 flex items-center justify-between" style="border-bottom: 1px solid #f0ece6;">
        <div class="flex items-center gap-2">
            <i class="bi bi-funnel text-sm" style="color: #8b7e74;"></i>
            <span class="text-sm font-semibold" style="color: #1c0f0a;">Filter</span>
        </div>
        <button type="button" id="reset-filters" class="text-xs font-medium px-3 py-1.5 rounded-lg" style="color: #c2410c; border: 1px solid #f0ece6; background: #fff;">Reset</button>
    </div>
    
    <div class="p-6">
        <!-- Search Input -->
        <div class="mb-5">
            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Pencarian Cepat
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="search-input"
                    class="w-full pl-12 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-gray-50 hover:bg-white"
                    placeholder="Ketik kode distribusi, nama program, atau nama mustahik..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filters Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Kategori Mustahik
                </label>
                <select id="category-filter"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="distribution-type-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Jenis Distribusi
                </label>
                <select id="distribution-type-filter"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400">
                    <option value="">Semua Jenis</option>
                    <option value="cash" {{ request('distribution_type') == 'cash' ? 'selected' : '' }}>💵 Tunai</option>
                    <option value="goods" {{ request('distribution_type') == 'goods' ? 'selected' : '' }}>📦 Barang</option>
                    <option value="voucher" {{ request('distribution_type') == 'voucher' ? 'selected' : '' }}>🎫 Voucher</option>
                    <option value="service" {{ request('distribution_type') == 'service' ? 'selected' : '' }}>🔧 Layanan</option>
                </select>
            </div>
            
            <div>
                <label for="program-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Program
                </label>
                <input type="text" id="program-filter"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400"
                    placeholder="Nama program..." value="{{ request('program') }}">
            </div>
            
            <div>
                <label for="received-status-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Penerimaan
                </label>
                <select id="received-status-filter"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400">
                    <option value="">Semua Status</option>
                    <option value="received" {{ request('received_status') == 'received' ? 'selected' : '' }}>✅ Sudah Diterima</option>
                    <option value="pending" {{ request('received_status') == 'pending' ? 'selected' : '' }}>⏳ Belum Diterima</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Rentang Tanggal
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="date" id="date-from"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400"
                        value="{{ request('date_from') }}" placeholder="Dari tanggal">
                    <input type="date" id="date-to"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all duration-200 hover:border-gray-400"
                        value="{{ request('date_to') }}" placeholder="Sampai tanggal">
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="search-loading" class="hidden mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-center gap-3 text-orange-600">
                <div class="inline-block animate-spin rounded-full h-5 w-5 border-3 border-orange-600 border-t-transparent"></div>
                <span class="text-sm font-medium">Memproses pencarian...</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #fff7ed;"><i class="bi bi-cash-stack" style="color: #c2410c;"></i></div>
        <h5 class="text-lg font-bold mb-0" id="total-amount" style="color: #1c0f0a;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h5>
        <small class="text-xs" style="color: #8b7e74;">Total distribusi</small>
    </div>
    <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #f0fdf4;"><i class="bi bi-people" style="color: #15803d;"></i></div>
        <h5 class="text-lg font-bold mb-0" id="total-count" style="color: #1c0f0a;">{{ number_format($stats['total_count']) }}</h5>
        <small class="text-xs" style="color: #8b7e74;">Total penerima</small>
    </div>
    <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #eff6ff;"><i class="bi bi-calendar-month" style="color: #0369a1;"></i></div>
        <h5 class="text-lg font-bold mb-0" id="thismonth-amount" style="color: #1c0f0a;">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</h5>
        <small class="text-xs" style="color: #8b7e74;">Bulan ini</small>
    </div>
    <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #fef3c7;"><i class="bi bi-clock" style="color: #b45309;"></i></div>
        <h5 class="text-lg font-bold mb-0" id="pending-count" style="color: #1c0f0a;">{{ $stats['pending_receipt'] }}</h5>
        <small class="text-xs" style="color: #8b7e74;">Belum diterima</small>
    </div>
    <div class="rounded-2xl p-5 col-span-2 lg:col-span-1" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: {{ $stats['available_balance'] > 0 ? '#fff7ed' : '#fef2f2' }};">
            <i class="bi bi-wallet2" style="color: {{ $stats['available_balance'] > 0 ? '#c2410c' : '#dc2626' }};"></i>
        </div>
        <h5 class="text-lg font-bold mb-0" id="available-balance" style="color: #1c0f0a;">Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}</h5>
        <small class="text-xs" style="color: #8b7e74;">Saldo tersedia</small>
    </div>
</div>

<div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
    <div class="px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
        <h5 class="text-base font-bold mb-0" style="color: #1c0f0a;">Daftar Distribusi</h5>
    </div>
    <div class="p-0" id="distributions-table-container">
        @include('distributions.partials.table')
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        let currentPage = 1;

        // Configuration from server
        const config = {
            apiRoute: "{{ route('api.distributions.search', [], false) }}",
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Get CSRF token
        const csrfToken = config.csrfToken;

        // Debounced search function
        function performSearch(page = 1) {
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const distributionTypeFilter = document.getElementById('distribution-type-filter');
            const programFilter = document.getElementById('program-filter');
            const receivedStatusFilter = document.getElementById('received-status-filter');
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');

            const searchData = {
                search: searchInput ? searchInput.value.trim() : '',
                category: categoryFilter ? categoryFilter.value : '',
                distribution_type: distributionTypeFilter ? distributionTypeFilter.value : '',
                program: programFilter ? programFilter.value.trim() : '',
                received_status: receivedStatusFilter ? receivedStatusFilter.value : '',
                date_from: dateFrom ? dateFrom.value : '',
                date_to: dateTo ? dateTo.value : '',
                page: page
            };

            // Remove empty values from searchData
            Object.keys(searchData).forEach(key => {
                if (searchData[key] === '' || searchData[key] === null) {
                    delete searchData[key];
                }
            });

            // Show loading indicator
            const loadingEl = document.getElementById('search-loading');
            if (loadingEl) {
                loadingEl.classList.remove('hidden');
            }

            // Create query string
            const params = new URLSearchParams(searchData);

            const apiRoute = config.apiRoute;

            fetch(apiRoute + '?' + params.toString(), {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    cache: 'no-store'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Permintaan pencarian gagal (${response.status})`);
                    }
                    return response.json();
                })
                .then(response => {
                    if (response.success) {
                        // Update table
                        updateTable(response.data.distributions, response.data.pagination);
                        // Update statistics
                        updateStatistics(response.data.statistics);
                        // Update current page
                        currentPage = response.data.pagination.current_page;
                    } else {
                        console.error('Search failed:', response);
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    alert('Terjadi kesalahan saat mencari data. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading indicator
                    const loadingEl = document.getElementById('search-loading');
                    if (loadingEl) {
                        loadingEl.classList.add('hidden');
                    }
                });
        }

        // Update table with new data
        function updateTable(distributions, pagination) {
            let tableHtml = '';

            if (distributions.length > 0) {
                tableHtml = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Distribusi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

                distributions.forEach(function(distribution) {
                    const distributionDate = new Date(distribution.distribution_date).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    // Distribution type display names
                    const distributionTypes = {
                        'cash': 'Tunai',
                        'goods': 'Barang',
                        'voucher': 'Voucher',
                        'service': 'Layanan'
                    };

                    // Distribution type colors
                    const typeColors = {
                        'cash': 'bg-orange-100 text-orange-800',
                        'goods': 'bg-cyan-100 text-cyan-800',
                        'voucher': 'bg-yellow-100 text-yellow-800',
                        'service': 'bg-blue-100 text-blue-800'
                    };

                    // Category display names
                    const categoryMap = {
                        'fakir': 'Fakir',
                        'miskin': 'Miskin',
                        'amil': 'Amil',
                        'muallaf': 'Muallaf',
                        'riqab': 'Riqab',
                        'gharim': 'Gharim',
                        'fisabilillah': 'Fi Sabilillah',
                        'ibnu_sabil': 'Ibnu Sabil'
                    };

                    tableHtml += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold">${distribution.distribution_code}</div>
                            ${distribution.location ? '<small class="text-gray-500">' + distribution.location + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold">${distribution.mustahik.name}</div>
                            <small class="text-gray-500">${categoryMap[distribution.mustahik.category] || distribution.mustahik.category}</small>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${distribution.program_name ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">' + distribution.program_name + '</span>' : '<span class="text-gray-500">-</span>'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${typeColors[distribution.distribution_type]}">${distributionTypes[distribution.distribution_type]}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold">Rp ${parseInt(distribution.amount).toLocaleString('id-ID')}</div>
                            ${distribution.goods_description ? '<small class="text-gray-500">' + distribution.goods_description + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${distribution.is_received ? 
                                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Sudah Diterima</span>' : 
                                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>'
                            }
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${distributionDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="/distributions/${distribution.id}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/distributions/${distribution.id}/receipt" 
                                   class="inline-flex items-center px-3 py-1.5 border border-orange-300 shadow-sm text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200" 
                                   title="Kwitansi" target="_blank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                                <a href="/distributions/${distribution.id}/edit" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200" 
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                ${!distribution.is_received ? `
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1.5 border border-yellow-300 shadow-sm text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200" 
                                        title="Tandai Diterima" 
                                        onclick="markAsReceived(${distribution.id}, '${distribution.mustahik.name.replace(/'/g, "\\'")}')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <form action="/distributions/${distribution.id}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus distribusi ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" 
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
                });

                tableHtml += `
                        </tbody>
                    </table>
                </div>
            `;

                // Add pagination if needed
                if (pagination.last_page > 1) {
                    tableHtml += `
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-600 text-sm">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="flex space-x-2">
                `;

                    if (pagination.current_page > 1) {
                        tableHtml += '<li><a class="px-3 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 pagination-link" href="#" data-page="' + (pagination.current_page - 1) + '">‹</a></li>';
                    }

                    for (let i = 1; i <= pagination.last_page; i++) {
                        const activeClass = pagination.current_page == i ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 hover:bg-gray-50';
                        tableHtml += '<li><a class="px-3 py-2 text-sm border rounded pagination-link ' + activeClass + '" href="#" data-page="' + i + '">' + i + '</a></li>';
                    }

                    if (pagination.current_page < pagination.last_page) {
                        tableHtml += '<li><a class="px-3 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 pagination-link" href="#" data-page="' + (pagination.current_page + 1) + '">›</a></li>';
                    }

                    tableHtml += `
                                </ul>
                            </nav>
                        </div>
                    </div>
                `;
                }
            } else {
                tableHtml = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h5 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data distribusi</h5>
                    <p class="text-gray-600 mb-4">Tidak ada distribusi yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Pencarian
                    </button>
                </div>
            `;
            }

            document.getElementById('distributions-table-container').innerHTML = tableHtml;
        }

        // Update statistics
        function updateStatistics(stats) {
            document.getElementById('total-amount').textContent = 'Rp ' + parseInt(stats.total_amount).toLocaleString('id-ID');
            document.getElementById('total-count').textContent = stats.total_count.toLocaleString('id-ID');
            document.getElementById('thismonth-amount').textContent = 'Rp ' + parseInt(stats.this_month).toLocaleString('id-ID');
            document.getElementById('pending-count').textContent = stats.pending_receipt.toLocaleString('id-ID');
            document.getElementById('available-balance').textContent = 'Rp ' + parseInt(stats.available_balance).toLocaleString('id-ID');

            // Update available balance color
            const balanceIcon = document.getElementById('available-balance').previousElementSibling;
            if (stats.available_balance > 0) {
                balanceIcon.className = balanceIcon.className.replace('text-red-600', 'text-orange-600');
            } else {
                balanceIcon.className = balanceIcon.className.replace('text-orange-600', 'text-red-600');
            }
        }

        // Search input with debouncing (reduced delay for better responsiveness)
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Show loading immediately
            document.getElementById('search-loading').classList.remove('hidden');
            searchTimeout = setTimeout(function() {
                performSearch(1);
            }, 300); // Reduced to 300ms for better responsiveness
        });

        // Filter changes
        document.getElementById('category-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('distribution-type-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('program-filter').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Show loading immediately
            document.getElementById('search-loading').classList.remove('hidden');
            searchTimeout = setTimeout(function() {
                performSearch(1);
            }, 300); // Reduced to 300ms for better responsiveness
        });

        document.getElementById('received-status-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('date-from').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('date-to').addEventListener('change', function() {
            performSearch(1);
        });

        // Reset filters
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('search-input').value = '';
            document.getElementById('category-filter').value = '';
            document.getElementById('distribution-type-filter').value = '';
            document.getElementById('program-filter').value = '';
            document.getElementById('received-status-filter').value = '';
            document.getElementById('date-from').value = '';
            document.getElementById('date-to').value = '';
            performSearch(1);
        });

        // Pagination click handler (using event delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('pagination-link')) {
                e.preventDefault();
                const page = e.target.dataset.page;
                performSearch(page);
            }

            // Clear search button (using event delegation)
            if (e.target.id === 'clear-search' || e.target.closest('#clear-search')) {
                document.getElementById('search-input').value = '';
                document.getElementById('category-filter').value = '';
                document.getElementById('distribution-type-filter').value = '';
                document.getElementById('program-filter').value = '';
                document.getElementById('received-status-filter').value = '';
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                performSearch(1);
            }
        });
    });

    // Mark as received function
    function markAsReceived(distributionId, mustahikName) {
        if (confirm(`Tandai distribusi untuk ${mustahikName} sebagai sudah diterima?`)) {
            // Create a form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/distributions/${distributionId}/mark-received`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PATCH">
        `;

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
    