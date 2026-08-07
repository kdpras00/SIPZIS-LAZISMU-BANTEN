<?php

namespace App\Services;

use App\Models\ZakatPayment;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class MidtransService
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    /**
     * Configure Midtrans credentials & environment.
     */
    protected function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);

        $overrideServerKey = env('MIDTRANS_SERVER_KEY');
        if (!empty($overrideServerKey)) {
            Config::$serverKey = $overrideServerKey;
        }
    }

    /**
     * Build Midtrans transaction parameter array for a payment.
     */
    public function buildTransactionParams(ZakatPayment $payment, string $paymentMethod = 'midtrans'): array
    {
        $orderId = $payment->payment_code;
        $grossAmount = (int) round($payment->paid_amount);

        $payerName = $payment->muzakki?->name ?? 'Donatur';
        $payerEmail = $payment->muzakki?->email ?? 'donatur@sipzis-lazismu-banten.or.id';
        $payerPhone = $payment->muzakki?->phone ?? '081234567890';

        $programName = $payment->program?->name ?? 'Donasi SIPZIS Lazismu Banten';

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $payerName,
                'email' => $payerEmail,
                'phone' => $payerPhone,
            ],
            'item_details' => [
                [
                    'id' => 'DONATION-' . $payment->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => mb_substr($programName, 0, 50),
                ]
            ]
        ];

        // Specific payment method overrides if provided
        if ($paymentMethod === 'gopay') {
            $params['payment_type'] = 'gopay';
            $params['gopay'] = [
                'enable_callback' => true,
                'callback_url' => route('guest.payment.success', ['paymentCode' => $payment->payment_code])
            ];
        } elseif (str_ends_with($paymentMethod, '_va')) {
            $bank = str_replace('_va', '', $paymentMethod);
            $params['payment_type'] = 'bank_transfer';
            $params['bank_transfer'] = [
                'bank' => $bank
            ];
        } elseif ($paymentMethod === 'qris') {
            $params['payment_type'] = 'qris';
        }

        return $params;
    }

    /**
     * Create Snap Token from Midtrans.
     */
    public function createSnapToken(ZakatPayment $payment, string $paymentMethod = 'midtrans'): string
    {
        $params = $this->buildTransactionParams($payment, $paymentMethod);
        return Snap::getSnapToken($params);
    }

    /**
     * Process Midtrans Notification Payload.
     */
    public function handleNotificationPayload(array $payload): ?ZakatPayment
    {
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return null;
        }

        $payment = ZakatPayment::where('payment_code', $orderId)->first();
        if (!$payment) {
            return null;
        }

        $newStatus = $payment->status;

        if ($transactionStatus === 'capture') {
            $newStatus = ($fraudStatus === 'challenge') ? 'pending' : 'completed';
        } elseif ($transactionStatus === 'settlement') {
            $newStatus = 'completed';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $newStatus = 'cancelled';
        } elseif ($transactionStatus === 'pending') {
            $newStatus = 'pending';
        }

        if ($payment->status !== $newStatus) {
            $payment->update([
                'status' => $newStatus,
                'payment_reference' => $payload['transaction_id'] ?? $payment->payment_reference
            ]);
        }

        return $payment;
    }
}
