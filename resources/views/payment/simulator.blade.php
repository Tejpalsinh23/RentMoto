@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 600px;">
    <div class="card card-custom p-4 p-md-5 border-0 shadow-lg" style="background-color: var(--card-bg);">
        <div class="text-center mb-4">
            @if($method === 'stripe')
                <h2 class="fw-bold text-primary"><i class="fa-brands fa-cc-stripe fs-1 me-2"></i>Stripe Checkout</h2>
            @elseif($method === 'paypal')
                <h2 class="fw-bold text-info"><i class="fa-brands fa-paypal fs-1 me-2"></i>PayPal Checkout</h2>
            @elseif($method === 'razorpay')
                <h2 class="fw-bold text-warning"><i class="fa-solid fa-wallet fs-1 me-2"></i>Razorpay Gateway</h2>
            @endif
            <p class="text-muted">Simulated Payment Sandbox</p>
        </div>

        <div class="alert alert-warning border-0 p-3 mb-4 text-center rounded-3">
            <i class="fa-solid fa-triangle-exclamation text-warning fs-4 mb-2 d-block"></i>
            <span class="small fw-semibold text-secondary">This application is running in **Payment Simulator Mode**. No real funds will be charged. Choose an outcome below to test the rental status updates.</span>
        </div>

        <!-- Booking Metadata -->
        <div class="bg-light-subtle rounded-3 p-3 mb-4 border border-secondary-subtle small">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Booking Reference:</span>
                <span class="fw-bold text-dark">{{ $booking->booking_number }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Rented Vehicle:</span>
                <span class="fw-bold text-dark">{{ $booking->vehicle->name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Total Rental Days:</span>
                <span class="fw-bold text-dark">{{ $booking->total_days }} Days</span>
            </div>
            <hr class="my-2 opacity-25">
            <div class="d-flex justify-content-between fs-6 fw-bold text-primary">
                <span>Amount Due:</span>
                <span>{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->grand_total, 2) }}</span>
            </div>
        </div>

        @if($method === 'stripe')
            <!-- Simulated Credit Card Form Interface -->
            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted">Cardholder Name</label>
                <input type="text" class="form-control border-secondary-subtle" value="{{ Auth::user()->name }}" disabled>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted">Card Number</label>
                <div class="input-group">
                    <input type="text" class="form-control border-secondary-subtle" value="4242 4242 4242 4242" disabled>
                    <span class="input-group-text"><i class="fa-solid fa-credit-card text-muted"></i></span>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label small fw-semibold text-muted">Expiry Date</label>
                    <input type="text" class="form-control border-secondary-subtle" value="12/29" disabled>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-semibold text-muted">CVC Code</label>
                    <input type="password" class="form-control border-secondary-subtle" value="***" disabled>
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-6">
                <form action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <input type="hidden" name="method" value="{{ $method }}">
                    <input type="hidden" name="outcome" value="success">
                    <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold"><i class="fa-solid fa-check-circle me-1"></i> Simulate Success</button>
                </form>
            </div>
            <div class="col-6">
                <form action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <input type="hidden" name="method" value="{{ $method }}">
                    <input type="hidden" name="outcome" value="failure">
                    <button type="submit" class="btn btn-danger w-100 py-2.5 fw-bold"><i class="fa-solid fa-times-circle me-1"></i> Simulate Decline</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
