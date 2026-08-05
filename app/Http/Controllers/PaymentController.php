<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function gateway(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'method' => 'required|in:stripe,razorpay,paypal,cod'
        ]);

        $booking = Booking::with('vehicle')->findOrFail($request->booking_id);
        $method = $request->method;

        if ($method === 'cod') {
            // Cash on Delivery automatically sets up a pending payment
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => 'COD-' . strtoupper(Str::random(8)),
                'payment_method' => 'cod',
                'amount' => $booking->grand_total,
                'status' => 'pending',
                'payment_details' => ['message' => 'Pay in cash upon picking up the vehicle']
            ]);

            $booking->update(['status' => 'confirmed']);

            return redirect()->route('payment.success', $booking->id);
        }

        // Show checkout simulator page for digital payment gateways
        return view('payment.simulator', compact('booking', 'method'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'method' => 'required|in:stripe,razorpay,paypal',
            'outcome' => 'required|in:success,failure'
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $outcome = $request->outcome;
        $method = $request->method;

        if ($outcome === 'success') {
            // Create successful payment
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => strtoupper($method) . '-' . strtoupper(Str::random(12)),
                'payment_method' => $method,
                'amount' => $booking->grand_total,
                'status' => 'paid',
                'payment_details' => [
                    'simulator' => true,
                    'auth_code' => mt_rand(100000, 999999),
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);

            $booking->update(['status' => 'confirmed']);

            return redirect()->route('payment.success', $booking->id)->with('success', 'Payment successful via ' . ucfirst($method) . ' Simulator!');
        } else {
            // Create failed payment record
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => null,
                'payment_method' => $method,
                'amount' => $booking->grand_total,
                'status' => 'failed',
                'payment_details' => [
                    'simulator' => true,
                    'error' => 'Simulated card declined or transaction cancelled by user'
                ]
            ]);

            // Release vehicle back to available
            $booking->vehicle->update(['status' => 'available']);
            $booking->update(['status' => 'cancelled']);

            return redirect()->route('vehicles.show', $booking->vehicle->slug)
                ->withErrors(['payment' => 'Payment transaction was declined or cancelled. Please try again.']);
        }
    }

    public function success($id)
    {
        $booking = Booking::with(['vehicle', 'pickupLocation', 'returnLocation', 'payments'])->findOrFail($id);
        return view('payment.success', compact('booking'));
    }
}
