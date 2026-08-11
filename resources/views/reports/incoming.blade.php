@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Laporan Masuk</h2>
        <p class="text-sm" style="color: #8b7e74;">Ringkasan data pembayaran zakat yang masuk</p>
    </div>

    
    <div class="rounded-2xl mb-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="p-5 sm:p-6">
            <form method="GET" action="{{ route('reports.incoming') }}" id="filterForm" class="flex flex-wrap items-center gap-3">
                
                
                <div class="flex-1 min-w-[220px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs" style="color: #8b7e74;"></i>
                        <input type="text" id="search" name="search"
                            class="w-full h-11 pl-9 pr-4 rounded-xl border border-[#e8e0d6] bg-white text-xs font-medium text-[#1c0f0a] focus:border-[#c2410c] focus:ring-2 focus:ring-[#c2410c]/10 transition-all outline-none"
                            placeholder="Cari kode pembayaran, nama muzakki..." value="{{ request('search') }}"
                            onchange="this.form.submit()">
                    </div>
                </div>

                
                <div class="w-full sm:w-[170px]">
                    <x-custom-select 
                        id="payment_method" 
                        name="payment_method" 
                        placeholder="Semua Metode" 
                        :selected="request('payment_method', '')" 
                        :options="['cash' => 'Tunai', 'transfer' => 'Transfer', 'check' => 'Cek', 'online' => 'Online']"
                        onChange="this.$refs.hiddenInput.form.submit()" />
                </div>

                
                <div class="w-full sm:w-[145px]">
                    <x-custom-date-picker
                        id="date_from"
                        name="date_from"
                        :value="request('date_from')"
                        placeholder="Tanggal Mulai"
                        onChange="this.$refs.hiddenInput.form.submit()"
                    />
                </div>

                <span class="text-xs text-[#8b7e74] font-medium hidden sm:inline">s/d</span>

                
                <div class="w-full sm:w-[145px]">
                    <x-custom-date-picker
                        id="date_to"
                        name="date_to"
                        :value="request('date_to')"
                        placeholder="Tanggal Akhir"
                        onChange="this.$refs.hiddenInput.form.submit()"
                    />
                </div>

                
                <div class="flex items-center gap-2">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center justify-center h-11 px-4 font-medium rounded-xl text-xs transition-colors duration-200" style="border: 1px solid #e8e0d6; color: #1c0f0a; background: #fff;">
                            <i class="bi bi-download mr-1.5" style="color: #c2410c;"></i> Export
                        </button>
                        <div x-show="open" x-cloak x-transition style="display: none;" class="absolute right-0 mt-1.5 w-36 bg-white rounded-2xl border border-[#e8e0d6] shadow-xl py-1.5 z-50">
                            <button type="button" class="w-full text-left px-4 py-2 text-xs text-[#1c0f0a] hover:bg-orange-50/60 transition-colors flex items-center gap-2" onclick="exportReport('pdf')">
                                <i class="bi bi-file-earmark-pdf text-red-600"></i> PDF
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2 text-xs text-[#1c0f0a] hover:bg-orange-50/60 transition-colors flex items-center gap-2" onclick="exportReport('excel')">
                                <i class="bi bi-file-earmark-excel text-green-600"></i> Excel (CSV)
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total pembayaran</p>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ number_format($stats['total_count'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Total nominal masuk</p>
            <p class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Bulan ini</p>
            <p class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04);">
            <p class="text-xs font-medium leading-tight" style="color: #8b7e74;">Menunggu verifikasi</p>
            <p class="text-2xl font-bold mb-0" style="color: #1c0f0a;">{{ number_format($stats['pending'], 0, ',', '.') }}</p>
        </div>
    </div>

    
    <div class="rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div class="overflow-x-auto">
            <table id="table-reports-incoming" class="min-w-full divide-y divide-[#f0ece6]">
                <thead style="background: #faf8f5;">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kode Pembayaran</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Nama Muzakki</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jenis Zakat</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Metode</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Nominal</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Tanggal</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0ece6]">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                        <td class="px-5 py-4 whitespace-nowrap text-xs font-bold" style="color: #1c0f0a;">{{ $payment->payment_code }}</td>
                        <td class="px-5 py-4 whitespace-nowrap text-xs font-bold" style="color: #1c0f0a;">{{ $payment->muzakki?->name ?? '-' }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">{{ $payment->zakatType?->name ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium" style="color: #8b7e74;">
                            @switch($payment->payment_method)
                            @case('cash') Tunai @break
                            @case('transfer') Transfer @break
                            @case('check') Cek @break
                            @case('online') Online @break
                            @endswitch
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-[#c2410c]">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium" style="color: #1c0f0a;">{{ $payment->payment_date->format('d M Y') }}</td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($payment->status == 'completed')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #c2410c;">Selesai</span>
                            @elseif($payment->status == 'pending')
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Menunggu</span>
                            @else
                            <span class="inline-flex items-center text-xs font-semibold" style="color: #dc2626;">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-file-earmark-bar-graph text-4xl mb-2" style="color: #d1cbc4;"></i>
                                <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Tidak ada data laporan masuk</p>
                                <p class="text-xs mt-1" style="color: #8b7e74;">Coba sesuaikan filter atau rentang tanggal pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function exportReport(type) {
    const form = document.getElementById('filterForm');
    const action = form.action;
    const exportInput = document.createElement('input');
    exportInput.type = 'hidden';
    exportInput.name = 'export';
    exportInput.value = type;
    form.appendChild(exportInput);
    form.submit();
    form.removeChild(exportInput);
}
</script>
@endsection
