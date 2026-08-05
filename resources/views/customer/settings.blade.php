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
                    <a href="{{ route('dashboard') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-gauge me-2"></i>Dashboard Summary</a>
                    <a href="{{ route('dashboard.bookings') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-calendar-check me-2"></i>My Rentals</a>
                    <a href="{{ route('dashboard.wishlist') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-heart me-2"></i>Saved Wishlist</a>
                    <a href="{{ route('dashboard.reviews') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-star me-2"></i>Ratings & Reviews</a>
                    <a href="{{ route('dashboard.settings') }}" class="nav-link active py-2.5"><i class="fa-solid fa-sliders me-2"></i>Profile Settings</a>
                </div>
            </div>
        </div>

        <!-- Main settings forms -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-sliders me-2 text-primary"></i>Profile Settings</h3>

            <div class="row g-4">
                <!-- Profile details form -->
                <div class="col-md-6">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-user-circle me-1 text-primary"></i>Renter Information</h5>
                        <form action="{{ route('dashboard.profile.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control border-secondary-subtle" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control border-secondary-subtle" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Phone Number</label>
                                <input type="text" name="phone" class="form-control border-secondary-subtle" value="{{ Auth::user()->phone }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-muted">Address</label>
                                <textarea name="address" class="form-control border-secondary-subtle" rows="2">{{ Auth::user()->address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </form>
                    </div>
                </div>

                <!-- Password reset form -->
                <div class="col-md-6">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-lock me-1 text-primary"></i>Update Password</h5>
                        <form action="{{ route('dashboard.password.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Current Password</label>
                                <input type="password" name="current_password" class="form-control border-secondary-subtle" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">New Password</label>
                                <input type="password" name="password" class="form-control border-secondary-subtle" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-muted">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control border-secondary-subtle" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
