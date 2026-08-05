<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'start_date', 'expiry_date',
        'usage_limit', 'used_count', 'min_booking_amount', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_booking_amount' => 'decimal:2'
    ];

    public function isValidFor($amount)
    {
        if (!$this->is_active) return false;
        if ($this->expiry_date && $this->expiry_date->isPast()) return false;
        if ($this->start_date && $this->start_date->isFuture()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($amount < $this->min_booking_amount) return false;
        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            return round(($amount * $this->value) / 100, 2);
        }
        return min($this->value, $amount);
    }
}
