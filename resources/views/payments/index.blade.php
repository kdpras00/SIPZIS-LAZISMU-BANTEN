@extends('layouts.app')

@section('page-title', 'Manajemen Pembayaran Zakat')

@section('content')
    <div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">
                @if (auth()->user()->role === 'muzakki')
                    Riwayat Donasi
                @else
                    Data Pembayaran ZIS
                @endif
            </h2>
            <p class="text-sm" style="color: #8b7e74;">
                @if (auth()->user()->role === 'muzakki')
                    Lihat riwayat pembayaran donasi Anda
                @else
                    Kelola data pembayaran zakat, infaq, dan sedekah
                @endif
            </p>
        </div>
        <!-- <div>
                                                        @if (auth()->user()->role !== 'muzakki')
    <a href="{{ route('payments.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                                            <i class="bi bi-plus-circle-fill ></i> Tambah Pembayaran
                                                        </a>
@else
    <a href="{{ route('muzakki.payments.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                                            <i class="bi bi-plus-circle-fill ></i> Bayar Zakat
                                                        </a>
    @endif
                                                    </div> -->
    </div>

    <!-- Filter Section -->
    <div class="rounded-2xl mb-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid #f0ece6;">
            <div class="flex items-center gap-2">
                <i class="bi bi-funnel-fill text-xs" style="color: #c2410c;"></i>
                <span class="text-xs font-bold uppercase tracking-wider" style="color: #1c0f0a;">Filter Data Pembayaran ZIS</span>
            </div>
        </div>
        
        <div class="p-5 sm:p-6">
            <!-- Search Input -->
            <div class="mb-5">
                <label for="search-input" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">
                    <i class="bi bi-search mr-1" style="color: #8b7e74;"></i> Pencarian Cepat
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-[#8b7e74]">
                        <i class="bi bi-search text-xs"></i>
                    </span>
                    <input type="text" id="search-input"
                        class="w-full h-11 pl-10 pr-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                        placeholder="Ketik kode transaksi, nama muzakki, atau email..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="zakat-type-filter" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-tag-fill mr-1 text-[#c2410c]"></i>
                        Jenis Zakat
                    </label>
                    @php
                        $zakatOptions = ['' => 'Semua Jenis'];
                        foreach ($zakatTypes as $type) {
                            $zakatOptions[$type->id] = $type->name;
                        }
                    @endphp
                    <x-custom-select 
                        id="zakat-type-filter"
                        name="zakat_type"
                        :options="$zakatOptions"
                        :selected="request('zakat_type', '')"
                        placeholder="Semua Jenis"
                    />
                </div>
                
                <div>
                    <label for="payment-method-filter" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-credit-card-2-front-fill mr-1 text-[#c2410c]"></i>
                        Metode Pembayaran
                    </label>
                    <x-custom-select 
                        id="payment-method-filter"
                        name="payment_method"
                        :options="[
                            '' => 'Semua Metode',
                            'cash' => '💵 Tunai',
                            'transfer' => '🏦 Transfer Bank',
                            'check' => '📝 Cek',
                            'online' => '🌐 Online',
                            'midtrans' => '💳 Midtrans'
                        ]"
                        :selected="request('payment_method', '')"
                        placeholder="Semua Metode"
                    />
                </div>
                
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-[#c2410c] bi-check-circle-fill mr-1 text-[#c2410c]"></i>
                        Status
                    </label>
                    <x-custom-select 
                        id="status-filter"
                        name="status"
                        :options="[
                            '' => 'Semua Status',
                            'pending' => '⏳ Menunggu',
                            'completed' => '✅ Selesai',
                            'cancelled' => '❌ Dibatalkan'
                        ]"
                        :selected="request('status', '')"
                        placeholder="Semua Status"
                    />
                </div>
                
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Rentang Tanggal
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <x-custom-date-picker
                            id="date-from"
                            name="date_from"
                            :value="request('date_from')"
                            placeholder="Dari tanggal"
                            onChange="performSearch(1)"
                        />
                        <x-custom-date-picker
                            id="date-to"
                            name="date_to"
                            :value="request('date_to')"
                            placeholder="Sampai tanggal"
                            onChange="performSearch(1)"
                        />
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="search-loading" class="hidden mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-center gap-3 text-blue-600">
                    <div class="inline-block animate-spin rounded-full h-5 w-5 border-3 border-blue-600 border-t-transparent"></div>
                    <span class="text-sm font-medium">Memproses pencarian...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total terkumpul</p>
            <h4 class="text-xl font-bold mb-0" id="total-amount" style="color: #1c0f0a;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total transaksi</p>
            <h4 class="text-2xl font-bold mb-0" id="total-count" style="color: #1c0f0a;">{{ number_format($stats['total_count']) }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Bulan ini</p>
            <h4 class="text-xl font-bold mb-0" id="thismonth-amount" style="color: #1c0f0a;">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Menunggu verifikasi</p>
            <h4 class="text-2xl font-bold mb-0" id="pending-count" style="color: #1c0f0a;">{{ $stats['pending'] }}</h4>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="p-0" id="payments-table-container">
            @include('payments.partials.table')
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
                isNotMuzakki: {!! auth()->user()->role !== 'muzakki' ? 'true' : 'false' !!},
                apiRoute: '{!! url('/payments/search') !!}',
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Get CSRF token
            const csrfToken = config.csrfToken;

            // Debounced search function
            function performSearch(page = 1) {
                const searchInput = document.getElementById('search-input');
                const zakatTypeFilter = document.getElementById('zakat-type-filter');
                const paymentMethodFilter = document.getElementById('payment-method-filter');
                const statusFilter = document.getElementById('status-filter');
                const dateFrom = document.getElementById('date-from');
                const dateTo = document.getElementById('date-to');

                const searchData = {
                    search: searchInput ? searchInput.value.trim() : '',
                    zakat_type: zakatTypeFilter ? zakatTypeFilter.value : '',
                    payment_method: paymentMethodFilter ? paymentMethodFilter.value : '',
                    status: statusFilter ? statusFilter.value : '',
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
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(response => {
                        if (response.success) {
                            // Update table
                            updateTable(response.data.payments, response.data.pagination);
                            // Update statistics
                            updateStatistics(response.data.statistics);
                            // Update current page
                            currentPage = response.data.pagination.current_page;
                        } else {
                            console.error('Search failed:', response);
                            alert('Pencarian gagal. Silakan coba lagi.');
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
            function updateTable(payments, pagination) {
                let tableHtml = '';

                if (payments.length > 0) {
                    tableHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Kode Pembayaran</th>
                                ${config.isNotMuzakki ? '<th scope="col" class="px-6 py-3">Muzakki</th>' : ''}
                                <th scope="col" class="px-6 py-3">Jumlah Bayar</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    payments.forEach(function(payment) {
                        // Safe date parsing
                        let paymentDate = '-';
                        try {
                            if (payment.payment_date) {
                                paymentDate = new Date(payment.payment_date).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                            }
                        } catch (e) {
                            console.error('Error parsing date:', e);
                            paymentDate = payment.payment_date || '-';
                        }

                        // Payment method display names
                        const paymentMethods = {
                            'cash': 'Tunai',
                            'transfer': 'Transfer',
                            'check': 'Cek',
                            'online': 'Online',
                            'midtrans': 'Midtrans'
                        };

                        // Status badge classes
                        const statusClasses = {
                            'pending': 'bg-yellow-100 text-yellow-800',
                            'completed': 'bg-orange-100 text-orange-800',
                            'cancelled': 'bg-red-100 text-red-800'
                        };

                        const statusTexts = {
                            'pending': 'Menunggu',
                            'completed': 'Selesai',
                            'cancelled': 'Dibatalkan'
                        };

                        const isNotMuzakki = config.isNotMuzakki;
                        const muzakkiCell = isNotMuzakki ? `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-semibold text-gray-900">${payment.muzakki ? payment.muzakki.name : 'N/A'}</div>
                        ${payment.muzakki && payment.muzakki.phone ? '<small class="text-gray-500">' + payment.muzakki.phone + '</small>' : ''}
                    </td>
                ` : '';

                        // Safe access to zakat_type
                        const zakatTypeName = payment.zakat_type && payment.zakat_type.name ? payment.zakat_type.name : 'Tidak Diketahui';

                        tableHtml += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">${payment.payment_code || 'N/A'}</div>
                            <small class="text-gray-500">${payment.receipt_number || '-'}</small>
                        </td>
                        ${muzakkiCell}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">Rp ${payment.paid_amount ? parseInt(payment.paid_amount).toLocaleString('id-ID') : '0'}</div>
                            ${payment.zakat_amount ? '<small class="text-gray-500">Zakat: Rp ' + parseInt(payment.zakat_amount).toLocaleString('id-ID') + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClasses[payment.status] || 'bg-gray-100 text-gray-800'}">
                                ${statusTexts[payment.status] || payment.status || 'Tidak Diketahui'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">${paymentDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="/payments/${payment.payment_code}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/payments/${payment.id}/receipt" 
                                   class="inline-flex items-center px-3 py-1.5 border border-orange-300 shadow-sm text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200" 
                                   title="Kwitansi" target="_blank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                                ${config.isNotMuzakki ? `
                                    <a href="/payments/${payment.id}/edit" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200" 
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                                                                </a>
                                                                                ${payment.status !== 'completed' ? `
                                    <form action="/payments/${payment.id}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
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
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="inline-flex items-center -space-x-px">
                `;

                        if (pagination.current_page > 1) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page - 1) + '">‹</a></li>';
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            const activeClass = pagination.current_page == i ?
                                'bg-blue-50 border-blue-300 text-blue-600 hover:bg-blue-100 hover:text-blue-700' :
                                'bg-white border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700';
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 leading-tight border ' +
                                activeClass + '" data-page="' + i + '">' + i + '</a></li>';
                        }

                        if (pagination.current_page < pagination.last_page) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page + 1) + '">›</a></li>';
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
                <div class="text-center py-12 px-6">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h5 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pembayaran</h5>
                    <p class="text-gray-600 mb-4">Tidak ada pembayaran yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Pencarian
                    </button>
                </div>
            `;
                }

                document.getElementById('payments-table-container').innerHTML = tableHtml;
            }

            // Update statistics
            function updateStatistics(stats) {
                document.getElementById('total-amount').textContent = 'Rp ' + parseInt(stats.total_amount)
                    .toLocaleString('id-ID');
                document.getElementById('total-count').textContent = stats.total_count.toLocaleString('id-ID');
                document.getElementById('thismonth-amount').textContent = 'Rp ' + parseInt(stats.this_month)
                    .toLocaleString('id-ID');
                document.getElementById('pending-count').textContent = stats.pending.toLocaleString('id-ID');
            }

            // Search input with debouncing (reduced delay for better responsiveness)
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    // Show loading immediately
                    const loadingEl = document.getElementById('search-loading');
                    if (loadingEl) {
                        loadingEl.classList.remove('hidden');
                    }
                    // Perform search after 300ms delay
                    searchTimeout = setTimeout(function() {
                        performSearch(1);
                    }, 300); // Reduced to 300ms for better responsiveness
                });

                // Also trigger search on keyup for immediate feedback (Enter key)
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        clearTimeout(searchTimeout);
                        performSearch(1);
                    }
                });

                // Trigger search on paste event
                searchInput.addEventListener('paste', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                    }, 300);
            });
            }

            // Filter changes
            document.getElementById('zakat-type-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('payment-method-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('status-filter').addEventListener('change', function() {
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
                // Clear all filter inputs
                document.getElementById('search-input').value = '';
                document.getElementById('zakat-type-filter').value = '';
                document.getElementById('payment-method-filter').value = '';
                document.getElementById('status-filter').value = '';
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                
                // Reset URL to remove query parameters
                const url = new URL(window.location);
                url.search = '';
                window.history.pushState({}, '', url);
                
                // Perform search with empty filters
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
                    // Clear all filter inputs
                    document.getElementById('search-input').value = '';
                    document.getElementById('zakat-type-filter').value = '';
                    document.getElementById('payment-method-filter').value = '';
                    document.getElementById('status-filter').value = '';
                    document.getElementById('date-from').value = '';
                    document.getElementById('date-to').value = '';
                    
                    // Reset URL to remove query parameters
                    const url = new URL(window.location);
                    url.search = '';
                    window.history.pushState({}, '', url);
                    
                    // Perform search with empty filters
                    performSearch(1);
                }
            });

            // Initialize: Check if we need to perform search on page load
            // If there are query parameters, use them; otherwise show all data
            const urlParams = new URLSearchParams(window.location.search);
            const hasSearchParams = urlParams.has('search') || 
                                   urlParams.has('zakat_type') || 
                                   urlParams.has('payment_method') || 
                                   urlParams.has('status') || 
                                   urlParams.has('date_from') || 
                                   urlParams.has('date_to');
            
            // If there are search parameters in URL, sync them with form and search
            if (hasSearchParams) {
                if (urlParams.has('search')) {
                    document.getElementById('search-input').value = urlParams.get('search');
                }
                if (urlParams.has('zakat_type')) {
                    document.getElementById('zakat-type-filter').value = urlParams.get('zakat_type');
                }
                if (urlParams.has('payment_method')) {
                    document.getElementById('payment-method-filter').value = urlParams.get('payment_method');
                }
                if (urlParams.has('status')) {
                    document.getElementById('status-filter').value = urlParams.get('status');
                }
                if (urlParams.has('date_from')) {
                    document.getElementById('date-from').value = urlParams.get('date_from');
                }
                if (urlParams.has('date_to')) {
                    document.getElementById('date-to').value = urlParams.get('date_to');
                }
                // Perform search with URL parameters
                performSearch(1);
            }
        });
    </script>
@endpush
