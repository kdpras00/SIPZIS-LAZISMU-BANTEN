@extends('layouts.app')

@section('page-title', 'Manajemen Mustahik')

@section('content')
    <div class="px-6 py-5" style="max-width: 1280px;">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Data Mustahik</h2>
            <p class="text-sm" style="color: #8b7e74;">Kelola data penerima manfaat zakat</p>
        </div>
        <a href="{{ route('mustahik.create') }}"
            class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors duration-200 text-sm" style="background: #c2410c;">
            <i class="bi bi-plus-circle mr-2"></i> Tambah Mustahik
        </a>
    </div>

    <div class="rounded-2xl mb-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="p-4">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" id="search-input" class="w-full px-3 py-2 rounded-lg text-sm" style="border: 1px solid #e8e0d6;" placeholder="Cari nama, NIK, telepon..." value="{{ request('search') }}">
                </div>
                <div class="w-[180px]">
                    <select id="category-filter" class="w-full px-3 py-2 rounded-lg text-sm" style="border: 1px solid #e8e0d6; background: #fff;">
                        <option value="">Semua Kategori</option>
                        @foreach (\App\Models\Mustahik::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-[150px]">
                    <input type="text" id="city-filter" class="w-full px-3 py-2 rounded-lg text-sm" style="border: 1px solid #e8e0d6;" placeholder="Kota" value="{{ request('city') }}">
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="reset-filters" class="inline-flex items-center px-4 py-2 font-medium rounded-lg text-sm" style="border: 1px solid #e8e0d6; color: #8b7e74; background: #fff;">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Reset
                    </button>
                    <div id="search-loading" class="hidden">
                        <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2" style="border-color: #c2410c;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #fff7ed;">
                <i class="bi bi-person-hearts" style="color: #c2410c;"></i>
            </div>
            <h4 class="text-2xl font-bold mb-0" id="total-count" style="color: #1c0f0a;">{{ $mustahik->total() }}</h4>
            <small class="text-xs" style="color: #8b7e74;">Total penerima manfaat</small>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: #eff6ff;">
                <i class="bi bi-person-plus" style="color: #0369a1;"></i>
            </div>
            <h4 class="text-2xl font-bold mb-0" id="thismonth-count" style="color: #1c0f0a;">{{ $mustahik->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
            <small class="text-xs" style="color: #8b7e74;">Baru bulan ini</small>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="px-5 py-4" style="border-bottom: 1px solid #f0ece6;">
            <h5 class="text-base font-bold mb-0" style="color: #1c0f0a;">Daftar Mustahik</h5>
        </div>
        <div class="p-0" id="mustahik-table-container">
            @include('mustahik.partials.table')
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
                const searchData = {
                    search: document.getElementById('search-input').value,
                    category: document.getElementById('category-filter').value,
                    city: document.getElementById('city-filter').value,
                    page: page
                };

                // Show loading indicator
                document.getElementById('search-loading').classList.remove('hidden');

                // Create query string
                const params = new URLSearchParams(searchData);

                fetch('{{ route('api.mustahik.search') }}?' + params.toString(), {
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
                            updateTable(response.data.mustahik, response.data.pagination);
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
                        document.getElementById('search-loading').classList.add('hidden');
                    });
            }

            // Update table with new data
            function updateTable(mustahik, pagination) {
                let tableHtml = '';

                if (mustahik.length > 0) {
                    tableHtml = `
                <div>
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Telepon</th>
                                <th scope="col" class="px-6 py-3">Kota</th>
                                <th scope="col" class="px-6 py-3">Terdaftar</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    mustahik.forEach(function(item) {
                        const createdAt = new Date(item.created_at).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        // Get category display name
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
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-orange-100 rounded-full p-2 mr-3">
                                    <i class="bi bi-person-heart text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">${item.name}</div>
                                    ${item.nik ? '<small class="text-gray-500">NIK: ' + item.nik + '</small>' : ''}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${categoryMap[item.category] || item.category}</span>
                        </td>
                        <td class="px-6 py-4">${item.phone || '-'}</td>
                        <td class="px-6 py-4">${item.city || '-'}</td>
                        <td class="px-6 py-4">${createdAt}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <a href="/mustahik/${item.id}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/mustahik/${item.id}/edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/mustahik/${item.id}/toggle-status" method="POST" class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Toggle Status">
                                        <i class="bi bi-toggle-${item.is_active ? 'on' : 'off'}"></i>
                                    </button>
                                </form>
                                ${item.zakat_distributions_count == 0 ? `
                                <form action="/mustahik/${item.id}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="bi bi-trash"></i>
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
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-gray-500">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="inline-flex items-center -space-x-px">
                `;

                        if (pagination.current_page > 1) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page - 1) + '">‹</a></li>';
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            const isActive = pagination.current_page == i;
                            tableHtml += '<li><a href="#" class="pagination-link px-3 py-2 leading-tight ' + (
                                isActive ?
                                'text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700' :
                                'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'
                            ) + '" data-page="' + i + '">' + i + '</a></li>';
                        }

                        if (pagination.current_page < pagination.last_page) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
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
                    <i class="bi bi-inbox text-6xl text-gray-400 mb-4 block"></i>
                    <h5 class="text-lg font-medium text-gray-500 mb-2">Tidak ada data mustahik</h5>
                    <p class="text-sm text-gray-400 mb-4">Tidak ada mustahik yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 bg-white hover:bg-blue-50 font-medium rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Reset Pencarian
                    </button>
                </div>
            `;
                }

                document.getElementById('mustahik-table-container').innerHTML = tableHtml;
            }

            // Update statistics
            function updateStatistics(stats) {
                document.getElementById('total-count').textContent = stats.total.toLocaleString();
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
            document.getElementById('category-filter').addEventListener('change', function() {
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
                document.getElementById('category-filter').value = '';
                document.getElementById('city-filter').value = '';
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
                    document.getElementById('city-filter').value = '';
                    performSearch(1);
                }
            });
        });
    </script>
@endpush
