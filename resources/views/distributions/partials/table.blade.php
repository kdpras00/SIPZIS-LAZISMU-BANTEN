@if($distributions->count() > 0)
<div class="overflow-x-auto">
    <table id="table-distributions" class="min-w-full divide-y divide-[#f0ece6]">
        <thead style="background: #faf8f5;">
            <tr>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kode Distribusi</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Mustahik</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Program</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jenis</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jumlah</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Tanggal</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#f0ece6]">
            @foreach($distributions as $distribution)
            <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 border border-[#f0ece6] flex-shrink-0" style="background: #faf8f5;">
                            <i class="bi bi-box-seam text-sm" style="color: #c2410c;"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold" style="color: #1c0f0a;">{{ $distribution->distribution_code }}</div>
                            @if($distribution->location)
                            <div class="text-[11px]" style="color: #8b7e74;">{{ $distribution->location }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-xs font-bold" style="color: #1c0f0a;">{{ $distribution->mustahik->name }}</div>
                    <div class="text-[11px]" style="color: #8b7e74;">{{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}</div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if($distribution->program_name)
                    <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">{{ $distribution->program_name }}</span>
                    @else
                    <span class="text-xs" style="color: #8b7e74;">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-center">
                    @switch($distribution->distribution_type)
                        @case('cash')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #c2410c;">Tunai</span>
                            @break
                        @case('goods')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Barang</span>
                            @break
                        @case('voucher')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Voucher</span>
                            @break
                        @case('service')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Layanan</span>
                            @break
                        @default
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #8b7e74;">{{ ucwords($distribution->distribution_type) }}</span>
                    @endswitch
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-center">
                    <div class="text-xs font-bold text-[#c2410c]">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                    @if($distribution->goods_description)
                    <div class="text-[11px] mt-0.5" style="color: #8b7e74;">{{ Str::limit($distribution->goods_description, 30) }}</div>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-center">
                    @if($distribution->is_received)
                        <span class="inline-flex items-center text-xs font-semibold" style="color: #c2410c;">Sudah Diterima</span>
                        @if($distribution->received_date)
                        <div class="text-[11px] mt-0.5" style="color: #8b7e74;">{{ $distribution->received_date->format('d M Y') }}</div>
                        @endif
                    @else
                        <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Belum Diterima</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium" style="color: #1c0f0a;">{{ $distribution->distribution_date->format('d M Y') }}</td>
                <td class="px-5 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-start xl:justify-center gap-1.5">
                        <a href="{{ route('distributions.show', $distribution) }}" 
                           class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;"
                           title="Lihat Detail">
                            <i class="bi bi-eye text-xs"></i>
                        </a>
                        <a href="{{ route('distributions.receipt', $distribution) }}" 
                           class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors border border-[#ffedd5]" style="background: #fff7ed; color: #c2410c;"
                           title="Kwitansi" target="_blank">
                            <i class="bi bi-file-earmark-pdf text-xs"></i>
                        </a>
                        <a href="{{ route('distributions.edit', $distribution) }}" 
                           class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;"
                           title="Edit">
                            <i class="bi bi-pencil text-xs"></i>
                        </a>
                        @if(!$distribution->is_received)
                        <button type="button" 
                                class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors border border-[#ffedd5]" style="background: #fff7ed; color: #c2410c;"
                                title="Tandai Diterima" 
                                onclick="showMarkReceivedModal({{ $distribution->id }}, '{{ addslashes($distribution->mustahik->name) }}')">
                            <i class="bi bi-check2-circle text-xs"></i>
                        </button>
                        <form action="{{ route('distributions.destroy', $distribution) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus distribusi ini?')">
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

@if($distributions->hasPages())
<div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
    {{ $distributions->withQueryString()->links() }}
</div>
@endif

@else
<div class="text-center py-12 px-6">
    <i class="bi bi-box-seam text-4xl mb-2 block" style="color: #d1cbc4;"></i>
    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Tidak ada data distribusi</p>
    <p class="text-xs mt-1 mb-4" style="color: #8b7e74;">Belum ada distribusi zakat yang tercatat dalam sistem atau sesuai kriteria pencarian</p>
    <a href="{{ route('distributions.create') }}" 
       class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors text-xs shadow-xs" style="background: #c2410c;">
        <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Distribusi Pertama
    </a>
</div>
@endif

<div class="fixed inset-0 bg-black/40 backdrop-blur-xs hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center" id="markReceivedModal">
    <div class="relative p-6 border border-[#f0ece6] w-full max-w-md shadow-2xl rounded-2xl bg-white m-4">
        <div class="flex justify-between items-center pb-3 border-b border-[#f0ece6]">
            <h5 class="text-sm font-bold" style="color: #1c0f0a;">Tandai Sebagai Diterima</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeMarkReceivedModal()">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <form id="markReceivedForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mt-4">
                <p class="text-xs mb-4" style="color: #1c0f0a;">Konfirmasi bahwa distribusi untuk <strong id="mustahikNameModal" class="text-[#c2410c]"></strong> telah diterima?</p>
                
                <div class="mb-4">
                    <label for="received_by_name" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">Diterima Oleh</label>
                    <input type="text" class="w-full h-10 px-3.5 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" id="received_by_name" name="received_by_name" placeholder="Nama penerima (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="received_notes" class="block text-xs font-semibold mb-1.5" style="color: #1c0f0a;">Catatan Penerimaan</label>
                    <textarea class="w-full p-3 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none" id="received_notes" name="received_notes" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-4 border-t border-[#f0ece6]">
                <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors" style="background: #f0ece6; color: #1c0f0a;" onclick="closeMarkReceivedModal()">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition-colors" style="background: #c2410c;">Tandai Diterima</button>
            </div>
        </form>
    </div>
</div>

<script>
function showMarkReceivedModal(distributionId, mustahikName) {
    document.getElementById('mustahikNameModal').textContent = mustahikName;
    document.getElementById('markReceivedForm').action = `/distributions/${distributionId}/mark-received`;
    document.getElementById('received_by_name').value = '';
    document.getElementById('received_notes').value = '';
    document.getElementById('markReceivedModal').classList.remove('hidden');
}

function closeMarkReceivedModal() {
    document.getElementById('markReceivedModal').classList.add('hidden');
}

document.getElementById('markReceivedModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkReceivedModal();
    }
});
</script>
