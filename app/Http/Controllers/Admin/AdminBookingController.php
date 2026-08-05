<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    // ==========================================
    // BOOKINGS MANAGEMENT
    // ==========================================

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'vehicle.brand']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'vehicle.brand', 'pickupLocation', 'returnLocation', 'payments'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed,rejected'
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        // Release vehicle if booking is completed, cancelled or rejected
        if (in_array($newStatus, ['completed', 'cancelled', 'rejected'])) {
            $booking->vehicle->update(['status' => 'available']);
        }

        // If newly confirmed, verify payment is recorded or update
        if ($newStatus === 'confirmed' && $booking->payments()->count() > 0) {
            $payment = $booking->payments()->first();
            if ($payment->payment_method === 'cod') {
                $payment->update(['status' => 'pending']);
            }
        }

        // Mark payment as paid if order completed
        if ($newStatus === 'completed') {
            $booking->payments()->update(['status' => 'paid']);
        }

        return back()->with('success', 'Booking status updated to ' . ucfirst($newStatus) . ' successfully!');
    }

    // ==========================================
    // CUSTOMERS MANAGEMENT
    // ==========================================

    public function customers()
    {
        $customers = User::where('role', 'customer')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    // ==========================================
    // PAYMENTS LIST
    // ==========================================

    public function payments()
    {
        $payments = Payment::with('booking.user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    // ==========================================
    // REVIEWS MANAGEMENT
    // ==========================================

    public function reviews()
    {
        $reviews = Review::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approveReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved successfully!');
    }

    public function rejectReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);
        return back()->with('success', 'Review unapproved/rejected.');
    }

    // ==========================================
    // COUPONS CRUD
    // ==========================================

    public function coupons()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function couponStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'min_booking_amount' => 'nullable|numeric|min:0'
        ]);

        Coupon::create($request->all());
        return back()->with('success', 'Coupon created successfully!');
    }

    public function couponUpdate(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'min_booking_amount' => 'nullable|numeric|min:0'
        ]);

        $coupon->update($request->all());
        return back()->with('success', 'Coupon updated successfully!');
    }

    public function couponDestroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return back()->with('success', 'Coupon deleted successfully!');
    }

    // ==========================================
    // REPORTS & EXPORTS
    // ==========================================

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:revenue,bookings,vehicles,customers'
        ]);

        $type = $request->type;
        $fileName = 'report_' . $type . '_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        switch ($type) {
            case 'revenue':
                $columns = ['Payment ID', 'Booking Number', 'Customer', 'Amount', 'Payment Method', 'Status', 'Date'];
                $dataLoader = function() {
                    return Payment::with('booking.user')->where('status', 'paid')->cursor();
                };
                $rowFormatter = function($payment) {
                    return [
                        $payment->id,
                        $payment->booking->booking_number ?? 'N/A',
                        $payment->booking->user->name ?? 'Deleted User',
                        $payment->amount,
                        strtoupper($payment->payment_method),
                        ucfirst($payment->status),
                        $payment->created_at->format('Y-m-d H:i:s')
                    ];
                };
                break;

            case 'bookings':
                $columns = ['Booking Number', 'Customer', 'Vehicle', 'Pickup Date', 'Return Date', 'Total Days', 'Grand Total', 'Status', 'Booking Date'];
                $dataLoader = function() {
                    return Booking::with(['user', 'vehicle'])->cursor();
                };
                $rowFormatter = function($booking) {
                    return [
                        $booking->booking_number,
                        $booking->user->name ?? 'Deleted User',
                        $booking->vehicle->name ?? 'Deleted Vehicle',
                        $booking->pickup_date->format('Y-m-d'),
                        $booking->return_date->format('Y-m-d'),
                        $booking->total_days,
                        $booking->grand_total,
                        ucfirst($booking->status),
                        $booking->created_at->format('Y-m-d H:i:s')
                    ];
                };
                break;

            case 'vehicles':
                $columns = ['Vehicle Name', 'License Plate', 'Model Year', 'Transmission', 'Fuel Type', 'Price Per Day', 'Status', 'Featured', 'Popular'];
                $dataLoader = function() {
                    return Vehicle::cursor();
                };
                $rowFormatter = function($vehicle) {
                    return [
                        $vehicle->name,
                        $vehicle->license_plate,
                        $vehicle->model_year,
                        $vehicle->transmission,
                        $vehicle->fuel_type,
                        $vehicle->price_per_day,
                        ucfirst($vehicle->status),
                        $vehicle->is_featured ? 'Yes' : 'No',
                        $vehicle->is_popular ? 'Yes' : 'No'
                    ];
                };
                break;

            case 'customers':
                $columns = ['Customer ID', 'Name', 'Email', 'Phone', 'Address', 'Join Date'];
                $dataLoader = function() {
                    return User::where('role', 'customer')->cursor();
                };
                $rowFormatter = function($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone ?? 'N/A',
                        $user->address ?? 'N/A',
                        $user->created_at->format('Y-m-d H:i:s')
                    ];
                };
                break;
        }

        $callback = function() use ($columns, $dataLoader, $rowFormatter) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dataLoader() as $item) {
                fputcsv($file, $rowFormatter($item));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
