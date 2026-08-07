<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ZakatPayment;
use App\Models\ZakatDistribution;

class NotificationService
{
    /**
     * Membuat notifikasi pembayaran untuk muzakki.
     */
    public function createPaymentNotification($muzakki, ZakatPayment $payment, string $status): Notification
    {
        $paymentType = 'Donasi';
        if ($payment->program_category) {
            $category = strtolower(trim($payment->program_category));
            if ($category === 'zakat' || str_starts_with($category, 'zakat-')) {
                $paymentType = 'Donasi';
            } elseif ($category === 'infaq' || str_starts_with($category, 'infaq-')) {
                $paymentType = 'Infaq';
            } elseif (in_array($category, ['shadaqah', 'sedekah']) || str_starts_with($category, 'shadaqah-')) {
                $paymentType = 'Shadaqah';
            } else {
                $paymentType = ucfirst(str_replace('-', ' ', $category));
            }
        }

        $messages = [
            'completed' => 'Pembayaran ' . $paymentType . ' Anda telah berhasil diverifikasi.',
            'failed' => 'Pembayaran ' . $paymentType . ' Anda gagal diproses, silakan coba kembali.',
            'pending' => 'Menunggu konfirmasi pembayaran ' . $paymentType . ' melalui ' . ($payment->payment_method ?? 'transfer bank') . '.'
        ];

        $titles = [
            'completed' => 'Pembayaran Berhasil',
            'failed' => 'Pembayaran Gagal',
            'pending' => 'Menunggu Konfirmasi'
        ];

        return Notification::create([
            'muzakki_id' => $muzakki->id,
            'user_id' => $muzakki->user_id,
            'type' => 'payment',
            'title' => $titles[$status] ?? 'Notifikasi Pembayaran',
            'message' => $messages[$status] ?? 'Status pembayaran Anda diperbarui.',
            'notifiable_type' => ZakatPayment::class,
            'notifiable_id' => $payment->id,
            'data' => [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'status' => $status,
                'amount' => $payment->paid_amount,
                'program_category' => $payment->program_category,
                'payment_type' => $paymentType,
                'is_guest_payment' => $payment->is_guest_payment
            ]
        ]);
    }

    /**
     * Membuat notifikasi penyaluran donasi untuk muzakki.
     */
    public function createDistributionNotification($muzakki, ZakatDistribution $distribution): Notification
    {
        return Notification::create([
            'muzakki_id' => $muzakki->id,
            'user_id' => $muzakki->user_id,
            'type' => 'distribution',
            'title' => 'Donasi Telah Disalurkan',
            'message' => 'Donasi Anda telah disalurkan kepada mustahik di wilayah ' . ($distribution->location ?? 'yang membutuhkan') . '.',
            'notifiable_type' => ZakatDistribution::class,
            'notifiable_id' => $distribution->id,
            'data' => [
                'distribution_id' => $distribution->id,
                'amount' => $distribution->amount
            ]
        ]);
    }

    /**
     * Membuat notifikasi terkait akun user.
     */
    public function createAccountNotification($user, string $eventType, $muzakki = null): Notification
    {
        $muzakki = $muzakki ?: $user->muzakki;

        $messages = [
            'profile' => 'Selamat datang! Silakan lengkapi profil Anda untuk mempermudah transaksi donasi.',
            'password' => 'Kata sandi Anda berhasil diperbarui.'
        ];

        $titles = [
            'profile' => 'Selamat Datang',
            'password' => 'Perubahan Kata Sandi'
        ];

        return Notification::create([
            'user_id' => $user->id,
            'muzakki_id' => $muzakki?->id,
            'type' => 'account',
            'title' => $titles[$eventType] ?? 'Notifikasi Akun',
            'message' => $messages[$eventType] ?? 'Pembaruan akun berhasil.',
            'data' => ['event_type' => $eventType]
        ]);
    }
}
