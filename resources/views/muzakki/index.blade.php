@extends('layouts.app')

@section('page-title', 'Manajemen Muzakki')

@section('content')
    <div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Data Muzakki</h2>
            <p class="text-sm" style="color: #8b7e74;">Kelola data muzakki yang terdaftar dalam sistem</p>
        </div>
        <a href="{{ route('muzakki.create') }}"
            class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Muzakki
        </a>
    </div>

    
    <div class="rounded-2xl mb-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid #f0ece6;">
            <div class="flex items-center gap-2">
                <i class="bi bi-funnel-fill text-xs" style="color: #c2410c;"></i>
                <span class="text-xs font-bold uppercase tracking-wider" style="color: #1c0f0a;">Filter Data Muzakki</span>
            </div>
        </div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" id="search-input"
                        class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                        placeholder="Cari nama, email, NIK..." value="{{ request('search') }}">
                </div>
                <div class="w-full sm:w-[180px]">
                    <x-custom-select 
                        id="occupation-filter" 
                        name="occupation" 
                        placeholder="Semua Pekerjaan" 
                        :selected="request('occupation', '')" 
                        :options="collect($occupations)->mapWithKeys(fn($item) => [$item => ucwords(str_replace('_', ' ', $item))])->toArray()" />
                </div>
                <div class="w-full sm:w-[150px]">
                    <input type="text" id="city-filter"
                        class="w-full h-11 px-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                        placeholder="Kota" value="{{ request('city') }}">
                </div>
                <div class="w-full sm:w-[180px]">
                    <x-custom-select 
                        id="status-filter" 
                        name="status" 
                        placeholder="Semua Status" 
                        :selected="request('status', '')" 
                        :options="['active' => 'Aktif', 'inactive' => 'Tidak Aktif']" />
                </div>
                <div class="flex items-center gap-2">
                    <div id="search-loading" class="w-6 h-6 flex items-center justify-center opacity-0 transition-opacity duration-150">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-t-transparent" style="border-color: #c2410c; border-t-color: transparent;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total muzakki</p>
            <h4 class="text-2xl font-bold mb-0" id="total-count" style="color: #1c0f0a;">{{ $muzakki->total() }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Aktif & terverifikasi</p>
            <h4 class="text-2xl font-bold mb-0" id="active-count" style="color: #1c0f0a;">{{ $muzakki->where('is_active', true)->count() }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Menunggu verifikasi</p>
            <h4 class="text-2xl font-bold mb-0" id="inactive-count" style="color: #1c0f0a;">{{ $muzakki->where('is_active', false)->count() }}</h4>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Baru bulan ini</p>
            <h4 class="text-2xl font-bold mb-0" id="thismonth-count" style="color: #1c0f0a;">{{ $muzakki->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
        </div>
    </div>

    
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="p-0" id="muzakki-table-container">
            @include('muzakki.partials.table')
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimeout;
            let currentPage = 1;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Debounced search function
            function performSearch(page = 1) {
                const searchUrl = "{{ route('api.muzakki.search') }}";
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const searchData = {
                    search: document.getElementById('search-input').value,
                    occupation: document.getElementById('occupation-filter').value,
                    city: document.getElementById('city-filter').value,
                    status: document.getElementById('status-filter').value,
                    page: page
                };

                // Show loading indicator
                document.getElementById('search-loading').classList.remove('opacity-0');

                // Create query string
                const params = new URLSearchParams(searchData);

                // Fetch data from Laravel route
                fetch(`${searchUrl}?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            // Update table
                            updateTable(response.data.muzakki, response.data.pagination);
                            // Update statistics
                            updateStatistics(response.data.statistics);
                            // Update current page
                            currentPage = response.data.pagination.current_page;
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    })
                    .finally(() => {
                        // Hide loading indicator
                        document.getElementById('search-loading').classList.add('opacity-0');
                    });
            }

            // Update table with new data
            function updateTable(muzakki, pagination) {
                let tableHtml = '';

                if (muzakki.length > 0) {
                    tableHtml = `
                <div>
                    <table id="table-muzakki" class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 14%;">Nama</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 14%;">NIK</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Kategori</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Telepon</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Kota</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Status Verifikasi</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Terdaftar</th>
                                <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

                    muzakki.forEach(function(item) {
                        const date = new Date(item.created_at);
                        const day = String(date.getDate()).padStart(2, '0');
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep',
                            'Okt', 'Nov', 'Des'
                        ];
                        const month = monthNames[date.getMonth()];
                        const year = date.getFullYear();
                        const createdAt = `${day} ${month} ${year}`;

                        const rowIndex = muzakki.indexOf(item);
                        const isEven = rowIndex % 2 === 1;
                        tableHtml += `
                    <tr class="hover:bg-gray-50 transition-colors ${isEven ? 'bg-gray-50' : 'bg-white'}">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="bg-orange-100 rounded-full p-1.5 mr-2 flex-shrink-0">
                                    <i class="fas fa-user text-orange-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">${item.name}</div>
                                    ${item.nik ? '<small class="text-gray-500 text-xs">NIK: ' + item.nik + '</small>' : ''}
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-3">
                            ${item.occupation ?
                                '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">' + item.occupation.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) + '</span>' :
                                '<span class="text-gray-400 text-xs">-</span>'
                            }
                        </td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">${item.phone || '-'}</td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">${item.city || '-'}</td>
                        <td class="px-2 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${item.is_active ? 'bg-orange-600 text-white' : 'bg-yellow-500 text-white'} whitespace-nowrap">
                                ${item.is_active ? 'Terverifikasi' : 'Menunggu'}
                            </span>
                        </td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">${createdAt}</td>
                        <td class="px-2 py-3">
                            <div class="flex items-center justify-start xl:justify-center gap-1.5">
                                <a href="/muzakki/${item.id}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="/muzakki/${item.id}/edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="/muzakki/${item.id}/toggle-status" method="POST" class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Toggle Status">
                                        <i class="bi bi-toggle-${item.is_active ? 'on' : 'off'}"></i>
                                    </button>
                                </form>
                                ${item.payments_count == 0 ? `
                                <form action="/muzakki/${item.id}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>` : ''}
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
                                <ul class="flex items-center space-x-1">
                `;

                        if (pagination.current_page > 1) {
                            tableHtml +=
                                '<li><a class="pagination-link px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer" href="#" data-page="' +
                                (pagination.current_page - 1) + '">‹</a></li>';
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            const isActive = pagination.current_page == i;
                            tableHtml += '<li><a class="pagination-link px-3 py-2 text-sm font-medium ' +
                                (isActive ? 'text-white bg-blue-600 border border-blue-600' :
                                    'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50') +
                                ' rounded-lg transition-colors cursor-pointer" href="#" data-page="' + i + '">' +
                                i + '</a></li>';
                        }

                        if (pagination.current_page < pagination.last_page) {
                            tableHtml +=
                                '<li><a class="pagination-link px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer" href="#" data-page="' +
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
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-400 mb-4 block"></i>
                    <h5 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada data muzakki</h5>
                    <p class="text-gray-500 mb-4">Tidak ada muzakki yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="px-4 py-2 border border-blue-600 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors">
                        <i class="fas fa-redo mr-2"></i> Reset Pencarian
                    </button>
                </div>
            `;
                }

                document.getElementById('muzakki-table-container').innerHTML = tableHtml;

                // Re-init DataTables Responsive after AJAX DOM rebuild
                if (window.SipzisTable) {
                    window.SipzisTable.initTable('#table-muzakki');
                }
            }

            // Update statistics
            function updateStatistics(stats) {
                document.getElementById('total-count').textContent = stats.total.toLocaleString();
                document.getElementById('active-count').textContent = stats.active.toLocaleString();
                document.getElementById('inactive-count').textContent = stats.inactive.toLocaleString();
                document.getElementById('thismonth-count').textContent = stats.this_month.toLocaleString();
            }

            // Search input with debouncing
            document.getElementById('search-input').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                }, 500); // 500ms delay
            });

            // Filter changes
            document.getElementById('occupation-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('status-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('city-filter').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                }, 500);
            });

            // Reset filters
            document.getElementById('reset-filters').addEventListener('click', function() {
                document.getElementById('search-input').value = '';
                document.getElementById('occupation-filter').value = '';
                document.getElementById('city-filter').value = '';
                document.getElementById('status-filter').value = '';
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
                    document.getElementById('occupation-filter').value = '';
                    document.getElementById('city-filter').value = '';
                    document.getElementById('status-filter').value = '';
                    performSearch(1);
                }
            });
        });
    </script>
@endpush
