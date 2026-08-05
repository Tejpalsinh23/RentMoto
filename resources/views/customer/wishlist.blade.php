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
                    <a href="{{ route('dashboard.wishlist') }}" class="nav-link active py-2.5"><i class="fa-solid fa-heart me-2"></i>Saved Wishlist</a>
                    <a href="{{ route('dashboard.reviews') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-star me-2"></i>Ratings & Reviews</a>
                    <a href="{{ route('dashboard.settings') }}" class="nav-link py-2.5 text-reset"><i class="fa-solid fa-sliders me-2"></i>Profile Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Wishlist -->
        <div class="col-lg-9">
            <h3 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-heart me-2 text-danger"></i>My Saved Fleet</h3>

            <div class="row g-4">
                @forelse($wishlist as $item)
                    <div class="col-md-6 col-lg-4" id="wishlist-col-{{ $item->vehicle->id }}">
                        <div class="card card-custom h-100 overflow-hidden">
                            <div style="position: relative; height: 160px; overflow: hidden; background: #e2e8f0;">
                                @if($item->vehicle->main_image)
                                    <img src="{{ $item->vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->vehicle->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car fs-3"></i></div>
                                @endif
                                <!-- Remove heart icon -->
                                <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" onclick="removeFromWishlist({{ $item->vehicle->id }})" title="Remove item">
                                    <i class="fa-solid fa-heart text-danger"></i>
                                </button>
                            </div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold small">{{ $item->vehicle->category->name }}</span>
                                    <h5 class="fw-bold card-title mt-1 mb-2">{{ $item->vehicle->name }}</h5>
                                    <div class="text-primary fw-extrabold mb-3">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($item->vehicle->price_per_day, 2) }} <small class="text-muted">/ day</small></div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('vehicles.show', $item->vehicle->slug) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-key me-1"></i>Rent Vehicle</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 card card-custom border-0 shadow-sm bg-transparent">
                        <i class="fa-solid fa-heart-crack fs-1 text-primary mb-3"></i>
                        <h5 class="text-muted">Your wishlist is currently empty.</h5>
                        <p class="text-secondary small">Bookmark vehicles in the fleet catalog to review them here.</p>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-2">Find Vehicles</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function removeFromWishlist(vehicleId) {
        const formData = new FormData();
        formData.append('vehicle_id', vehicleId);
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && !data.in_wishlist) {
                const element = document.getElementById('wishlist-col-' + vehicleId);
                element.remove();
                
                // If all removed, reload to show empty state
                const remaining = document.querySelectorAll('[id^="wishlist-col-"]');
                if (remaining.length === 0) {
                    location.reload();
                }
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
