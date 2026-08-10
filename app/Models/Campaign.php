<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'program_category',
        'program_id',
        'target_amount',
        'collected_amount',
        'photo',
        'status',
        'end_date',
        'created_by',
        'is_published',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'end_date' => 'date',
        'is_published' => 'boolean',
    ];

    
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function payments()
    {
        
        if ($this->program_id) {
            return $this->hasMany(Payment::class, 'program_id', 'program_id')
                ->where('status', 'completed');
        }
        
        
        return $this->hasMany(Payment::class, 'program_category', 'program_category')
            ->whereNotNull('program_category')
            ->where('status', 'completed');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'program_name', 'program_category');
    }

    
    public function getImageUrlAttribute()
    {
        if (empty($this->photo)) {
            return null;
        }

        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        return asset('storage/' . ltrim($this->photo, '/'));
    }

    public function getCollectedAmountAttribute()
    {
        
        if (array_key_exists('payments_sum_paid_amount', $this->attributes)) {
            return (float) $this->attributes['payments_sum_paid_amount'];
        }

        
        if ($this->program_id) {
            return Payment::where('program_id', $this->program_id)
                ->where('status', 'completed')
                
                ->where('created_at', '>=', $this->created_at)
                ->sum('paid_amount');
        }
        
        
        
        return Payment::where('program_category', $this->program_category)
            ->whereNotNull('program_category')
            ->where('status', 'completed')
            ->where('created_at', '>=', $this->created_at)
            ->sum('paid_amount');
    }

    public function getDistributedAmountAttribute()
    {
        return $this->distributions()->sum('amount');
    }

    public function getNetCollectedAmountAttribute()
    {
        return max(0, $this->collected_amount - $this->distributed_amount);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, ($this->net_collected_amount / $this->target_amount) * 100);
    }

    public function getFormattedTargetAmountAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAmountAttribute()
    {
        return 'Rp ' . number_format($this->net_collected_amount, 0, ',', '.');
    }

    public function getDonorsCountAttribute()
    {
        return $this->payments()->count();
    }

    
    public function getRemainingDaysAttribute()
    {
        
        if (!$this->end_date) {
            return null;
        }

        $endDate = Carbon::parse($this->end_date)->endOfDay(); 
        $now = Carbon::now();

        
        if ($now->isAfter($endDate)) {
            return -1;
        }

        
        
        $remainingDays = $now->diffInDays($endDate, absolute: false);

        return (int) $remainingDays;
    }

    
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('program_category', $category);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return Carbon::parse($this->end_date)->isPast();
    }

    
    public function isTargetReached(): bool
    {
        if ($this->target_amount <= 0) {
            return false;
        }

        return $this->collected_amount >= $this->target_amount;
    }

    
    public function markAsCompleted(): bool
    {
        if ($this->isExpired() && $this->status === 'published') {
            return $this->update(['status' => 'completed']);
        }

        return false;
    }

    
    public function checkAndCompleteIfExpired(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        
        if ($this->isExpired() || $this->isTargetReached()) {
            return $this->update(['status' => 'completed']);
        }

        return false;
    }

    
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now()->startOfDay());
    }

    
    protected static function boot()
    {
        parent::boot();

        
        static::created(function ($campaign) {
            if ($campaign->status === 'published') {
                \App\Jobs\SendProgramNotifications::dispatch($campaign);
            }
        });

        
        static::updated(function ($campaign) {
            if ($campaign->isDirty('status') && $campaign->status === 'published') {
                \App\Jobs\SendProgramNotifications::dispatch($campaign);
            }
        });
    }
}
