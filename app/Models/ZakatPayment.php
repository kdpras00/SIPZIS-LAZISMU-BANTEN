<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Campaign;

class ZakatPayment extends Model
{
    use HasFactory;

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

    // Relationships
    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }


    public function getCampaignAttribute()
    {
        // Try to find campaign by program_id first (most specific)
        if ($this->program_id) {
            // Find campaign with same program_id, prioritize published ones
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

        // Fallback to program_category (for backward compatibility)
        if ($this->program_category && $this->program_category !== 'umum') {
            // Find campaign with same program_category, prioritize published ones
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

    // Methods
    public static function generatePaymentCode(): string
    {
        // ponytail: lockForUpdate inside transaction prevents SELECT-then-INSERT race under concurrent requests.
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $year = date('Y');
            $last = self::where('payment_code', 'like', "DNS-{$year}-%")
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $next = $last
                ? ((int) (explode('-', $last->payment_code)[2] ?? 0)) + 1
                : 1;

            return "DNS-{$year}-" . str_pad($next % 10000, 3, '0', STR_PAD_LEFT);
        });
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

    // Scopes
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


    // Event handling for notifications
    public static function boot()
    {
        parent::boot();

        // When a payment is created
        static::created(function ($payment) {
            // Create a notification for the muzakki
            if ($payment->muzakki) {
                Notification::createPaymentNotification($payment->muzakki, $payment, $payment->status);
            }
        });

        // When a payment is updated
        static::updated(function ($payment) {
            // Check if status has changed
            if ($payment->isDirty('status')) {
                // Create a notification for the muzakki
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
