<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\Coupon;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
            'coupon_code' => 'nullable|string'
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);
        
        // Check availability
        $overlapping = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->where(function($query) use ($pickup, $return) {
                $query->where('pickup_date', '<=', $return->format('Y-m-d'))
                      ->where('return_date', '>=', $pickup->format('Y-m-d'));
            })->exists();

        if ($overlapping) {
            return response()->json([
                'success' => false,
                'message' => 'This vehicle is already booked for the selected dates.'
            ], 422);
        }

        $days = $pickup->diffInDays($return);
        if ($days === 0) $days = 1;

        $pricePerDay = $vehicle->price_per_day;
        $subtotal = $pricePerDay * $days;

        // Discount calculation
        $discount = 0;
        $couponMessage = '';
        $couponValid = false;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponValid = true;
                $couponMessage = 'Coupon applied successfully!';
            } else {
                $couponMessage = 'Invalid or expired coupon code.';
            }
        }

        // Tax calculation
        $taxRate = (float) Setting::get('tax_rate', 12);
        $taxableAmount = max(0, $subtotal - $discount);
        $tax = round(($taxableAmount * $taxRate) / 100, 2);
        $total = round($taxableAmount + $tax, 2);

        return response()->json([
            'success' => true,
            'days' => $days,
            'price_per_day' => number_format($pricePerDay, 2),
            'subtotal' => number_format($subtotal, 2),
            'discount' => number_format($discount, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2),
            'coupon_valid' => $couponValid,
            'coupon_message' => $couponMessage
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_location_id' => 'required|exists:locations,id',
            'return_location_id' => 'required|exists:locations,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
            'coupon_code' => 'nullable|string'
        ]);

        $vehicle = Vehicle::with(['brand', 'category'])->findOrFail($request->vehicle_id);
        $pickupLoc = Location::findOrFail($request->pickup_location_id);
        $returnLoc = Location::findOrFail($request->return_location_id);

        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);

        // Check availability
        $overlapping = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->where(function($query) use ($pickup, $return) {
                $query->where('pickup_date', '<=', $return->format('Y-m-d'))
                      ->where('return_date', '>=', $pickup->format('Y-m-d'));
            })->exists();

        if ($overlapping) {
            return back()->withErrors(['pickup_date' => 'This vehicle is already booked for the selected dates. Please choose different dates or another vehicle.']);
        }

        $days = $pickup->diffInDays($return);
        if ($days === 0) $days = 1;

        $subtotal = $vehicle->price_per_day * $days;

        $discount = 0;
        $coupon = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $taxRate = (float) Setting::get('tax_rate', 12);
        $tax = round((($subtotal - $discount) * $taxRate) / 100, 2);
        $total = round($subtotal - $discount + $tax, 2);

        $params = $request->only(['vehicle_id', 'pickup_location_id', 'return_location_id', 'pickup_date', 'return_date', 'coupon_code']);
        
        return view('booking.checkout', compact('vehicle', 'pickupLoc', 'returnLoc', 'days', 'subtotal', 'discount', 'tax', 'total', 'params'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_location_id' => 'required|exists:locations,id',
            'return_location_id' => 'required|exists:locations,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'required|in:stripe,razorpay,paypal,cod',
            'notes' => 'nullable|string'
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);

        // Check availability
        $overlapping = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->where(function($query) use ($pickup, $return) {
                $query->where('pickup_date', '<=', $return->format('Y-m-d'))
                      ->where('return_date', '>=', $pickup->format('Y-m-d'));
            })->exists();

        if ($overlapping) {
            return back()->withErrors(['pickup_date' => 'This vehicle is already booked for the selected dates. Please choose different dates or another vehicle.']);
        }

        $days = $pickup->diffInDays($return);
        if ($days === 0) $days = 1;

        $pricePerDay = $vehicle->price_per_day;
        $subtotal = $pricePerDay * $days;

        // Apply discount
        $discount = 0;
        $coupon = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Tax & Total
        $taxRate = (float) Setting::get('tax_rate', 12);
        $tax = round((($subtotal - $discount) * $taxRate) / 100, 2);
        $total = round($subtotal - $discount + $tax, 2);

        // Generate Booking Number
        $bookingNumber = 'BK-' . strtoupper(Str::random(3)) . '-' . mt_rand(1000, 9999);

        // Create Booking
        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $request->pickup_location_id,
            'return_location_id' => $request->return_location_id,
            'pickup_date' => $pickup->format('Y-m-d'),
            'return_date' => $return->format('Y-m-d'),
            'total_days' => $days,
            'price_per_day' => $pricePerDay,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'grand_total' => $total,
            'coupon_code' => $request->coupon_code,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        // Increment coupon count
        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Redirect to Payment router
        return redirect()->route('payment.gateway', [
            'booking_id' => $booking->id,
            'method' => $request->payment_method
        ]);
    }
}
