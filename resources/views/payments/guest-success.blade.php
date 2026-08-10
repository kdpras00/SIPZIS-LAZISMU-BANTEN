@extends('layouts.main')

@section('title', 'Pembayaran ' . ($payment->program ? $payment->program->name : ($payment->program_category ?
    ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum')))

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
    
    <div class="relative bg-gradient-to-br from-orange-50 via-orange-50 to-cyan-50 overflow-hidden min-h-screen">
        <div class="relative container mx-auto px-4 py-16">
            <div class="max-w-4xl mx-auto">
                
                <div class="text-center mb-12 mt-16">
                    @if ($payment->status === 'completed' || $payment->status === 'success' || $payment->status === 'settlement')
                        <h1 class="text-5xl font-bold text-orange-600 mb-3">Pembayaran Berhasil!</h1>
                        <p class="text-gray-700">Terima kasih, pembayaran Anda telah kami terima.</p>
                    @elseif ($payment->status === 'pending')
                        <h1 class="text-5xl font-bold text-yellow-600 mb-3">Menunggu Konfirmasi</h1>
                        <p class="text-gray-700">Pembayaran Anda sedang diproses. Mohon tunggu konfirmasi.</p>
                    @else
                        <h1 class="text-5xl font-bold text-red-600 mb-3">Pembayaran Gagal</h1>
                        <p class="text-gray-700">Maaf, terjadi kesalahan saat memproses pembayaran.</p>
                    @endif
                </div>

                
                <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Detail
                            {{ $payment->program ? $payment->program->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum') }}
                        </h2>
                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Kode Pembayaran:</span>
                                <span class="font-semibold text-orange-700">{{ $payment->payment_code }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Nama Donatur:</span>
                                <span class="font-semibold">{{ $payment->muzakki->name ?? 'Donatur Umum' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Jenis Program:</span>
                                <span
                                    class="font-semibold">{{ $payment->program ? $payment->program->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum') }}</span>
                            </div>
                        </div>

                        <div class="space-y-4">

                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Jumlah Dibayar:</span>
                                <span class="font-semibold text-orange-700">Rp
                                    {{ number_format($payment->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Metode Pembayaran:</span>
                                <span class="font-semibold">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-semibold">
                                    {{ optional($payment->payment_date)->format('d F Y') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($payment->notes)
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-700 mb-2">Catatan:</h4>
                            <p class="text-gray-600">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>

                
                @if (!Auth::check() && $payment->muzakki && $payment->muzakki->user && !$payment->muzakki->user->is_active)
                <div class="bg-yellow-50 border border-yellow-200 rounded-3xl p-8 mb-8 text-center sm:text-left max-w-4xl mx-auto shadow-sm">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 text-yellow-600">
                             <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-yellow-800 mb-2">Simpan Akun Anda?</h3>
                            <p class="text-yellow-700 mb-4">Email <strong>{{ $payment->muzakki->email }}</strong> sudah terdaftar. Buat password untuk melihat riwayat donasi Anda di masa depan.</p>
                            
                            <form action="{{ route('guest.account.claim') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input type="hidden" name="payment_code" value="{{ $payment->payment_code }}">
                                <input type="hidden" name="email" value="{{ $payment->muzakki->email }}">
                                <input type="password" name="password" placeholder="Buat Password" class="flex-1 px-4 py-3 rounded-xl border border-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="flex-1 px-4 py-3 rounded-xl border border-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                                <button type="submit" class="bg-yellow-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-yellow-700 transition shadow-md whitespace-nowrap">
                                    Simpan Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                
                <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                    @if (in_array($payment->status, ['completed', 'success']))
                        <a href="{{ route('guest.payment.receipt.download', $payment->payment_code) }}"
                            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto text-sm font-semibold text-white px-8 py-3 rounded-xl transition-colors duration-200 whitespace-nowrap"
                            style="background: #c2410c;">
                            <i class="fas fa-download"></i>
                            Unduh Kwitansi
                        </a>

                        <a href="{{ route('guest.payment.receipt', $payment->payment_code) }}"
                            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto text-sm font-semibold text-white px-8 py-3 rounded-xl transition-colors duration-200 whitespace-nowrap"
                            style="background: #c2410c;">
                            <i class="fas fa-eye"></i>
                            Lihat Kwitansi
                        </a>

                        <a href="{{ route('guest.payment.create') }}"
                            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto text-sm font-semibold bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-3 rounded-xl transition-colors duration-200 whitespace-nowrap">
                            <i class="fas fa-plus"></i>
                            Bayar Lagi
                        </a>
                    @endif

                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center gap-2 w-full sm:w-auto text-sm font-semibold bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-3 rounded-xl transition-colors duration-200 whitespace-nowrap">
                        <i class="fas fa-home"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                
                <div class="mt-12 text-center max-w-2xl mx-auto border-y border-orange-200 py-6">
                    <div class="text-orange-700 font-semibold mb-2 text-lg">
                        "وَمَن يُؤْتَ الْحِكْمَةَ فَقَدْ أُوتِيَ خَيْرًا كَثِيرًا"
                    </div>
                    <p class="text-gray-600 italic">
                        "Barangsiapa diberi hikmah, sungguh ia telah dianugerahi kebaikan yang banyak."
                    </p>
                    <p class="text-sm text-gray-500 mt-2">QS. Al-Baqarah: 269</p>
                </div>

            </div>
        </div>
    </div>

    <style>
        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(-3deg);
            }

            50% {
                transform: rotate(3deg);
            }
        }

        .animate-wiggle {
            animation: wiggle 0.5s ease-in-out infinite;
        }
    </style>
@endsection
