<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'muzakki_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'is_read',
        'read_at',
        'notifiable_type',
        'notifiable_id',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForMuzakki($query, $muzakkiId)
    {
        return $query->where('muzakki_id', $muzakkiId);
    }

    // Methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    // Backward compatibility delegates to NotificationService
    public static function createPaymentNotification($muzakki, $payment, $status)
    {
        return app(\App\Services\NotificationService::class)->createPaymentNotification($muzakki, $payment, $status);
    }

    public static function createDistributionNotification($muzakki, $distribution)
    {
        return app(\App\Services\NotificationService::class)->createDistributionNotification($muzakki, $distribution);
    }

    public static function createAccountNotification($user, $eventType, $muzakki = null)
    {
        return app(\App\Services\NotificationService::class)->createAccountNotification($user, $eventType, $muzakki);
    }
}
