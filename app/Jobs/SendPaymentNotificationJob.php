<?php

namespace App\Jobs;

use App\Models\Payment;
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

    
    public int $tries = 3;

    
    public array $backoff = [10, 30, 60];

    public function __construct(
        public readonly Payment $payment,
        public readonly ?string $previousStatus = null
    ) {}

    
    public function handle(): void
    {
        
        $this->payment->refresh();

        
        $this->sendPaymentEmail();

        
        $this->sendWhatsAppNotification();
    }

    
    private function sendPaymentEmail(): void
    {
        if (!$this->payment->muzakki || !$this->payment->muzakki->email) {
            return;
        }

        $cacheKey = "email_sent_{$this->payment->payment_code}_{$this->payment->status}";

        if (Cache::has($cacheKey)) {
            Log::info('Email already sent, skipping duplicate: ' . $this->payment->payment_code . ' with status: ' . $this->payment->status);
            return;
        }

        try {
            switch ($this->payment->status) {
                case 'completed':
                case 'failed':
                case 'cancelled':
                    Mail::to($this->payment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentStatus($this->payment, $this->payment->status));

                    Cache::put($cacheKey, true, now()->addHours(24));
                    Log::info('Payment email sent successfully', [
                        'email' => $this->payment->muzakki->email,
                        'payment_code' => $this->payment->payment_code,
                        'status' => $this->payment->status
                    ]);
                    break;

                case 'pending':
                    Log::info('Payment pending, no email sent', [
                        'email' => $this->payment->muzakki->email,
                        'payment_code' => $this->payment->payment_code
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send payment email in job', [
                'payment_code' => $this->payment->payment_code,
                'email' => $this->payment->muzakki->email ?? 'N/A',
                'error_message' => $e->getMessage()
            ]);
            throw $e; 
        }
    }

    
    private function sendWhatsAppNotification(): void
    {
        if (!$this->payment->muzakki) {
            Log::info('No muzakki found for payment: ' . $this->payment->payment_code);
            return;
        }

        $phone = $this->payment->muzakki->phone;

        if (!$phone) {
            Log::info('No phone number for muzakki, skip WhatsApp notification for payment: ' . $this->payment->payment_code);
            return;
        }

        try {
            $whatsappService = new WhatsAppService();

            switch ($this->payment->status) {
                case 'completed':
                    $result = $whatsappService->sendPaymentSuccess($this->payment, $phone);
                    break;

                case 'pending':
                    Log::channel('whatsapp')->info('Payment pending WhatsApp skipped to avoid spam', [
                        'payment_code' => $this->payment->payment_code,
                        'phone' => $phone
                    ]);
                    $result = ['success' => true];
                    break;

                case 'failed':
                    $result = $whatsappService->sendPaymentFailed($this->payment, $phone);
                    break;

                case 'cancelled':
                    $result = $whatsappService->sendPaymentCancelled($this->payment, $phone);
                    break;
                default:
                    $result = ['success' => false];
                    break;
            }

            Log::channel('whatsapp')->info('Payment WhatsApp sent via Job', [
                'payment_code' => $this->payment->payment_code,
                'phone' => $phone,
                'status' => $this->payment->status,
                'success' => $result['success'] ?? false
            ]);
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Failed to send WhatsApp notification in job', [
                'payment_code' => $this->payment->payment_code,
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            throw $e; 
        }
    }
}
