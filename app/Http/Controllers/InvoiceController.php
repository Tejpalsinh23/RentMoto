<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download($bookingNumber)
    {
        $booking = Booking::with(['user', 'vehicle.brand', 'pickupLocation', 'returnLocation', 'payments'])
            ->where('booking_number', $bookingNumber)
            ->firstOrFail();

        // Authorize: only owner or admin can view
        if (Auth::user()->role !== 'admin' && Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('booking.invoice', compact('booking'));
        
        return $pdf->download('invoice-' . $booking->booking_number . '.pdf');
    }
}
