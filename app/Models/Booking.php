<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number', 'user_id', 'vehicle_id', 'pickup_location_id',
        'return_location_id', 'pickup_date', 'return_date', 'total_days',
        'price_per_day', 'subtotal', 'discount_amount', 'tax_amount',
        'grand_total', 'coupon_code', 'status', 'notes'
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id')->withTrashed();
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id')->withTrashed();
    }

    public function returnLocation()
    {
        return $this->belongsTo(Location::class, 'return_location_id')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}
