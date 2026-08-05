<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'address', 'latitude', 'longitude', 'is_active'];

    public function bookingsAsPickup()
    {
        return $this->hasMany(Booking::class, 'pickup_location_id');
    }

    public function bookingsAsReturn()
    {
        return $this->hasMany(Booking::class, 'return_location_id');
    }
}
