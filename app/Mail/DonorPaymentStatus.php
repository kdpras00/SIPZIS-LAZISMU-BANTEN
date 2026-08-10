<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class DonorPaymentStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $donorName;
    public $status;
    public $statusMessage;

    
    public function __construct(Payment $payment, $status)
    {
        $this->payment = $payment;
        $this->donorName = $payment->muzakki->name ?? 'Hamba Allah';
        $this->status = $status;

        
        switch ($status) {
            case 'pending':
                $this->statusMessage = 'Menunggu Konfirmasi';
                break;
            case 'completed':
                $this->statusMessage = 'Pembayaran Berhasil';
                break;
            case 'failed':
                $this->statusMessage = 'Pembayaran Gagal';
                break;
            case 'cancelled':
                $this->statusMessage = 'Pembayaran Dibatalkan';
                break;
            default:
                $this->statusMessage = 'Status Pembayaran';
        }
    }

    
    public function build()
    {
        $email = $this->subject('Status Pembayaran Donasi Anda - ' . $this->statusMessage)
            ->view('emails.donor.payment-status')
            ->with([
                'payment' => $this->payment,
                'donorName' => $this->donorName,
                'status' => $this->status,
                'statusMessage' => $this->statusMessage,
            ]);

        
        if ($this->status === 'completed' && $this->payment->status === 'completed') {
            try {
                
                if (!$this->payment->relationLoaded('muzakki')) {
                    $this->payment->load('muzakki');
                }
                if (!$this->payment->relationLoaded('program')) {
                    $this->payment->load('program');
                }
                
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.guest-receipt-pdf', [
                    'payment' => $this->payment
                ]);
                
                $pdf->setPaper('A4');
                
                
                
                $pdfContent = $pdf->output();
                
                
                if (empty($pdfContent)) {
                    throw new \Exception('PDF content is empty');
                }
                
                
                $email->attachData($pdfContent, 'kwitansi-' . $this->payment->payment_code . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
                
                Log::info('PDF receipt attached to email successfully', [
                    'payment_code' => $this->payment->payment_code,
                    'email' => $this->payment->muzakki->email ?? 'N/A',
                    'pdf_size' => strlen($pdfContent)
                ]);
                
            } catch (\Exception $e) {
                
                Log::error('Failed to attach PDF receipt to email', [
                    'payment_code' => $this->payment->payment_code,
                    'email' => $this->payment->muzakki->email ?? 'N/A',
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                
                
            }
        }

        return $email;
    }
}
