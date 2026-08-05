<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $totalBookings = Booking::where('user_id', $userId)->count();
        $activeBookings = Booking::where('user_id', $userId)->whereIn('status', ['pending', 'confirmed'])->count();
        $wishlistCount = Wishlist::where('user_id', $userId)->count();
        
        $recentBookings = Booking::with('vehicle')->where('user_id', $userId)->orderBy('created_at', 'desc')->take(5)->get();

        return view('customer.dashboard', compact('totalBookings', 'activeBookings', 'wishlistCount', 'recentBookings'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['vehicle.brand', 'pickupLocation'])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.bookings', compact('bookings'));
    }

    public function wishlist()
    {
        $wishlist = Wishlist::with('vehicle.brand')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('customer.wishlist', compact('wishlist'));
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $userId = Auth::id();
        $vehicleId = $request->vehicle_id;

        $exists = Wishlist::where('user_id', $userId)->where('vehicle_id', $vehicleId)->first();
        if ($exists) {
            $exists->delete();
            $inWishlist = false;
            $msg = 'Removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicleId
            ]);
            $inWishlist = true;
            $msg = 'Added to wishlist.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'message' => $msg
            ]);
        }

        return back()->with('success', $msg);
    }

    public function reviews()
    {
        $reviews = Review::with('vehicle')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        // Vehicles that user rented and can review (completed bookings, and not already reviewed)
        $rentedVehicleIds = Booking::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->pluck('vehicle_id')
            ->unique();

        $reviewedVehicleIds = Review::where('user_id', Auth::id())->pluck('vehicle_id');
        
        $pendingReviewVehicles = Vehicle::whereIn('id', $rentedVehicleIds)
            ->whereNotIn('id', $reviewedVehicleIds)
            ->get();

        return view('customer.reviews', compact('reviews', 'pendingReviewVehicles'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5'
        ]);

        // Verify that user actually rented this vehicle
        $rented = Booking::where('user_id', Auth::id())
            ->where('vehicle_id', $request->vehicle_id)
            ->where('status', 'completed')
            ->exists();

        if (!$rented) {
            return back()->withErrors(['vehicle_id' => 'You can only review vehicles that you have rented and completed bookings for.']);
        }

        Review::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $request->vehicle_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false // Admin needs to approve
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting administrator approval.');
    }

    public function settings()
    {
        return view('customer.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string']
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return back()->with('success', 'Profile information updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
