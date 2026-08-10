@if($mustahik->count() > 0)
<div class="overflow-x-auto">
    <table id="table-mustahik" class="min-w-full divide-y divide-[#f0ece6]">
        <thead style="background: #faf8f5;">
            <tr>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Nama</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">NIK</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Telepon</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kota</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Terdaftar</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#f0ece6]">
            @foreach($mustahik as $item)
            <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-xs font-bold truncate" style="color: #1c0f0a;">{{ $item->name }}</div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-xs font-medium" style="color: #1c0f0a;">
                    {{ $item->nik ?: "-" }}
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</span>
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-xs font-medium" style="color: #1c0f0a;">{{ $item->phone ?: '-' }}</td>
                <td class="px-5 py-4 whitespace-nowrap text-xs font-medium" style="color: #1c0f0a;">{{ $item->city ?: '-' }}</td>
                <td class="px-5 py-4 whitespace-nowrap text-xs" style="color: #8b7e74;">{{ $item->created_at->format('d M Y') }}</td>
                <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                    <div class="flex items-center justify-start xl:justify-center gap-1.5">
                        <a href="{{ route('mustahik.show', $item) }}" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;" title="Lihat Detail">
                            <i class="bi bi-eye text-xs"></i>
                        </a>
                        <a href="{{ route('mustahik.edit', $item) }}" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;" title="Edit">
                            <i class="bi bi-pencil text-xs"></i>
                        </a>
                        <form action="{{ route('mustahik.toggle-status', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors border border-[#ffedd5]" style="background: #fff7ed; color: #c2410c;" title="Toggle Status">
                                <i class="bi bi-toggle-{{ $item->is_active ? 'on' : 'off' }} text-xs"></i>
                            </button>
                        </form>
                        @if ($item->distributions_count == 0)
                            <form action="{{ route('mustahik.destroy', $item->id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors"
                                    title="Hapus">
                                    <i class="bi bi-trash text-xs"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(isset($pagination))
<div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-xs" style="color: #8b7e74;">
            Menampilkan {{ $pagination['from'] ?? 1 }} sampai {{ $pagination['to'] ?? count($mustahik) }} dari {{ $pagination['total'] ?? count($mustahik) }} data
        </div>
        @if($pagination['last_page'] > 1)
        <nav>
            <ul class="inline-flex items-center gap-1">
                @if($pagination['current_page'] > 1)
                    <li>
                        <a href="#" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#f0ece6]" style="background: #fff; color: #1c0f0a;" data-page="{{ $pagination['current_page'] - 1 }}">‹</a>
                    </li>
                @endif
                
                @for($i = 1; $i <= $pagination['last_page']; $i++)
                    <li>
                        <a href="#" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $pagination['current_page'] == $i ? 'text-white' : 'border border-[#f0ece6]' }}" style="{{ $pagination['current_page'] == $i ? 'background: #c2410c;' : 'background: #fff; color: #1c0f0a;' }}" data-page="{{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                
                @if($pagination['current_page'] < $pagination['last_page'])
                    <li>
                        <a href="#" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#f0ece6]" style="background: #fff; color: #1c0f0a;" data-page="{{ $pagination['current_page'] + 1 }}">›</a>
                    </li>
                @endif
            </ul>
        </nav>
        @endif
    </div>
</div>
@elseif($mustahik->hasPages())
<div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
    {{ $mustahik->withQueryString()->links() }}
</div>
@endif

@else
<div class="text-center py-12 px-6">
    <i class="bi bi-people text-4xl mb-2 block" style="color: #d1cbc4;"></i>
    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Tidak ada data mustahik</p>
    <p class="text-xs mt-1 mb-4" style="color: #8b7e74;">Belum ada mustahik yang terdaftar dalam sistem atau sesuai kriteria pencarian</p>
    <a href="{{ route('mustahik.create') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors text-xs shadow-xs" style="background: #c2410c;">
        <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Mustahik Pertama
    </a>
</div>
@endif