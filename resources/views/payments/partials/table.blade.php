@if ($payments->count() > 0)
    <div class="overflow-x-auto">
        <table id="table-payments" class="min-w-full divide-y divide-[#f0ece6]">
            <thead style="background: #faf8f5;">
                <tr>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kode Pembayaran</th>
                    @if (!auth()->user()->hasRole('muzakki'))
                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Muzakki</th>
                    @endif
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Jumlah Bayar</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Tanggal</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-[#f0ece6]">
                @foreach ($payments as $payment)
                    <tr class="hover:bg-[#faf8f5]/60 transition-colors duration-150">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 border border-[#f0ece6] flex-shrink-0" style="background: #faf8f5;">
                                    <i class="bi bi-credit-card text-sm" style="color: #c2410c;"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold" style="color: #1c0f0a;">{{ $payment->payment_code }}</div>
                                    <div class="text-[11px]" style="color: #8b7e74;">{{ $payment->receipt_number }}</div>
                                </div>
                            </div>
                        </td>
                        @if (!auth()->user()->hasRole('muzakki'))
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold" style="color: #1c0f0a;">{{ $payment->muzakki->name }}</div>
                                @if ($payment->muzakki->phone)
                                    <div class="text-[11px]" style="color: #8b7e74;">{{ $payment->muzakki->phone }}</div>
                                @endif
                            </td>
                        @endif
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <div class="text-xs font-bold text-[#c2410c]">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                            @if ($payment->zakat_amount)
                                <div class="text-[11px]" style="color: #8b7e74;">Zakat: Rp {{ number_format($payment->zakat_amount, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @switch($payment->status)
                                @case('pending')
                                    <span class="inline-flex items-center text-xs font-semibold" style="color: #1c0f0a;">Menunggu</span>
                                @break
                                @case('completed')
                                    <span class="inline-flex items-center text-xs font-semibold" style="color: #c2410c;">Selesai</span>
                                @break
                                @case('cancelled')
                                    <span class="inline-flex items-center text-xs font-semibold" style="color: #dc2626;">Dibatalkan</span>
                                @break
                                @default
                                    <span class="inline-flex items-center text-xs font-semibold" style="color: #8b7e74;">{{ ucwords($payment->status) }}</span>
                            @endswitch
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium" style="color: #1c0f0a;">
                            {{ $payment->payment_date->format('d M Y') }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-start xl:justify-center gap-1.5">
                                <a href="{{ route('payments.show', $payment->payment_code) }}"
                                   class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors" style="background: #f0ece6; color: #1c0f0a;"
                                   title="Lihat Detail">
                                    <i class="bi bi-eye text-xs"></i>
                                </a>
                                <a href="{{ route('payments.receipt', $payment) }}"
                                   class="inline-flex items-center justify-center h-8 px-2.5 rounded-lg text-xs font-medium transition-colors border border-[#ffedd5]" style="background: #fff7ed; color: #c2410c;"
                                   title="Kwitansi" target="_blank">
                                    <i class="bi bi-file-earmark-pdf text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($payments->hasPages())
        <div class="px-5 py-4 border-t border-[#f0ece6]" style="background: #fff;">
            {{ $payments->withQueryString()->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12 px-6">
        <i class="bi bi-wallet2 text-4xl mb-2 block" style="color: #d1cbc4;"></i>
        <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Tidak ada data pembayaran</p>
        <p class="text-xs mt-1 mb-4" style="color: #8b7e74;">
            @if (auth()->user()->hasRole('muzakki'))
                Belum ada pembayaran zakat yang tercatat
            @else
                Tidak ada pembayaran zakat yang sesuai dengan kriteria pencarian
            @endif
        </p>
        @if (auth()->user()->hasRole('muzakki'))
            <a href="{{ route('payments.create') }}"
               class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-plus-circle-fill mr-1.5"></i> Bayar Zakat Sekarang
            </a>
        @endif
    </div>
@endif
