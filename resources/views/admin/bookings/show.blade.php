@extends('layouts.app')

@section('styles')
<style>
    .admin-nav-link {
        font-weight: 500;
        color: var(--text-color) !important;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .admin-nav-link:hover, .admin-nav-link.active {
        color: var(--primary-color) !important;
        background-color: rgba(79, 70, 229, 0.08);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-5 px-md-5 animated-fade-in">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="text-center py-3 border-bottom border-secondary-subtle mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold" style="width: 50px; height: 50px;">
                        AD
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Administrator Portal</h6>
                    <small class="text-muted">{{ Auth::user()->name }}</small>
                </div>
                <div class="nav flex-column gap-1 small">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link"><i class="fa-solid fa-chart-line me-2"></i>Dashboard</a>
                    <a href="{{ route('admin.vehicles.index') }}" class="admin-nav-link"><i class="fa-solid fa-car me-2"></i>Vehicles Fleet</a>
                    <a href="{{ route('admin.categories.index') }}" class="admin-nav-link"><i class="fa-solid fa-folder me-2"></i>Categories</a>
                    <a href="{{ route('admin.brands.index') }}" class="admin-nav-link"><i class="fa-solid fa-copyright me-2"></i>Brands</a>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link active"><i class="fa-solid fa-calendar-check me-2"></i>Bookings Log</a>
                    <a href="{{ route('admin.customers.index') }}" class="admin-nav-link"><i class="fa-solid fa-users me-2"></i>Customers</a>
                    <a href="{{ route('admin.payments.index') }}" class="admin-nav-link"><i class="fa-solid fa-money-bill-wave me-2"></i>Payments</a>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link"><i class="fa-solid fa-star me-2"></i>Reviews</a>
                    <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link"><i class="fa-solid fa-ticket me-2"></i>Coupons</a>
                    <a href="{{ route('admin.locations.index') }}" class="admin-nav-link"><i class="fa-solid fa-map-marker-alt me-2"></i>Locations</a>
                    <a href="{{ route('admin.faqs.index') }}" class="admin-nav-link"><i class="fa-solid fa-question-circle me-2"></i>FAQs</a>
                    <a href="{{ route('admin.blogs.index') }}" class="admin-nav-link"><i class="fa-solid fa-blog me-2"></i>Blog Posts</a>
                    <a href="{{ route('admin.contacts.index') }}" class="admin-nav-link"><i class="fa-solid fa-envelope me-2"></i>Contact Messages</a>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Reports</a>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link"><i class="fa-solid fa-cog me-2"></i>System Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-file-invoice me-2 text-primary"></i>Booking Overview: {{ $booking->booking_number }}</h3>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to Log</a>
            </div>

            <div class="row g-4">
                <!-- Left: Booking Specifications -->
                <div class="col-lg-8">
                    <div class="card card-custom p-4 border-0 shadow-sm mb-4" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Rental specifics</h5>
                        
                        <!-- Rented Vehicle -->
                        <div class="d-flex align-items-center mb-4 gap-3">
                            <div style="width: 80px; height: 50px; overflow: hidden; border-radius: 0.375rem; background: #e2e8f0;">
                                @if($booking->vehicle->main_image)
                                    <img src="{{ $booking->vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="Image">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car"></i></div>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $booking->vehicle->name }}</h6>
                                <small class="text-secondary">License Plate: {{ $booking->vehicle->license_plate }} | Daily Rate: {{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->price_per_day, 2) }}</small>
                            </div>
                        </div>

                        <!-- Routing details -->
                        <div class="row g-3 small mb-4">
                            <div class="col-md-6">
                                <span class="text-muted d-block">Pickup Depot:</span>
                                <span class="fw-bold text-dark">{{ $booking->pickupLocation->name }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block">Return Depot:</span>
                                <span class="fw-bold text-dark">{{ $booking->returnLocation->name }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block">Pickup Date:</span>
                                <span class="fw-bold text-dark">{{ $booking->pickup_date->format('Y-m-d') }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block">Return Date:</span>
                                <span class="fw-bold text-dark">{{ $booking->return_date->format('Y-m-d') }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block">Rental Period:</span>
                                <span class="fw-bold text-primary">{{ $booking->total_days }} Days</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block">Notes:</span>
                                <span class="text-secondary">{{ $booking->notes ?? 'None' }}</span>
                            </div>
                        </div>

                        <!-- Payment metadata -->
                        <h5 class="fw-bold mb-3 border-top pt-4">Payments transactions</h5>
                        <div class="table-responsive">
                            <table class="table align-middle text-start table-sm small">
                                <thead>
                                    <tr class="text-muted">
                                        <th>Transaction ID</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($booking->payments as $payment)
                                        <tr>
                                            <td><span class="fw-bold text-dark">{{ $payment->transaction_id ?? 'N/A' }}</span></td>
                                            <td class="text-uppercase"><span class="fw-semibold text-secondary">{{ $payment->payment_method }}</span></td>
                                            <td><span class="fw-bold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($payment->amount, 2) }}</span></td>
                                            <td>
                                                @if($payment->status === 'paid')
                                                    <span class="badge bg-success text-uppercase">Paid</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="badge bg-danger text-uppercase">Failed</span>
                                                @else
                                                    <span class="badge bg-warning text-uppercase">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No transaction logs recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Customer details & status actions -->
                <div class="col-lg-4">
                    <!-- Status card -->
                    <div class="card card-custom p-4 border-0 shadow-sm mb-4" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Status Actions</h5>
                        <div class="mb-4">
                            <small class="text-muted d-block mb-1">Current State:</small>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success text-uppercase fs-6">Confirmed</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge bg-primary text-uppercase fs-6">Completed</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="badge bg-danger text-uppercase fs-6">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-uppercase fs-6">Pending Approval</span>
                            @endif
                        </div>

                        <!-- Actions selectors -->
                        <div class="d-grid gap-2">
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold"><i class="fa-solid fa-check me-1"></i> Approve Booking</button>
                                </form>
                                <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger w-100 py-2.5 fw-bold"><i class="fa-solid fa-times me-1"></i> Reject Booking</button>
                                </form>
                            @elseif($booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold"><i class="fa-solid fa-flag-checkered me-1"></i> Mark as Completed</button>
                                </form>
                                <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-outline-danger w-100 py-2.5 fw-semibold"><i class="fa-solid fa-ban me-1"></i> Cancel Booking</button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary w-100 py-2.5 fw-bold" disabled>No Action Available</button>
                            @endif

                            @if($booking->status === 'confirmed' || $booking->status === 'completed')
                                <hr class="my-2 border-secondary opacity-25">
                                <a href="{{ route('booking.invoice.download', $booking->booking_number) }}" class="btn btn-outline-primary w-100 py-2.5 fw-semibold"><i class="fa-solid fa-file-pdf me-1"></i> Download Invoice PDF</a>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Profile Card -->
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Customer Profile</h5>
                        <div class="small">
                            <span class="text-muted d-block">Full Name:</span>
                            <span class="fw-bold text-dark d-block mb-2">{{ $booking->user->name }}</span>
                            
                            <span class="text-muted d-block">Email Address:</span>
                            <span class="fw-bold text-dark d-block mb-2">{{ $booking->user->email }}</span>
                            
                            <span class="text-muted d-block">Phone Number:</span>
                            <span class="fw-bold text-dark d-block mb-2">{{ $booking->user->phone ?? 'N/A' }}</span>
                            
                            <span class="text-muted d-block">Billing Address:</span>
                            <span class="fw-semibold text-secondary">{{ $booking->user->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
