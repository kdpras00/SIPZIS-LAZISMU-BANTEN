@extends('layouts.app')

@section('page-title', 'Detail Muzakki - ' . $muzakki->name)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Muzakki</h2>
            <p class="text-sm" style="color: #8b7e74;">Informasi lengkap dan riwayat pembayaran zakat</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('muzakki.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('muzakki.edit', $muzakki) }}"
                class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-pencil-fill mr-1.5"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl overflow-hidden border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h5 class="text-sm font-bold text-[#1c0f0a] flex items-center mb-0">
                        <i class="bi bi-person-fill mr-2" style="color: #c2410c;"></i> Informasi Pribadi
                    </h5>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="rounded-full p-4 inline-flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-[#fff7ed] to-orange-100 text-[#c2410c] shadow-inner ring-4 ring-white">
                            <i class="bi bi-person-fill text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-[#1c0f0a] mt-2 mb-1.5">{{ $muzakki->name }}</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $muzakki->is_active ? 'bg-[#fff7ed] text-[#c2410c]' : 'bg-red-50 text-red-700' }}">
                            {{ $muzakki->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                            <div class="text-[#8b7e74] w-24 flex-shrink-0">NIK</div>
                            <div class="text-[#1c0f0a] font-medium">{{ $muzakki->nik ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                            <div class="text-[#8b7e74] w-24 flex-shrink-0">Email</div>
                            <div class="text-[#1c0f0a] font-medium">{{ $muzakki->email ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                            <div class="text-[#8b7e74] w-24 flex-shrink-0">Telepon</div>
                            <div class="text-[#1c0f0a] font-medium">{{ $muzakki->phone ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                            <div class="text-[#8b7e74] w-24 flex-shrink-0">Gender</div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $muzakki->gender === 'male' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                    {{ $muzakki->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>
                        </div>
                        @if ($muzakki->date_of_birth)
                            <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Tanggal Lahir</div>
                                <div class="text-[#1c0f0a] font-medium">{{ $muzakki->date_of_birth->format('d F Y') }}</div>
                            </div>
                            <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Usia</div>
                                <div class="text-[#1c0f0a] font-medium">{{ $muzakki->age ?? '-' }} tahun</div>
                            </div>
                        @endif
                        <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                            <div class="text-[#8b7e74] w-24 flex-shrink-0">Pekerjaan</div>
                            <div class="text-[#1c0f0a] font-medium">
                                {{ $muzakki->occupation ? ucwords(str_replace('_', ' ', $muzakki->occupation)) : '-' }}
                            </div>
                        </div>
                        @if ($muzakki->monthly_income)
                            <div class="flex">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Pendapatan</div>
                                <div class="text-[#1c0f0a] font-medium">Rp {{ number_format($muzakki->monthly_income, 0, ',', '.') }}/bulan</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-3xl overflow-hidden border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1">
                <div class="px-6 py-4 border-b border-[#f0ece6]">
                    <h6 class="text-sm font-bold text-[#1c0f0a] flex items-center mb-0">
                        <i class="bi bi-geo-alt-fill mr-2" style="color: #c2410c;"></i> Alamat
                    </h6>
                </div>
                <div class="p-6 text-xs text-[#1c0f0a]">
                    @if ($muzakki->address || $muzakki->city || $muzakki->province)
                        <address class="not-italic leading-relaxed">
                            @if ($muzakki->address)
                                {{ $muzakki->address }}<br>
                            @endif
                            @if ($muzakki->city || $muzakki->province)
                                {{ $muzakki->city }}{{ $muzakki->city && $muzakki->province ? ', ' : '' }}{{ $muzakki->province }}<br>
                            @endif
                            @if ($muzakki->postal_code)
                                Kode Pos: {{ $muzakki->postal_code }}
                            @endif
                        </address>
                    @else
                        <p class="text-[#8b7e74] mb-0">Alamat belum diisi</p>
                    @endif
                </div>
            </div>

            
            @if ($muzakki->user)
                <div class="bg-white rounded-3xl overflow-hidden border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1">
                    <div class="px-6 py-4 border-b border-[#f0ece6]">
                        <h6 class="text-sm font-bold text-[#1c0f0a] flex items-center mb-0">
                            <i class="bi bi-shield-lock-fill mr-2" style="color: #c2410c;"></i> Akun Pengguna
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-xs">
                            <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Status</div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $muzakki->user->is_active ? 'bg-[#fff7ed] text-[#c2410c]' : 'bg-red-50 text-red-700' }}">
                                        {{ $muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex border-b border-dashed border-[#e8e0d6] pb-3">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Role</div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ ucfirst($muzakki->user->roles->first()?->name ?? 'User') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="text-[#8b7e74] w-24 flex-shrink-0">Terdaftar</div>
                                <div class="text-[#1c0f0a] font-medium">{{ $muzakki->user->created_at->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-3xl p-5 border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium mb-1" style="color: #8b7e74;">Total Zakat</p>
                            <h4 class="text-xl font-bold mb-0" style="color: #1c0f0a;">Rp {{ number_format($stats['total_zakat'], 0, ',', '.') }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#fff7ed] to-orange-50 text-[#c2410c] transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-sm border border-orange-100/50">
                            <i class="bi bi-wallet2 text-lg"></i>
                        </div>
                    </div>
                    <p class="text-[11px] mt-2 mb-0" style="color: #8b7e74;">Sepanjang masa</p>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium mb-1" style="color: #8b7e74;">Total Transaksi</p>
                            <h4 class="text-xl font-bold mb-0" style="color: #1c0f0a;">{{ number_format($stats['payment_count']) }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#fff7ed] to-orange-50 text-[#c2410c] transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-sm border border-orange-100/50">
                            <i class="bi bi-[#c2410c] bi-receipt text-lg"></i>
                        </div>
                    </div>
                    <p class="text-[11px] mt-2 mb-0" style="color: #8b7e74;">Pembayaran selesai</p>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium mb-1" style="color: #8b7e74;">Terakhir Bayar</p>
                            <h4 class="text-xl font-bold mb-0" style="color: #1c0f0a;">
                                @if ($stats['last_payment'])
                                    {{ $stats['last_payment']->payment_date->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </h4>
                        </div>
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#fff7ed] to-orange-50 text-[#c2410c] transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-sm border border-orange-100/50">
                            <i class="bi bi-clock-history text-lg"></i>
                        </div>
                    </div>
                    @if ($stats['last_payment'])
                        <p class="text-[11px] mt-2 mb-0" style="color: #8b7e74;">{{ $stats['last_payment']->payment_date->format('d M Y') }}</p>
                    @else
                        <p class="text-[11px] mt-2 mb-0" style="color: #8b7e74;">Belum ada pembayaran</p>
                    @endif
                </div>
            </div>

            
            <div class="bg-white rounded-3xl overflow-hidden border border-[#f0ece6] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(194,65,12,0.12)] hover:-translate-y-1">
                <div class="px-6 py-4 border-b border-[#f0ece6] flex justify-between items-center">
                    <h5 class="text-sm font-bold text-[#1c0f0a] flex items-center mb-0">
                        <i class="bi bi-journal-check mr-2" style="color: #c2410c;"></i> Riwayat Pembayaran Donasi
                    </h5>
                    <a href="{{ route('payments.index', ['search' => $muzakki->name]) }}"
                        class="text-xs font-semibold hover:underline" style="color: #c2410c;">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-hidden">
                    @if ($recentPayments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-gray-50 text-[#8b7e74] uppercase tracking-wider text-[11px]">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5">Kode Pembayaran</th>
                                        <th scope="col" class="px-6 py-3.5">Jenis Zakat</th>
                                        <th scope="col" class="px-6 py-3.5">Jumlah</th>
                                        <th scope="col" class="px-6 py-3.5">Metode</th>
                                        <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                        <th scope="col" class="px-6 py-3.5">Status</th>
                                        <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#f0ece6] text-[#1c0f0a]">
                                    @foreach ($recentPayments as $payment)
                                        <tr class="hover:bg-orange-50/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-mono font-bold">{{ $payment->payment_code }}</div>
                                                @if ($payment->receipt_number)
                                                    <div class="text-[11px] text-[#8b7e74]">No: {{ $payment->receipt_number }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800">
                                                    {{ $payment->program ? $payment->program->name : 'Donasi Umum' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">
                                                Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800">
                                                    {{ ucfirst($payment->payment_method) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>{{ $payment->payment_date->format('d M Y') }}</div>
                                                <div class="text-[11px] text-[#8b7e74]">{{ $payment->payment_date->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->status === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#fff7ed] text-[#c2410c]">Selesai</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800">Pending</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <a href="{{ route('payments.show', $payment->payment_code) }}"
                                                    class="inline-flex items-center justify-center p-2 rounded-lg hover:bg-orange-50 text-[#c2410c]" title="Detail">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="bi bi-inbox text-3xl block mb-2" style="color: #8b7e74;"></i>
                            <h5 class="text-sm font-bold text-[#1c0f0a] mb-1">Belum Ada Pembayaran</h5>
                            <p class="text-xs text-[#8b7e74] mb-0">Muzakki ini belum memiliki riwayat pembayaran zakat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
