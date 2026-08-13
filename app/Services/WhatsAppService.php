<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;

class WhatsAppService
{
    protected $apiUrl;
    protected $token;
    protected $enabled;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->token = config('services.whatsapp.token');
        $this->enabled = config('services.whatsapp.enabled', false);
    }

    
    public function sendMessage($phone, $message)
    {
        
        if (!$this->enabled) {
            Log::info('WhatsApp is disabled in config', ['phone' => $phone]);
            return [
                'success' => false,
                'message' => 'WhatsApp notification is disabled'
            ];
        }

        
        if (empty($this->token)) {
            Log::error('WhatsApp API token not configured');
            return [
                'success' => false,
                'message' => 'WhatsApp API token not configured'
            ];
        }

        
        $formattedPhone = $this->formatPhoneNumber($phone);

        if (!$formattedPhone) {
            Log::error('Invalid phone number format', ['phone' => $phone]);
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        try {
            
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62', 
            ]);

            $result = $response->json();

            
            Log::channel('whatsapp')->info('WhatsApp message sent', [
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'response' => $result,
                'sent_at' => now(),
            ]);

            return [
                'success' => $response->successful(),
                'message' => $result['message'] ?? 'Message sent',
                'response' => $result
            ];
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Failed to send WhatsApp message', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    protected function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        
        $phone = preg_replace('/[^0-9]/', '', $phone);

        
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }

        
        if (substr($phone, 0, 2) !== '62') {
            return null;
        }

        
        if (strlen($phone) < 10 || strlen($phone) > 15) {
            return null;
        }

        return $phone;
    }

    
    public function sendPaymentPending(Payment $payment, $phone)
    {
        $programName = $this->getProgramName($payment);
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $trackingUrl = route('guest.payment.summary', $payment->payment_code);

        $message = "🕐 *DONASI PENDING*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Terima kasih telah berdonasi melalui Lazismu Banten!\n\n";
        $message .= "📋 *Detail Donasi:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Program: {$programName}\n";
        $message .= "• Nominal: Rp {$amount}\n";
        $message .= "• Status: ⏳ Menunggu Pembayaran\n\n";
        $message .= "💳 Silakan selesaikan pembayaran Anda.\n\n";
        $message .= "Cek status: {$trackingUrl}\n\n";
        $message .= "Setelah pembayaran berhasil, kwitansi akan dikirim ke email Anda.\n\n";
        $message .= "_Lazismu Banten - Layanan Zakat, Infaq, Sedekah_";

        return $this->sendMessage($phone, $message);
    }

    
    public function sendPaymentSuccess(Payment $payment, $phone)
    {
        $programName = $this->getProgramName($payment);
        $amount = number_format($payment->paid_amount, 0, ',', '.');

        
        
        if ($payment->updated_at && $payment->status === 'completed') {
            
            $dateTime = $payment->updated_at;
        } elseif ($payment->created_at) {
            
            $dateTime = $payment->created_at;
        } else {
            
            $dateTime = now();
        }

        
        if (!$dateTime instanceof \Carbon\Carbon) {
            $dateTime = \Carbon\Carbon::parse($dateTime);
        }

        
        $date = $dateTime->setTimezone('Asia/Jakarta')->format('d M Y H:i');

        $message = "✅ *DONASI BERHASIL*\n\n";
        $message .= "Alhamdulillah! 🎉\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Donasi Anda telah berhasil diterima.\n\n";
        $message .= "📋 *Detail Donasi:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Program: {$programName}\n";
        $message .= "• Nominal: Rp {$amount}\n";
        $message .= "• Tanggal: {$date}\n\n";
        $message .= "Jazakallahu khairan katsiran! 🤲\n\n";
        $message .= "📄 Kwitansi pembayaran dilampirkan pada pesan ini.\n";
        $message .= "Kwitansi juga telah dikirim ke email Anda dalam format PDF.\n\n";
        $message .= "_Lazismu Banten - Layanan Zakat, Infaq, Sedekah_";

        
        return $this->sendReceiptPDF($payment, $phone, $message);
    }

    
    public function sendReceiptPDF(Payment $payment, $phone, $customMessage = null)
    {
        
        if (!$this->enabled) {
            return [
                'success' => false,
                'message' => 'WhatsApp is disabled in config'
            ];
        }

        
        if (empty($this->token)) {
            Log::error('WhatsApp API token not configured');
            return [
                'success' => false,
                'message' => 'WhatsApp API token not configured'
            ];
        }

        
        $formattedPhone = $this->formatPhoneNumber($phone);

        if (!$formattedPhone) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        try {
            
            $payment->load(['muzakki', 'program']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.guest-receipt-pdf', [
                'payment' => $payment
            ]);
            $pdf->setPaper('A4');

            
            $pdfContent = $pdf->output();

            
            $filename = 'kwitansi-' . $payment->payment_code . '.pdf';
            $storagePath = 'receipts/' . $filename;
            Storage::disk('public')->put($storagePath, $pdfContent);

            
            $message = $customMessage ?? 'Kwitansi Pembayaran Donasi Anda';

            
            
            $publicUrl = asset('storage/' . $storagePath);

            
            $appUrl = config('app.url');
            if (str_contains($publicUrl, 'localhost') || str_contains($publicUrl, '127.0.0.1')) {
                
                $publicUrl = rtrim($appUrl, '/') . '/storage/' . $storagePath;
            }

            
            
            $isLocalhost = str_contains($publicUrl, 'localhost') || str_contains($publicUrl, '127.0.0.1');
            
            if (!$isLocalhost) {
                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->apiUrl, [
                    'target' => $formattedPhone,
                    'message' => $message,
                    'type' => 'document',
                    'document' => $publicUrl,
                    'filename' => $filename,
                ]);

                $result = $response->json();
            } else {
                
                $response = isset($response) ? $response : null; 
            }

            
            
            if ($isLocalhost || !$response || !$response->successful()) {
                if (!$isLocalhost) {
                     Log::channel('whatsapp')->warning('URL method failed, trying base64 method', [
                        'error' => $result['message'] ?? 'Unknown error',
                        'payment_code' => $payment->payment_code,
                        'url_tried' => $publicUrl,
                    ]);
                } else {
                     Log::channel('whatsapp')->info('Localhost detected, using base64 method immediately', [
                        'payment_code' => $payment->payment_code,
                    ]);
                }

                
                $base64Pdf = base64_encode($pdfContent);

                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->apiUrl, [
                    'target' => $formattedPhone,
                    'message' => $message,
                    'type' => 'document',
                    'document' => $base64Pdf,
                    'filename' => $filename,
                ]);
            }

            $result = $response->json();

            Log::channel('whatsapp')->info('WhatsApp PDF receipt sent', [
                'phone' => $formattedPhone,
                'payment_code' => $payment->payment_code,
                'status' => $response->status(),
                'response' => $result,
            ]);

            return [
                'success' => $response->successful(),
                'message' => $result['message'] ?? 'PDF sent',
                'response' => $result
            ];
        } catch (\Exception $e) {
            
            if (isset($storagePath)) {
                try {
                    Storage::disk('public')->delete($storagePath);
                } catch (\Exception $deleteException) {
                    
                }
            }

            Log::channel('whatsapp')->error('Failed to send WhatsApp PDF receipt', [
                'phone' => $formattedPhone,
                'payment_code' => $payment->payment_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    public function sendPaymentFailed(Payment $payment, $phone)
    {
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $retryUrl = route('guest.payment.create', [
            
            'category' => $payment->program_category,
            'amount' => $payment->paid_amount
        ]);

        $message = "❌ *DONASI GAGAL*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Maaf, pembayaran Anda gagal diproses.\n\n";
        $message .= "📋 *Detail:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Nominal: Rp {$amount}\n\n";
        $message .= "Silakan coba lagi atau hubungi kami.\n\n";
        $message .= "🔄 Donasi Ulang: {$retryUrl}\n";
        $message .= "📞 Bantuan: admin@lazismu-banten.or.id\n\n";
        $message .= "_Lazismu Banten - Layanan Zakat, Infaq, Sedekah_";

        return $this->sendMessage($phone, $message);
    }

    
    public function sendPaymentCancelled(Payment $payment, $phone)
    {
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $retryUrl = route('guest.payment.create', [
            
            'category' => $payment->program_category
        ]);

        $message = "🚫 *DONASI DIBATALKAN*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Pembayaran Anda telah dibatalkan.\n\n";
        $message .= "📋 *Detail:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Nominal: Rp {$amount}\n\n";
        $message .= "Ingin berdonasi lagi?\n";
        $message .= "🔄 Donasi Baru: {$retryUrl}\n\n";
        $message .= "_Lazismu Banten - Layanan Zakat, Infaq, Sedekah_";

        return $this->sendMessage($phone, $message);
    }

    
    public function sendWelcomeMessage($muzakki)
    {
        if (!$muzakki->phone) {
            return [
                'success' => false,
                'message' => 'No phone number provided'
            ];
        }

        $message = "👋 *SELAMAT DATANG DI LAZISMU BANTEN*\n\n";
        $message .= "Halo *{$muzakki->name}*!\n\n";
        $message .= "Terima kasih telah bergabung dengan Lazismu Banten.\n\n";
        $message .= "Bersama kita wujudkan:\n";
        $message .= "✅ Transparansi pengelolaan zakat\n";
        $message .= "✅ Kemudahan berdonasi\n";
        $message .= "✅ Penyaluran tepat sasaran\n\n";
        $message .= "Mari mulai berbagi kebaikan! 💚\n\n";
        $message .= "_Lazismu Banten - Layanan Zakat, Infaq, Sedekah_";

        return $this->sendMessage($muzakki->phone, $message);
    }

    
    protected function getProgramName(Payment $payment)
    {
        
        
        if ($payment->campaign) {
            return $payment->campaign->title;
        }

        if ($payment->program_category) {
            $categoryNames = [
                'pendidikan' => 'Donasi Pendidikan',
                'kesehatan' => 'Donasi Kesehatan',
                'ekonomi' => 'Donasi Ekonomi',
                'sosial-dakwah' => 'Donasi Sosial Dakwah',
                'kemanusiaan' => 'Donasi Kemanusiaan',
                'lingkungan' => 'Donasi Lingkungan',
                'zakat-mal' => 'Zakat Mal',
                'zakat-fitrah' => 'Zakat Fitrah',
                'zakat-profesi' => 'Zakat Profesi',
                'infaq-masjid' => 'Infaq Masjid',
                'shadaqah-jariyah' => 'Shadaqah Jariyah',
            ];

            return $categoryNames[$payment->program_category] ?? 'Donasi Umum';
        }

        return 'Donasi Umum';
    }
}
