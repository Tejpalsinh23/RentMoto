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
                    <a href="{{ route('admin.categories.index') }}" class="admin-nav-link active"><i class="fa-solid fa-folder me-2"></i>Categories</a>
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
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-folder-open me-2 text-primary"></i>Vehicle Categories</h3>

            <div class="row g-4">
                <!-- Left: Create Category Form -->
                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-3">Create Category</h5>
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Category Name</label>
                                <input type="text" name="name" class="form-control border-secondary-subtle" placeholder="e.g. Electric Cars" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Description</label>
                                <textarea name="description" class="form-control border-secondary-subtle" rows="3" placeholder="Category brief..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-muted">Cover Image</label>
                                <input type="file" name="image" class="form-control border-secondary-subtle" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Category</button>
                        </form>
                    </div>
                </div>

                <!-- Right: Categories List Table -->
                <div class="col-md-8">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <div class="table-responsive">
                            <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $cat)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $cat->name }}</span>
                                            </td>
                                            <td><code class="small">{{ $cat->slug }}</code></td>
                                            <td><span class="small text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $cat->description }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border shadow-none" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted small py-4">No categories created yet.</td>
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
