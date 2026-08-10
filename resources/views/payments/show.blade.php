@extends('layouts.app')

@section('page-title', 'Detail Pembayaran - ' . $payment->payment_code)

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Detail Pembayaran Zakat</h2>
            <p class="text-sm font-mono" style="color: #8b7e74;">{{ $payment->payment_code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('payments.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium transition-colors duration-200"
                style="background: #f0ece6; color: #1c0f0a;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            @if($payment->status === 'completed')
            <a href="{{ route('payments.receipt', $payment) }}" target="_blank" 
                class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-receipt-cutoff mr-1.5"></i> Kwitansi
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-credit-card-fill mr-2"></i>
                        Informasi Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div class="space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Pembayaran</label>
                                <div class="flex items-center mt-1">
                                    <p class="text-xl font-bold text-gray-900 font-mono">{{ $payment->payment_code }}</p>
                                    <button class="ml-2 text-gray-400 hover:text-blue-600 transition-colors" onclick="navigator.clipboard.writeText('{{ $payment->payment_code }}')" title="Salin Kode">
                                        <i class="bi bi-clipboard-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Program / Peruntukan</label>
                                <div class="mt-2">
                                    @php
                                        $campaign = $payment->campaign;
                                    @endphp
                                    @if($campaign)
                                        <div class="flex items-start p-3 bg-blue-50 rounded-lg border border-blue-100">
                                            <i class="bi bi-heart-fill text-blue-600 mt-1 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-blue-900">{{ $campaign->title }}</p>
                                                <p class="text-xs text-blue-700 mt-1">Campaign</p>
                                            </div>
                                        </div>
                                    @elseif($payment->program)
                                        <div class="flex items-start p-3 bg-blue-50 rounded-lg border border-blue-100">
                                            <i class="bi bi-archive-fill text-blue-600 mt-1 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-blue-900">{{ $payment->program->name }}</p>
                                                <p class="text-xs text-blue-700 mt-1">Program Lazismu</p>
                                            </div>
                                        </div>
                                    @elseif($payment->program)
                                        <div class="flex items-start p-3 bg-blue-50 rounded-lg border border-blue-100">
                                            <i class="bi bi-tag-fill text-blue-600 mt-1 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-blue-900">{{ $payment->program->name }}</p>
                                                <p class="text-xs text-blue-700 mt-1">Tipe Program</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-100">
                                            <i class="bi bi-wallet-fill text-gray-500 mt-1 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Donasi Umum</p>
                                                <p class="text-xs text-gray-500 mt-1">Tidak ada program spesifik</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Zakat</label>
                                    <p class="text-gray-900 font-medium mt-1">{{ $payment->zakatType->name ?? 'Donasi Umum' }}</p>
                                </div>
                                @if($payment->program_category)
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</label>
                                    <p class="text-gray-900 font-medium mt-1">{{ ucfirst(str_replace('-', ' ', $payment->program_category)) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status</label>
                                    <div class="mt-1">
                                        @switch($payment->status)
                                        @case('completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="bi bi-check-circle-fill mr-1.5"></i> Selesai
                                        </span>
                                        @break
                                        @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-hourglass-fill mr-1.5"></i> Menunggu
                                        </span>
                                        @break
                                        @case('cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bi bi-x-circle-fill mr-1.5"></i> Dibatalkan
                                        </span>
                                        @break
                                        @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @endswitch
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode Bayar</label>
                                    <div class="mt-1">
                                        @switch($payment->payment_method)
                                        @case('cash')
                                        <span class="inline-flex items-center text-sm font-medium text-gray-900">
                                            <i class="bi bi-cash-stack text-orange-600 mr-2"></i>Tunai
                                        </span>
                                        @break
                                        @case('transfer')
                                        <span class="inline-flex items-center text-sm font-medium text-gray-900">
                                            <i class="bi bi-bank text-blue-600 mr-2"></i>Transfer Bank
                                        </span>
                                        @break
                                        @case('online')
                                        <span class="inline-flex items-center text-sm font-medium text-gray-900">
                                            <i class="bi bi-globe text-indigo-600 mr-2"></i>Online
                                        </span>
                                        @break
                                        @default
                                        <span class="inline-flex items-center text-sm font-medium text-gray-900">
                                            <i class="bi bi-credit-card-fill text-gray-600 mr-2"></i>{{ ucfirst($payment->payment_method) }}
                                        </span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Transaksi</label>
                                <div class="flex items-center mt-1 text-gray-900">
                                    <i class="bi bi-calendar3-fill text-gray-400 mr-2"></i>
                                    <span>{{ $payment->payment_date->format('d F Y') }}</span>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <i class="bi bi-clock-fill text-gray-400 mr-2"></i>
                                    <span>{{ $payment->created_at->format('H:i') }} WIB</span>
                                </div>
                                @if($payment->hijri_year)
                                <p class="text-xs text-gray-500 mt-1 ml-6">Tahun Hijriyah: {{ $payment->hijri_year }} H</p>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">No. Kwitansi</label>
                                        <p class="text-sm font-mono text-gray-900 mt-1">{{ $payment->receipt_number ?: '-' }}</p>
                                    </div>
                                    @if($payment->receivedBy)
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Petugas</label>
                                        <p class="text-sm text-gray-900 mt-1">{{ $payment->receivedBy->name }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-calculator mr-2"></i>
                        Rincian Jumlah
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($payment->wealth_amount)
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Jumlah Harta</h6>
                            <h4 class="text-2xl font-bold text-blue-600 mb-1">Rp {{ number_format($payment->wealth_amount, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Total kekayaan yang dizakatkan</small>
                        </div>
                        @endif
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Zakat Wajib</h6>
                            <h4 class="text-2xl font-bold text-yellow-600 mb-1">Rp {{ number_format($payment->zakat_amount ?? 0, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Jumlah zakat yang wajib</small>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Jumlah Dibayar</h6>
                            <h4 class="text-2xl font-bold text-orange-600 mb-1">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Total yang dibayarkan</small>
                        </div>
                    </div>

                    @if($payment->zakat_amount && $payment->paid_amount > $payment->zakat_amount)
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-blue-900">Kelebihan Pembayaran:</strong>
                                <p class="text-blue-800 mt-1">Rp {{ number_format($payment->paid_amount - $payment->zakat_amount, 0, ',', '.') }}</p>
                                <small class="text-blue-700">Kelebihan ini dapat dianggap sebagai infaq atau shodaqoh.</small>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->zakat_amount && $payment->paid_amount < $payment->zakat_amount)
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-triangle-fill text-yellow-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-yellow-900">Kekurangan Pembayaran:</strong>
                                <p class="text-yellow-800 mt-1">Rp {{ number_format($payment->zakat_amount - $payment->paid_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->zakat_amount)
                    <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-check-circle-fill text-orange-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-orange-900">Pembayaran Pas:</strong>
                                <p class="text-orange-800 mt-1">Jumlah yang dibayar sesuai dengan kewajiban zakat.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            
            @if($payment->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-sticky-fill mr-2"></i>
                        Catatan
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-person-fill mr-2"></i>
                        Informasi Muzakki
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="bg-orange-100 rounded-full p-4 inline-flex items-center justify-center mb-3">
                            <i class="bi bi-person-fill text-3xl text-orange-600"></i>
                        </div>
                        <h5 class="font-semibold text-gray-900 mb-2">{{ $payment->muzakki->name }}</h5>
                        @if(!$payment->is_guest_payment)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Terdaftar
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Guest
                        </span>
                        @endif
                    </div>

                    <div class="space-y-3 border-t pt-4">
                        @if($payment->muzakki->email)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Email</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->email }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->phone)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Telepon</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->phone }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->address)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Alamat</label>
                            <p class="text-sm text-gray-900 mt-1">{{ Str::limit($payment->muzakki->address, 50) }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->city)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Kota</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->city }}</p>
                        </div>
                        @endif
                    </div>

                    @if(!$payment->is_guest_payment && $payment->muzakki->user)
                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Akun Pengguna:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $payment->muzakki->user->is_active ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $payment->muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <div class="border-t pt-4 mt-4">
                        <a href="{{ route('muzakki.show', $payment->muzakki) }}" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                            <i class="bi bi-eye-fill mr-2"></i> Lihat Detail Muzakki
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-clock-fill mr-2"></i>
                        Timeline Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative">
                        
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-orange-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Dibuat</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        @if($payment->status === 'completed')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-orange-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Selesai</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_date->format('d F Y, H:i') }} WIB</p>
                                @if($payment->receivedBy)
                                <p class="text-xs text-gray-500 mt-1">Diterima oleh: {{ $payment->receivedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                        @elseif($payment->status === 'pending')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-yellow-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Menunggu Konfirmasi</h6>
                                <p class="text-xs text-gray-500 mt-1">Pembayaran sedang diproses</p>
                            </div>
                        </div>
                        @elseif($payment->status === 'cancelled')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-red-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Dibatalkan</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->updated_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy payment code functionality
        document.querySelectorAll('.font-mono').forEach(element => {
            element.style.cursor = 'pointer';
            element.title = 'Klik untuk menyalin';
            element.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(function() {
                    // Show notification if needed
                });
            });
        });
    });
</script>
@endpush
