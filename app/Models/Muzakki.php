<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone_verified',
        'nik',
        'gender',
        'address',
        'city',
        'province',
        'district',
        'village',
        'postal_code',
        'country',
        'campaign_url',
        'profile_photo',
        'ktp_photo',
        'bio',
        'occupation',
        'monthly_income',
        'date_of_birth',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'phone_verified' => 'boolean',
    ];

    /**
     * Find or create a muzakki record by email or attributes.
     */
    public static function findOrCreate(array $attributes)
    {
        $muzakki = self::where('email', $attributes['email'])->first();

        if ($muzakki) {
            $updateData = [];
            foreach ($attributes as $key => $value) {
                if ($value !== null || $muzakki->$key === null) {
                    $updateData[$key] = $value;
                }
            }
            $muzakki->update($updateData);
            return $muzakki;
        }

        return self::create($attributes);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zakatPayments()
    {
        return $this->hasMany(ZakatPayment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function recurringDonations()
    {
        return $this->hasMany(RecurringDonation::class);
    }

    // Attributes & Accessors
    public function getTotalZakatPaidAttribute()
    {
        return $this->zakatPayments()->where('status', 'completed')->sum('paid_amount');
    }

    public function getZakatPaymentsByYear($year = null)
    {
        $year = $year ?: date('Y');
        return $this->zakatPayments()
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->get();
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getPendingPaymentsCountAttribute()
    {
        return $this->zakatPayments()->pending()->count();
    }

    public function getTotalPaymentsCountAttribute()
    {
        return $this->zakatPayments()->count();
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
    }

    public function getLatestNotifications($limit = 10)
    {
        return $this->notifications()->latest()->limit($limit)->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByOccupation($query, $occupation)
    {
        return $query->where('occupation', $occupation);
    }

    public function getProfileCompletenessAttribute()
    {
        $fields = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'district' => $this->district,
            'village' => $this->village,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'campaign_url' => $this->campaign_url,
            'profile_photo' => $this->profile_photo,
            'ktp_photo' => $this->ktp_photo,
            'bio' => $this->bio,
            'occupation' => $this->occupation,
            'date_of_birth' => $this->date_of_birth,
        ];

        $filledFields = 0;
        $totalFields = count($fields);

        foreach ($fields as $value) {
            if (!is_null($value) && $value !== '') {
                $filledFields++;
            }
        }

        return $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
    }
}
