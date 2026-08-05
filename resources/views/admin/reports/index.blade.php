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

    .report-card {
        border-radius: 1rem;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        text-align: center;
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
                    <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link"><i class="fa-solid fa-ticket me-2"></i>Coupons</a>
                    <a href="{{ route('admin.locations.index') }}" class="admin-nav-link"><i class="fa-solid fa-map-marker-alt me-2"></i>Locations</a>
                    <a href="{{ route('admin.faqs.index') }}" class="admin-nav-link"><i class="fa-solid fa-question-circle me-2"></i>FAQs</a>
                    <a href="{{ route('admin.blogs.index') }}" class="admin-nav-link"><i class="fa-solid fa-blog me-2"></i>Blog Posts</a>
                    <a href="{{ route('admin.contacts.index') }}" class="admin-nav-link"><i class="fa-solid fa-envelope me-2"></i>Contact Messages</a>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link active"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Reports</a>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link"><i class="fa-solid fa-cog me-2"></i>System Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-file-excel me-2 text-primary"></i>Analytical Report Exports</h3>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="report-card shadow-sm">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-hand-holding-dollar fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Revenue Reports</h5>
                        <p class="text-muted small">Export verified transactions, itemized billing amounts, tax totals, and methods (Stripe, Paypal, COD).</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'revenue']) }}" class="btn btn-outline-primary w-100 fw-bold"><i class="fa-solid fa-download me-1"></i> Export Revenue CSV</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="report-card shadow-sm">
                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-calendar-check fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Reservation Reports</h5>
                        <p class="text-muted small">Export all customer bookings, pickup/return dates, durations, coupon records, and current reservation states.</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'bookings']) }}" class="btn btn-outline-success w-100 fw-bold"><i class="fa-solid fa-download me-1"></i> Export Bookings CSV</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="report-card shadow-sm">
                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-car fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Fleet Inventory Reports</h5>
                        <p class="text-muted small">Export full vehicle listings including license plates, model years, fuel specs, seating capacities, and rates.</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'vehicles']) }}" class="btn btn-outline-info w-100 fw-bold"><i class="fa-solid fa-download me-1"></i> Export Fleet CSV</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="report-card shadow-sm">
                        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-users fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Customer Profiles Reports</h5>
                        <p class="text-muted small">Export list of registered customers, contact numbers, email addresses, billing addresses, and registration dates.</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'customers']) }}" class="btn btn-outline-warning w-100 fw-bold"><i class="fa-solid fa-download me-1"></i> Export Customers CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
