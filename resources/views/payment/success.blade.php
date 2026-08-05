@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 650px;">
    <div class="card card-custom p-4 p-md-5 border-0 shadow-lg text-center" style="background-color: var(--card-bg);">
        <div class="mb-4">
            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px;">
                <i class="fa-solid fa-circle-check fs-1 text-success"></i>
            </div>
        </div>

        <h2 class="fw-bold text-success mb-2">Reservation Confirmed!</h2>
        <p class="text-muted mb-4">Your booking has been approved. A confirmation email has been dispatched (simulated).</p>

        <!-- Booking details card -->
        <div class="bg-light-subtle rounded-3 p-4 mb-4 border border-secondary-subtle text-start small">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fa-solid fa-receipt me-2 text-primary"></i>Receipt details</h6>
            <div class="row g-2">
                <div class="col-6">
                    <span class="text-muted">Booking Reference:</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ $booking->booking_number }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted">Vehicle Rented:</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ $booking->vehicle->name }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted">Pickup Depot:</span>
                </div>
                <div class="col-6 text-end text-truncate">
                    <span class="fw-bold text-dark">{{ $booking->pickupLocation->name }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted">Rental Period:</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ $booking->pickup_date->format('M d, Y') }} - {{ $booking->return_date->format('M d, Y') }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted">Amount Paid:</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->grand_total, 2) }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted">Payment Method:</span>
                </div>
                <div class="col-6 text-end text-uppercase">
                    <span class="fw-bold text-secondary">{{ $booking->payments->first()->payment_method ?? 'COD' }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0 p-3 mb-4 text-start rounded-3 d-flex align-items-start">
            <i class="fa-solid fa-circle-info text-info fs-5 me-3 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Pickup Instructions</h6>
                <span class="small text-secondary">Please present a valid Driver\'s License and Credit Card matching name **{{ Auth::user()->name }}** at the depot desk. Keys will be delivered immediately.</span>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-center">
            <a href="{{ route('booking.invoice.download', $booking->booking_number) }}" class="btn btn-primary px-4"><i class="fa-solid fa-file-pdf me-2"></i>Download Invoice PDF</a>
            <a href="{{ route('dashboard.bookings') }}" class="btn btn-outline-secondary px-4">View My Rentals</a>
            <a href="{{ route('home') }}" class="btn btn-link text-decoration-none text-muted">Back Home</a>
        </div>
    </div>
</div>
@endsection
