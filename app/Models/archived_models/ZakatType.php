<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZakatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'rate',
        'nisab_amount',
        'nisab_unit',
        'is_active'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'nisab_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    // public function zakatPayments()
    // {
    //     return $this->hasMany(ZakatPayment::class);
    // }

    // Methods
    public function calculateZakat($wealthAmount)
    {
        if ($this->nisab_amount && $wealthAmount < $this->nisab_amount) {
            return 0; // Below nisab threshold
        }

        return $wealthAmount * $this->rate;
    }

    public function isEligibleForZakat($wealthAmount)
    {
        return !$this->nisab_amount || $wealthAmount >= $this->nisab_amount;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
