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
        <!-- Admin Panel Navigation Sidebar -->
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
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link active"><i class="fa-solid fa-chart-line me-2"></i>Dashboard</a>
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
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Reports</a>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link"><i class="fa-solid fa-cog me-2"></i>System Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Panel -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-chart-bar me-2 text-primary"></i>Admin Operations Dashboard</h3>

            <!-- Stats widgets -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <small class="text-muted fw-semibold text-uppercase d-block mb-1">Total Users</small>
                        <h3 class="fw-extrabold mb-0 text-dark">{{ $stats['total_users'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <small class="text-muted fw-semibold text-uppercase d-block mb-1">Total Vehicles</small>
                        <h3 class="fw-extrabold mb-0 text-dark">{{ $stats['total_vehicles'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <small class="text-muted fw-semibold text-uppercase d-block mb-1">Available Fleet</small>
                        <h3 class="fw-extrabold mb-0 text-success">{{ $stats['available_vehicles'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <small class="text-muted fw-semibold text-uppercase d-block mb-1">Gross Revenue</small>
                        <h3 class="fw-extrabold mb-0 text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($stats['revenue'], 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-7">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3">Gross Revenue Growth Trend</h5>
                        <div style="height: 300px; position: relative;">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3">Fleet category Split</h5>
                        <div style="height: 300px; position: relative;">
                            <canvas id="categorySplitChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent bookings table -->
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Incoming Bookings Log</h5>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary shadow-none">View All Bookings</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                        <thead>
                            <tr class="small text-muted">
                                <th>Ref #</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Pickup Date</th>
                                <th>Total Days</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $bk)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $bk->booking_number }}</span></td>
                                    <td><span class="fw-semibold">{{ $bk->user->name }}</span></td>
                                    <td><span>{{ $bk->vehicle->name }}</span></td>
                                    <td><small>{{ $bk->pickup_date->format('Y-m-d') }}</small></td>
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
                                        <a href="{{ route('admin.bookings.show', $bk->id) }}" class="btn btn-sm btn-light border shadow-none" title="Manage Booking"><i class="fa-solid fa-eye text-primary"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4 small">No bookings recorded yet.</td>
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

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Monthly Revenue Chart (Line Chart)
    const ctxRevenue = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartMonthlyLabels) !!},
            datasets: [{
                label: 'Gross Earnings ($)',
                data: {!! json_encode($chartMonthlyData) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Category Split Chart (Doughnut Chart)
    const ctxCategory = document.getElementById('categorySplitChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartCategoryLabels) !!},
            datasets: [{
                data: {!! json_encode($chartCategoryData) !!},
                backgroundColor: [
                    '#4f46e5', '#06b6d4', '#eab308', '#22c55e', '#ef4444', '#a855f7', '#ec4899', '#f97316'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
