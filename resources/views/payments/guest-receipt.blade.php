@extends('layouts.main')

@section('title', 'Kwitansi Pembayaran')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Receipt -->
            <div class="bg-white rounded-lg shadow-lg p-8" style="border: 2px solid #059669; padding: 40px; font-family: Arial, sans-serif;">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 2px solid #059669; margin-bottom: 30px;">
                    <div style="font-size: 28px; font-weight: bold; color: #059669;">SIPZIS</div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: #666;">No. Kwitansi</div>
                        <div style="font-size: 16px; font-weight: bold; color: #059669;">{{ $payment->receipt_number }}</div>
                    </div>
                </div>

                <div style="text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 30px; color: #333;">
                    KWITANSI PEMBAYARAN
                </div>

                <!-- Payment Details -->
                <div style="padding: 12px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex;">
                        <div style="width: 180px; color: #666; flex-shrink: 0;">Diterima dari</div>
                        <div style="flex: 1; font-weight: 500; color: #333;">{{ $payment->muzakki->name }}</div>
                    </div>
                </div>

                <div style="padding: 12px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex;">
                        <div style="width: 180px; color: #666; flex-shrink: 0;">Untuk pembayaran</div>
                        <div style="flex: 1; font-weight: 500; color: #333;">
                            {{ $payment->campaign ? $payment->campaign->title : ($payment->programType ? $payment->programType->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum')) }}
                            @if($payment->notes)
                            <span style="font-size: 12px; color: #666;"> ({{ $payment->notes }})</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding: 12px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex;">
                        <div style="width: 180px; color: #666; flex-shrink: 0;">Metode pembayaran</div>
                        <div style="flex: 1; font-weight: 500; color: #333;">{{ ucfirst($payment->payment_method) }}</div>
                    </div>
                </div>

                <div style="padding: 12px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex;">
                        <div style="width: 180px; color: #666; flex-shrink: 0;">Tanggal</div>
                        <div style="flex: 1; font-weight: 500; color: #333;">{{ $payment->payment_date->format('d F Y') }}</div>
                    </div>
                </div>

                <!-- Amount Details -->
                <div style="background: #f8f9fa; border: 2px solid #059669; padding: 20px; margin: 30px 0; text-align: center;">
                    <div style="font-size: 12px; color: #666; margin-bottom: 8px;">JUMLAH PEMBAYARAN</div>
                    <div style="font-size: 32px; font-weight: bold; color: #059669;">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                    <div style="margin-top: 8px; font-size: 13px; font-style: italic; color: #666;">
                        {{ ucwords(\Illuminate\Support\Str::lower(\App\Helpers\Terbilang::convert($payment->paid_amount))) }} Rupiah
                    </div>
                </div>

                <!-- Signatures -->
                <table style="width: 100%; margin-top: 50px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; text-align: center; vertical-align: top;">
                            <div style="font-size: 12px; color: #666; margin-bottom: 60px;">Muzakki</div>
                            <div style="border-top: 1px solid #333; padding-top: 8px; font-weight: 500; display: inline-block; min-width: 150px;">{{ $payment->muzakki->name }}</div>
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: top;">
                            <div style="font-size: 12px; color: #666; margin-bottom: 60px;">Penerima</div>
                            <div style="border-top: 1px solid #333; padding-top: 8px; font-weight: 500; display: inline-block; min-width: 150px;">Amil Zakat</div>
                        </td>
                    </tr>
                </table>

                <!-- Islamic Quote -->
                <div class="mt-12 text-center">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 shadow-md">
                        <div class="text-emerald-700 font-semibold mb-2">
                            "وَمَن يُؤْتَ الْحِكْمَةَ فَقَدْ أُوتِيَ خَيْرًا كَثِيرًا"
                        </div>
                        <p class="text-gray-600 italic">
                            "Barangsiapa diberi hikmah, sungguh ia telah dianugerahi kebaikan yang banyak."
                        </p>
                        <p class="text-sm text-gray-500 mt-2">QS. Al-Baqarah: 269</p>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->

            <div class="mt-8 mb-6 text-center no-print">
                <a href="{{ route('guest.payment.success', $payment->payment_code) }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button onclick="window.print()" class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors inline-flex items-center mr-4">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Kwitansi
                </button>
                <a href="{{ route('guest.payment.receipt.download', $payment->payment_code) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
            padding: 0;
        }

        .container {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }

        .shadow-lg {
            box-shadow: none !important;
        }

        .bg-white {
            border: 1px solid #059669 !important;
            padding: 20px !important;
        }

        .amount-section {
            break-inside: avoid;
        }
    }
</style>
@endsection