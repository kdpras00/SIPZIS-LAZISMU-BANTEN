<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Campaign;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_code',
        'midtrans_order_id',
        'snap_token',
        'muzakki_id',
        'program_id',
        'program_category',
        'zakat_amount',
        'paid_amount',
        'payment_method',
        'midtrans_payment_method',
        'payment_reference',
        'payment_date',
        'notes',
        'status',
        'receipt_number',
        'received_by',
        'is_guest_payment'
    ];

    protected $casts = [
        'zakat_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    
    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getCampaignAttribute()
    {
        
        if ($this->program_id) {
            
            $campaign = Campaign::where('program_id', $this->program_id)
                ->where(function ($query) {
                    $query->where('status', 'published')
                          ->orWhere('status', 'completed');
                })
                ->orderByRaw("CASE WHEN status = 'published' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($campaign) {
                return $campaign;
            }
        }

        
        if ($this->program_category && $this->program_category !== 'umum') {
            
            $campaign = Campaign::where('program_category', $this->program_category)
                ->where(function ($query) {
                    $query->where('status', 'published')
                          ->orWhere('status', 'completed');
                })
                ->orderByRaw("CASE WHEN status = 'published' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($campaign) {
                return $campaign;
            }
        }

        return null;
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    
    public static function generatePaymentCode(): string
    {
        $year = date('Y');
        do {
            $random = strtoupper(\Illuminate\Support\Str::random(8));
            $code = "DNS-{$year}-{$random}";
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }

    public static function generateReceiptNumber(): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $year  = date('Y');
            $month = date('m');
            $last  = self::where('receipt_number', 'like', "RCP-{$year}{$month}-%")
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $next = $last
                ? ((int) (explode('-', $last->receipt_number)[2] ?? 0)) + 1
                : 1;

            return "RCP-{$year}{$month}-" . str_pad($next % 100000, 4, '0', STR_PAD_LEFT);
        });
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->paid_amount, 0, ',', '.');
    }

    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('payment_date', $year);
    }

    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?: date('Y');
        return $query->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month);
    }

    public function scopeByProgramCategory($query, $category)
    {
        return $query->where('program_category', $category);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    
    public static function boot()
    {
        parent::boot();

        
        static::updating(function ($payment) {
            if ($payment->isDirty('status')) {
                if (in_array($payment->status, ['completed', 'success', 'settlement']) && empty($payment->receipt_number)) {
                    $payment->receipt_number = self::generateReceiptNumber();
                }
            }
        });

        
        static::created(function ($payment) {
            
            if ($payment->muzakki) {
                Notification::createPaymentNotification($payment->muzakki, $payment, $payment->status);
            }
        });

        
        static::updated(function ($payment) {
            
            if ($payment->wasChanged('status')) {
                
                if ($payment->muzakki) {
                    Notification::createPaymentNotification($payment->muzakki, $payment, $payment->status);
                }
            }
        });
    }
    public function getRouteKeyName()
    {
        return 'payment_code';
    }
}
