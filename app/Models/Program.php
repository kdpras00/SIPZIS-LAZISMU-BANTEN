<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'target_amount',
        'status',
        'photo',
        'image_url',
        'slug',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
    ];

    
    
    
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    
    
    

    
    public function getImageUrlAttribute()
    {
        
        $imageUrl = isset($this->attributes['image_url']) ? $this->attributes['image_url'] : null;
        $photo = isset($this->attributes['photo']) ? $this->attributes['photo'] : null;
        
        
        $imagePath = $imageUrl ?: $photo;

        
        if (empty($imagePath)) {
            return null;
        }

        
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        
        
        return Storage::url($imagePath);
    }

    
    public function getTotalCollectedAttribute()
    {
        
        if (array_key_exists('payments_sum_paid_amount', $this->attributes)) {
            return (float) $this->attributes['payments_sum_paid_amount'];
        }

        
        return Payment::where('program_id', $this->id)
            ->where('status', 'completed')
            ->sum('paid_amount');
    }

    
    public function getTotalDistributedAttribute()
    {
        return $this->distributions()->sum('amount');
    }

    
    public function getNetTotalCollectedAttribute()
    {
        return max(0, $this->total_collected - $this->total_distributed);
    }

    
    public function getFormattedTotalCollectedAttribute()
    {
        return 'Rp ' . number_format($this->net_total_collected ?? 0, 0, ',', '.');
    }

    
    public function getTotalTargetAttribute()
    {
        if ($this->target_amount > 0) {
            return $this->target_amount;
        }

        return $this->campaigns()
            ->published()
            ->sum('target_amount');
    }

    
    public function getFormattedTotalTargetAttribute()
    {
        return 'Rp ' . number_format($this->total_target ?? 0, 0, ',', '.');
    }

    
    public function getProgressPercentageAttribute()
    {
        if ($this->total_target <= 0) {
            return 0;
        }

        
        return min(100, ($this->net_total_collected / $this->total_target) * 100);
    }

    
    public function getSlugAttribute($value)
    {
        
        if (!$value) {
            return Str::slug($this->name);
        }

        return $value;
    }

    
    
    
    
    
    public function isTargetReached(): bool
    {
        if ($this->total_target <= 0) {
            return false;
        }
        
        return $this->net_total_collected >= $this->total_target;
    }

    
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    
    public function checkAndCompleteIfTargetReached(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        if ($this->isTargetReached()) {
            return $this->update(['status' => 'completed']);
        }
        
        return false;
    }

    
    
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            
            if (empty($program->slug)) {
                $baseSlug = Str::slug($program->name);
                $slug = $baseSlug;
                $counter = 1;

                
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $program->slug = $slug;
            }
        });

        
        static::created(function ($program) {
            if ($program->status === 'active') {
                \App\Jobs\SendProgramNotifications::dispatch($program);
            }
        });

        
        static::updated(function ($program) {
            if ($program->isDirty('status') && $program->status === 'active') {
                \App\Jobs\SendProgramNotifications::dispatch($program);
            }
        });
    }
}
