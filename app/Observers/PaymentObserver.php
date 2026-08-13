<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\WhatsAppService;
use App\Jobs\SendPaymentNotificationJob;

class PaymentObserver
{
    
    public function creating(Payment $payment): void
    {
        
        $this->validateReceivedBy($payment);
    }

    
    public function updating(Payment $payment): void
    {
        
        $this->validateReceivedBy($payment);
    }

    
    public function created(Payment $payment): void
    {
        
        if ($payment->status === 'completed' && $payment->program_category) {
            $this->updateCampaignAndProgramTotals($payment);
        }

        
        SendPaymentNotificationJob::dispatch($payment);
    }

    
    public function updated(Payment $payment): void
    {
        
        if ($payment->isDirty('status') && $payment->status === 'completed' && $payment->program_category) {
            $this->updateCampaignAndProgramTotals($payment);
        }

        
        if ($payment->isDirty('status')) {
            $originalStatus = $payment->getOriginal('status');
            $newStatus = $payment->status;

            if ($originalStatus !== $newStatus) {
                
                SendPaymentNotificationJob::dispatch($payment, $originalStatus);
            }
        }
    }

    
    public function deleted(Payment $payment): void
    {
        
        if ($payment->status === 'completed' && $payment->program_category) {
            $this->updateCampaignAndProgramTotals($payment);
        }
    }

    
    public function restored(Payment $payment): void
    {
        
        if ($payment->status === 'completed' && $payment->program_category) {
            $this->updateCampaignAndProgramTotals($payment);
        }
    }

    
    public function forceDeleted(Payment $payment): void
    {
        
        if ($payment->status === 'completed' && $payment->program_category) {
            $this->updateCampaignAndProgramTotals($payment);
        }
    }

    
    private function validateReceivedBy(Payment $payment): void
    {
        
        if ($payment->received_by !== null) {
            $user = User::find($payment->received_by);
            if ($user && !$user->hasRole('admin')) {
                
                $payment->received_by = null;
            }
        }
    }

    
    private function updateCampaignAndProgramTotals(Payment $payment): void
    {
        
        $campaigns = collect();

        
        if ($payment->program_id) {
            $campaigns = Campaign::where('program_id', $payment->program_id)
                ->get();
        }

        
        
        if ($payment->program_category) {
            $campaignsByCategory = Campaign::where('program_category', $payment->program_category)
                ->where('created_at', '<=', $payment->created_at)
                ->get();
            $campaigns = $campaigns->merge($campaignsByCategory)->unique('id');
        }

        
        foreach ($campaigns as $campaign) {
            $collectedAmount = 0;

            
            if ($campaign->program_id) {
                $collectedAmount = \App\Models\Payment::where('program_id', $campaign->program_id)
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            } else {
                $collectedAmount = \App\Models\Payment::where('program_category', $campaign->program_category)
                    ->whereNotNull('program_category')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            }

            
            $campaign->update(['collected_amount' => $collectedAmount]);
        }

        
        
        if ($payment->program_id) {
            $program = Program::find($payment->program_id);
            if ($program) {
                Cache::forget("program_total_collected_{$program->id}");
            }
        }
    }

    
    private function sendPaymentEmail(Payment $payment): void
    {
        
        if (!$payment->muzakki || !$payment->muzakki->email) {
            return;
        }

        
        $cacheKey = "email_sent_{$payment->payment_code}_{$payment->status}";

        
        if (Cache::has($cacheKey)) {
            Log::info('Email already sent, skipping duplicate: ' . $payment->payment_code . ' with status: ' . $payment->status);
            return;
        }

        try {
            
            
            switch ($payment->status) {
                case 'completed':
                    
                    
                    try {
                        Mail::to($payment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($payment, 'completed'));

                        
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'completed'
                        ]);
                    } catch (\Exception $mailException) {
                        
                        Log::error('Failed to send payment email', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'completed',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                        
                    }
                    break;

                case 'pending':
                    
                    Log::info('Payment pending, no email sent', [
                        'email' => $payment->muzakki->email,
                        'payment_code' => $payment->payment_code
                    ]);
                    break;

                case 'failed':
                    
                    try {
                        Mail::to($payment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($payment, 'failed'));

                        
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'failed'
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('Failed to send payment email', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'failed',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                    }
                    break;

                case 'cancelled':
                    
                    try {
                        Mail::to($payment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($payment, 'cancelled'));

                        
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'cancelled'
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('Failed to send payment email', [
                            'email' => $payment->muzakki->email,
                            'payment_code' => $payment->payment_code,
                            'status' => 'cancelled',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                    }
                    break;
            }
        } catch (\Exception $e) {
            
            Log::error('Failed to send payment email - outer exception', [
                'payment_code' => $payment->payment_code,
                'email' => $payment->muzakki->email ?? 'N/A',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    
    private function sendWhatsAppNotification(Payment $payment): void
    {
        
        if (!$payment->muzakki) {
            Log::info('No muzakki found for payment: ' . $payment->payment_code);
            return;
        }

        
        $phone = $payment->muzakki->phone;

        
        if (!$phone) {
            Log::info('No phone number for muzakki, skip WhatsApp notification for payment: ' . $payment->payment_code);
            return;
        }

        try {
            $whatsappService = new WhatsAppService();

            
            switch ($payment->status) {
                case 'completed':
                    $result = $whatsappService->sendPaymentSuccess($payment, $phone);
                    Log::channel('whatsapp')->info('Payment success WhatsApp sent', [
                        'payment_code' => $payment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;

                case 'pending':
                    Log::channel('whatsapp')->info('Payment pending WhatsApp skipped to avoid spam', [
                        'payment_code' => $payment->payment_code,
                        'phone' => $phone
                    ]);
                    break;

                case 'failed':
                    $result = $whatsappService->sendPaymentFailed($payment, $phone);
                    Log::channel('whatsapp')->info('Payment failed WhatsApp sent', [
                        'payment_code' => $payment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;

                case 'cancelled':
                    $result = $whatsappService->sendPaymentCancelled($payment, $phone);
                    Log::channel('whatsapp')->info('Payment cancelled WhatsApp sent', [
                        'payment_code' => $payment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            
            Log::channel('whatsapp')->error('Failed to send WhatsApp notification', [
                'payment_code' => $payment->payment_code,
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
