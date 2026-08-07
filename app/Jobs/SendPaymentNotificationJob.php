<?php

namespace App\Jobs;

use App\Models\ZakatPayment;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan maksimal jika terjadi kegagalan transient.
     */
    public int $tries = 3;

    /**
     * Waktu jeda (backoff) antar percobaan dalam detik.
     */
    public array $backoff = [10, 30, 60];

    public function __construct(
        public readonly ZakatPayment $zakatPayment,
        public readonly ?string $previousStatus = null
    ) {}

    /**
     * Jalankan eksekusi pengiriman notifikasi email & WhatsApp secara asynchronous.
     */
    public function handle(): void
    {
        // Fresh reload data pembayaran untuk memastikan status terbaru
        $this->zakatPayment->refresh();

        // 1. Kirim Email Notifikasi
        $this->sendPaymentEmail();

        // 2. Kirim WhatsApp Notifikasi
        $this->sendWhatsAppNotification();
    }

    /**
     * Pengiriman Email Notifikasi
     */
    private function sendPaymentEmail(): void
    {
        if (!$this->zakatPayment->muzakki || !$this->zakatPayment->muzakki->email) {
            return;
        }

        $cacheKey = "email_sent_{$this->zakatPayment->payment_code}_{$this->zakatPayment->status}";

        if (Cache::has($cacheKey)) {
            Log::info('Email already sent, skipping duplicate: ' . $this->zakatPayment->payment_code . ' with status: ' . $this->zakatPayment->status);
            return;
        }

        try {
            switch ($this->zakatPayment->status) {
                case 'completed':
                case 'failed':
                case 'cancelled':
                    Mail::to($this->zakatPayment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentStatus($this->zakatPayment, $this->zakatPayment->status));

                    Cache::put($cacheKey, true, now()->addHours(24));
                    Log::info('Payment email sent successfully', [
                        'email' => $this->zakatPayment->muzakki->email,
                        'payment_code' => $this->zakatPayment->payment_code,
                        'status' => $this->zakatPayment->status
                    ]);
                    break;

                case 'pending':
                    Log::info('Payment pending, no email sent', [
                        'email' => $this->zakatPayment->muzakki->email,
                        'payment_code' => $this->zakatPayment->payment_code
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send payment email in job', [
                'payment_code' => $this->zakatPayment->payment_code,
                'email' => $this->zakatPayment->muzakki->email ?? 'N/A',
                'error_message' => $e->getMessage()
            ]);
            throw $e; // Re-throw agar job di-retry otomatis oleh queue worker
        }
    }

    /**
     * Pengiriman WhatsApp Notifikasi
     */
    private function sendWhatsAppNotification(): void
    {
        if (!$this->zakatPayment->muzakki) {
            Log::info('No muzakki found for payment: ' . $this->zakatPayment->payment_code);
            return;
        }

        $phone = $this->zakatPayment->muzakki->phone;

        if (!$phone) {
            Log::info('No phone number for muzakki, skip WhatsApp notification for payment: ' . $this->zakatPayment->payment_code);
            return;
        }

        try {
            $whatsappService = new WhatsAppService();

            switch ($this->zakatPayment->status) {
                case 'completed':
                    $result = $whatsappService->sendPaymentSuccess($this->zakatPayment, $phone);
                    break;

                case 'pending':
                    $result = $whatsappService->sendPaymentPending($this->zakatPayment, $phone);
                    break;

                case 'failed':
                    $result = $whatsappService->sendPaymentFailed($this->zakatPayment, $phone);
                    break;

                case 'cancelled':
                    $result = $whatsappService->sendPaymentCancelled($this->zakatPayment, $phone);
                    break;
                default:
                    $result = ['success' => false];
                    break;
            }

            Log::channel('whatsapp')->info('Payment WhatsApp sent via Job', [
                'payment_code' => $this->zakatPayment->payment_code,
                'phone' => $phone,
                'status' => $this->zakatPayment->status,
                'success' => $result['success'] ?? false
            ]);
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Failed to send WhatsApp notification in job', [
                'payment_code' => $this->zakatPayment->payment_code,
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            throw $e; // Re-throw agar job di-retry otomatis
        }
    }
}
