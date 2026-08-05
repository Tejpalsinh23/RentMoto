@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="text-center py-3 border-bottom border-secondary-subtle mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold fs-4" style="width: 60px; height: 60px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</h5>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="nav flex-column nav-pills gap-1">
                    <a href="{{ route('dashboard') }}" class="nav-link active py-2.5"><i class="fa-solid fa-gauge me-2"></i>Dashboard Summary</a>
                    <a href="{{ route('dashboard.bookings') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-calendar-check me-2"></i>My Rentals</a>
                    <a href="{{ route('dashboard.wishlist') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-heart me-2"></i>Saved Wishlist</a>
                    <a href="{{ route('dashboard.reviews') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-star me-2"></i>Ratings & Reviews</a>
                    <a href="{{ route('dashboard.settings') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-sliders me-2"></i>Profile Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Summary content -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-gauge-high me-2 text-primary"></i>My Dashboard Summary</h3>

            <!-- Stats grid -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card-custom p-4 border-0 shadow-sm text-center d-flex flex-column align-items-center" style="background-color: var(--card-bg);">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 55px; height: 55px;">
                            <i class="fa-solid fa-calendar-check fs-4"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark">{{ $totalBookings }}</h4>
                        <span class="small text-muted fw-bold text-uppercase">Total Bookings</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom p-4 border-0 shadow-sm text-center d-flex flex-column align-items-center" style="background-color: var(--card-bg);">
                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 55px; height: 55px;">
                            <i class="fa-solid fa-road fs-4"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark">{{ $activeBookings }}</h4>
                        <span class="small text-muted fw-bold text-uppercase">Active Rentals</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom p-4 border-0 shadow-sm text-center d-flex flex-column align-items-center" style="background-color: var(--card-bg);">
                        <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 55px; height: 55px;">
                            <i class="fa-solid fa-heart fs-4"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark">{{ $wishlistCount }}</h4>
                        <span class="small text-muted fw-bold text-uppercase">Wishlist Items</span>
                    </div>
                </div>
            </div>

            <!-- Recent bookings table -->
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-history me-2 text-primary"></i>Recent Rental Requests</h5>
                <div class="table-responsive">
                    <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                        <thead>
                            <tr class="small text-muted">
                                <th>Booking #</th>
                                <th>Vehicle</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $bk)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $bk->booking_number }}</span></td>
                                    <td><span class="fw-semibold">{{ $bk->vehicle->name }}</span></td>
                                    <td><small>{{ $bk->pickup_date->format('Y-m-d') }}</small></td>
                                    <td><small>{{ $bk->return_date->format('Y-m-d') }}</small></td>
                                    <td><span class="fw-bold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($bk->grand_total, 2) }}</span></td>
                                    <td>
                                        @if($bk->status === 'confirmed')
                                            <span class="badge bg-success text-uppercase">Confirmed</span>
                                        @elseif($bk->status === 'completed')
                                            <span class="badge bg-primary text-uppercase">Completed</span>
                                        @elseif($bk->status === 'cancelled')
                                            <span class="badge bg-danger text-uppercase">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-uppercase">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($bk->status === 'confirmed' || $bk->status === 'completed')
                                            <a href="{{ route('booking.invoice.download', $bk->booking_number) }}" class="btn btn-sm btn-outline-primary shadow-none" title="Download Invoice"><i class="fa-solid fa-file-pdf"></i> PDF</a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4 small">No reservations found. Start browsing vehicles to create bookings!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
