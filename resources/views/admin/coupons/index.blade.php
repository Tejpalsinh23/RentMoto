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
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link"><i class="fa-solid fa-calendar-check me-2"></i>Bookings Log</a>
                    <a href="{{ route('admin.customers.index') }}" class="admin-nav-link"><i class="fa-solid fa-users me-2"></i>Customers</a>
                    <a href="{{ route('admin.payments.index') }}" class="admin-nav-link"><i class="fa-solid fa-money-bill-wave me-2"></i>Payments</a>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link"><i class="fa-solid fa-star me-2"></i>Reviews</a>
                    <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link active"><i class="fa-solid fa-ticket me-2"></i>Coupons</a>
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
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-ticket-alt me-2 text-primary"></i>Promotion Coupons</h3>

            <div class="row g-4">
                <!-- Left: Create Coupon Form -->
                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3">Create Coupon</h5>
                        <form action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Promo Code</label>
                                <input type="text" name="code" class="form-control border-secondary-subtle" placeholder="e.g. DRIVE10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Type</label>
                                <select name="type" class="form-select border-secondary-subtle" required>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Cash Amount ($)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Discount Value</label>
                                <input type="number" name="value" class="form-control border-secondary-subtle" step="0.01" placeholder="e.g. 10.00" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                <input type="date" name="start_date" class="form-control border-secondary-subtle" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control border-secondary-subtle" value="{{ date('Y-m-d', strtotime('+3 months')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Min Booking value</label>
                                <input type="number" name="min_booking_amount" class="form-control border-secondary-subtle" value="0.00" step="0.01">
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-muted">Usage Limit</label>
                                <input type="number" name="usage_limit" class="form-control border-secondary-subtle" placeholder="Leave empty for unlimited">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Coupon</button>
                        </form>
                    </div>
                </div>

                <!-- Right: Coupons Table -->
                <div class="col-md-8">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <div class="table-responsive">
                            <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>Code</th>
                                        <th>Discount</th>
                                        <th>Dates</th>
                                        <th>Min Spend</th>
                                        <th>Used / Limit</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary text-uppercase fs-7">{{ $coupon->code }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    @if($coupon->type === 'percentage')
                                                        {{ (float) $coupon->value }}% Off
                                                    @else
                                                        {{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($coupon->value, 2) }} Off
                                                    @endif
                                                </span>
                                            </td>
                                            <td><small>{{ $coupon->start_date->format('Y-m-d') }} to {{ $coupon->expiry_date->format('Y-m-d') }}</small></td>
                                            <td><span class="small">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($coupon->min_booking_amount, 2) }}</span></td>
                                            <td><span class="small text-muted">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border shadow-none" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted small py-4">No promo coupons defined.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
