<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'model_year', 'license_plate',
        'fuel_type', 'seats', 'transmission', 'mileage', 'engine_size', 'color',
        'price_per_day', 'status', 'description', 'main_image', 'features',
        'is_featured', 'is_popular'
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'price_per_day' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class, 'vehicle_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'vehicle_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'vehicle_id');
    }

    // Average rating helper
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating'), 1) ?: 0;
    }
}
