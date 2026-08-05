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
                <h3 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-calendar-check me-2 text-primary"></i>Manage Rental Bookings</h3>
            </div>

            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <!-- Search & Filters -->
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="Search reference code or customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select border-secondary-subtle" onchange="this.form.submit()">
                            <option value="">All Reservation Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">Filter & Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                        <thead>
                            <tr class="small text-muted">
                                <th>Booking Ref</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Days</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th class="text-end">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $bk)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $bk->booking_number }}</span></td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $bk->user->name }}</span>
                                        <small class="text-secondary">{{ $bk->user->email }}</small>
                                    </td>
                                    <td><span class="fw-semibold text-primary">{{ $bk->vehicle->name }}</span></td>
                                    <td><small>{{ $bk->pickup_date->format('Y-m-d') }}</small></td>
                                    <td><small>{{ $bk->return_date->format('Y-m-d') }}</small></td>
                                    <td><span class="small text-muted">{{ $bk->total_days }} days</span></td>
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
                                        <a href="{{ route('admin.bookings.show', $bk->id) }}" class="btn btn-sm btn-light border shadow-none" title="View details"><i class="fa-solid fa-eye text-primary"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted small">No reservation logs matching criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
