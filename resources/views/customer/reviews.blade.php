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
                    <a href="{{ route('dashboard.reviews') }}" class="nav-link active py-2.5"><i class="fa-solid fa-star me-2"></i>Ratings & Reviews</a>
                    <a href="{{ route('dashboard.settings') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-sliders me-2"></i>Profile Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Reviews and ratings -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-star me-2 text-warning"></i>Ratings & Reviews</h3>

            <div class="row g-4">
                <!-- Left: Write review form (if completed rentals without reviews) -->
                @if($pendingReviewVehicles->count() > 0)
                    <div class="col-md-5">
                        <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-edit me-1 text-primary"></i>Write Review</h5>
                            <form action="{{ route('dashboard.reviews.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted">Rented Vehicle</label>
                                    <select name="vehicle_id" class="form-select border-secondary-subtle" required>
                                        <option value="">Select vehicle to review</option>
                                        @foreach($pendingReviewVehicles as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted">Rating (Stars)</label>
                                    <select name="rating" class="form-select border-secondary-subtle" required>
                                        <option value="5">5 - Excellent (Highly Recommend)</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Average</option>
                                        <option value="2">2 - Disappointed</option>
                                        <option value="1">1 - Terrible (Not Recommended)</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-semibold text-muted">Review Comment</label>
                                    <textarea name="comment" class="form-control border-secondary-subtle" rows="4" placeholder="Share your driving experience..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Right: Reviews history list -->
                <div class="{{ $pendingReviewVehicles->count() > 0 ? 'col-md-7' : 'col-12' }}">
                    <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <h5 class="fw-bold mb-4"><i class="fa-solid fa-history me-1 text-primary"></i>Submission History</h5>
                        
                        <div class="review-timeline">
                            @forelse($reviews as $rev)
                                <div class="pb-4 mb-4 border-bottom border-secondary-subtle">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <span class="fw-bold text-dark">{{ $rev->vehicle->name }}</span>
                                        <span class="text-warning small">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-{{ $i <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">"{{ $rev->comment }}"</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-secondary">{{ $rev->created_at->format('Y-m-d') }}</small>
                                        @if($rev->is_approved)
                                            <span class="badge bg-success-subtle text-success text-uppercase">Approved</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">Pending Approval</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted small">
                                    <i class="fa-solid fa-comment-slash fs-1 text-primary mb-3 d-block"></i>No reviews submitted yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
