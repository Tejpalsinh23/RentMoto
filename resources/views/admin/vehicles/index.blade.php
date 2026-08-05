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
                    <a href="{{ route('admin.vehicles.index') }}" class="admin-nav-link active"><i class="fa-solid fa-car me-2"></i>Vehicles Fleet</a>
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

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-car me-2 text-primary"></i>Manage Fleet Vehicles</h3>
                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add Vehicle</a>
            </div>

            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="table-responsive">
                    <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                        <thead>
                            <tr class="small text-muted">
                                <th>Vehicle</th>
                                <th>Plate #</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Daily Rate</th>
                                <th>Status</th>
                                <th>Badges</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $v)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 60px; height: 40px; overflow: hidden; border-radius: 0.375rem; background: #e2e8f0;">
                                                @if($v->main_image)
                                                    <img src="{{ $v->main_image }}" class="w-100 h-100 object-fit-cover" alt="Image">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $v->name }}</span>
                                                <small class="text-secondary">{{ $v->model_year }} | {{ $v->transmission }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="fw-semibold text-uppercase">{{ $v->license_plate }}</span></td>
                                    <td><span class="small">{{ $v->category->name }}</span></td>
                                    <td><span class="small">{{ $v->brand->name }}</span></td>
                                    <td><span class="fw-bold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($v->price_per_day, 2) }}</span></td>
                                    <td>
                                        @if($v->status === 'available')
                                            <span class="badge bg-success-subtle text-success text-uppercase">Available</span>
                                        @elseif($v->status === 'maintenance')
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">Maintenance</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">Booked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($v->is_featured)
                                            <span class="badge bg-primary text-uppercase" style="font-size: 0.7rem;">Featured</span>
                                        @endif
                                        @if($v->is_popular)
                                            <span class="badge bg-info text-uppercase" style="font-size: 0.7rem;">Popular</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.vehicles.edit', $v->id) }}" class="btn btn-sm btn-light border shadow-none" title="Edit"><i class="fa-solid fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to soft delete this vehicle?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border shadow-none" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted small">No vehicles added yet. Click 'Add Vehicle' to start building your fleet catalog!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $vehicles->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
