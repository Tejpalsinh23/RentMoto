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
                    <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link"><i class="fa-solid fa-ticket me-2"></i>Coupons</a>
                    <a href="{{ route('admin.locations.index') }}" class="admin-nav-link"><i class="fa-solid fa-map-marker-alt me-2"></i>Locations</a>
                    <a href="{{ route('admin.faqs.index') }}" class="admin-nav-link"><i class="fa-solid fa-question-circle me-2"></i>FAQs</a>
                    <a href="{{ route('admin.blogs.index') }}" class="admin-nav-link"><i class="fa-solid fa-blog me-2"></i>Blog Posts</a>
                    <a href="{{ route('admin.contacts.index') }}" class="admin-nav-link active"><i class="fa-solid fa-envelope me-2"></i>Contact Messages</a>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Reports</a>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link"><i class="fa-solid fa-cog me-2"></i>System Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-inbox me-2 text-primary"></i>Inbound Enquiries</h3>

            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="table-responsive">
                    <table class="table align-middle text-start table-hover border-top border-secondary-subtle">
                        <thead>
                            <tr class="small text-muted">
                                <th>From</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $msg->name }}</span>
                                        <small class="text-secondary">{{ $msg->email }}</small>
                                    </td>
                                    <td><span class="fw-semibold text-dark">{{ $msg->subject ?? 'Inquiry' }}</span></td>
                                    <td><span class="small text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $msg->message }}</span></td>
                                    <td>
                                        @if($msg->is_read)
                                            <span class="badge bg-success-subtle text-success text-uppercase">Read</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">New</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!$msg->is_read)
                                            <form action="{{ route('admin.contacts.read', $msg->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-envelope-open"></i> Read</button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Checked</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted small py-4">No contact messages received yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $messages->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
