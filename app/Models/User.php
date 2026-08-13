<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    
    use HasFactory, Notifiable, HasRoles;

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'phone',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_confirmed_at',
        'avatar',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    
    public function muzakki()
    {
        return $this->hasOne(Muzakki::class);
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'distributed_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isMuzakki()
    {
        return $this->hasRole('muzakki');
    }

    
    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled && $this->two_factor_secret;
    }

    
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
    }

    
    public function getLatestNotifications($limit = 10)
    {
        return $this->notifications()->latest()->limit($limit)->get();
    }

    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->role($role);
    }

    
    public static function boot()
    {
        parent::boot();

        
        static::updated(function ($user) {
            
            if ($user->isDirty('password')) {
                
                
                $user->refresh();
                $muzakki = $user->muzakki;
                Notification::createAccountNotification($user, 'password', $muzakki);
            }
        });
    }
}
