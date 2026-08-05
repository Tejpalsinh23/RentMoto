@extends('layouts.app')

@section('styles')
<style>
    .spec-badge {
        background-color: rgba(79, 70, 229, 0.06);
        border: 1px solid rgba(79, 70, 229, 0.15);
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .spec-badge:hover {
        transform: translateY(-2px);
    }

    .spec-icon {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    /* Calculator Sticky sidebar */
    .sticky-booking-card {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    .feature-item {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5 animated-fade-in">
    <!-- Back to browse link -->
    <a href="{{ route('vehicles.index') }}" class="btn btn-link text-decoration-none text-muted mb-4 p-0"><i class="fa-solid fa-arrow-left me-1"></i> Back to Fleet Directory</a>

    <div class="row">
        <!-- Vehicle specs & gallery column -->
        <div class="col-lg-8 mb-4">
            <!-- Header title -->
            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary text-uppercase fw-bold mb-2">{{ $vehicle->category->name }}</span>
                    <h1 class="fw-bold mb-1">{{ $vehicle->name }}</h1>
                    <p class="text-muted"><i class="fa-solid fa-tags me-1"></i> Brand: <span class="fw-semibold text-primary">{{ $vehicle->brand->name }}</span> | License: <span class="fw-semibold">{{ $vehicle->license_plate }}</span></p>
                </div>
                <!-- Wishlist heart button -->
                <div>
                    <button class="btn btn-outline-danger d-flex align-items-center gap-2 py-2 px-3 shadow-sm border-secondary-subtle" onclick="toggleWishlist({{ $vehicle->id }})" id="wishlistBtn">
                        <i class="fa-regular fa-heart" id="wishlistIcon"></i> <span id="wishlistText">Save to Wishlist</span>
                    </button>
                </div>
            </div>

            <!-- Image display -->
            <div class="card-custom overflow-hidden mb-4 p-2 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div style="height: 450px; overflow: hidden; border-radius: 0.75rem; background: #e2e8f0;">
                    @if($vehicle->main_image)
                        <img src="{{ $vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $vehicle->name }}">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car fs-1"></i></div>
                    @endif
                </div>

                <!-- Secondary Gallery Images Slider -->
                @if($vehicle->images->count() > 0)
                    <div class="swiper gallery-slider mt-3">
                        <div class="swiper-wrapper">
                            @foreach($vehicle->images as $img)
                                <div class="swiper-slide" style="height: 100px; width: 150px; overflow: hidden; border-radius: 0.5rem;">
                                    <img src="{{ $img->image_path }}" class="w-100 h-100 object-fit-cover cursor-pointer" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Key Specifications Grid -->
            <h4 class="fw-bold mb-3 mt-5">Core Specifications</h4>
            <div class="row g-3 mb-5">
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-gears spec-icon"></i>
                        <h6 class="fw-bold mb-0">Transmission</h6>
                        <small class="text-muted">{{ $vehicle->transmission }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-chair spec-icon"></i>
                        <h6 class="fw-bold mb-0">Seats</h6>
                        <small class="text-muted">{{ $vehicle->seats }} Adults</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-gas-pump spec-icon"></i>
                        <h6 class="fw-bold mb-0">Fuel Type</h6>
                        <small class="text-muted">{{ $vehicle->fuel_type }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-gauge spec-icon"></i>
                        <h6 class="fw-bold mb-0">Mileage</h6>
                        <small class="text-muted">{{ $vehicle->mileage ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-calendar spec-icon"></i>
                        <h6 class="fw-bold mb-0">Model Year</h6>
                        <small class="text-muted">{{ $vehicle->model_year }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-bolt-lightning spec-icon"></i>
                        <h6 class="fw-bold mb-0">Engine Size</h6>
                        <small class="text-muted">{{ $vehicle->engine_size ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-palette spec-icon"></i>
                        <h6 class="fw-bold mb-0">Color</h6>
                        <small class="text-muted">{{ $vehicle->color ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-badge">
                        <i class="fa-solid fa-circle-check spec-icon text-success"></i>
                        <h6 class="fw-bold mb-0">Availability</h6>
                        <small class="text-success fw-semibold">{{ ucfirst($vehicle->status) }}</small>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <h4 class="fw-bold mb-3">Vehicle Overview</h4>
            <p class="text-muted mb-5" style="line-height: 1.7;">{{ $vehicle->description }}</p>

            <!-- Features -->
            @if(!empty($vehicle->features))
                <h4 class="fw-bold mb-3">Included Amenities</h4>
                <div class="row g-3 mb-5">
                    @foreach($vehicle->features as $feat)
                        <div class="col-md-4 col-6">
                            <div class="feature-item">
                                <i class="fa-solid fa-circle-check text-primary"></i>
                                <span class="text-secondary fw-semibold">{{ $feat }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Customer Reviews -->
            <h4 class="fw-bold mb-3">Customer Ratings & Reviews ({{ $vehicle->reviews->count() }})</h4>
            <div class="card-custom p-4 border-0 shadow-sm mb-4" style="background-color: var(--card-bg);">
                <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                    <div class="text-center bg-light-subtle p-3 rounded-3" style="width: 130px; border: 1px solid var(--border-color);">
                        <h1 class="display-5 fw-extrabold text-primary mb-1">{{ $vehicle->average_rating }}</h1>
                        <span class="text-warning small"><i class="fa-solid fa-star"></i> Overall</span>
                    </div>
                    <div class="ms-md-3">
                        <p class="text-muted small mb-0">Ratings are gathered from verified rentals of the vehicle. All reviewers have rented and driven this specific model.</p>
                    </div>
                </div>

                <div class="review-list">
                    @forelse($vehicle->reviews as $rev)
                        <div class="border-top pt-4 mt-4">
                            <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        {{ substr($rev->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $rev->user->name }}</h6>
                                        <small class="text-muted">{{ $rev->created_at->format('F d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="text-warning small">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa-{{ $i <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-muted small mb-0 font-italic">"{{ $rev->comment }}"</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small border-top pt-4">No reviews recorded yet for this vehicle. Be the first to rent and leave a review!</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Rental Booking Calculator Sidebar -->
        <div class="col-lg-4">
            <div class="card card-custom sticky-booking-card p-4 border-0 shadow-lg">
                <h5 class="fw-bold mb-3 border-bottom pb-3"><i class="fa-solid fa-calculator me-2 text-primary"></i>Rental Calculator</h5>
                
                <form action="{{ route('booking.checkout') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Pickup Location</label>
                        <select name="pickup_location_id" class="form-select border-secondary-subtle" required>
                            <option value="">Choose Depot</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('pickup_location') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Return Location</label>
                        <select name="return_location_id" class="form-select border-secondary-subtle" required>
                            <option value="">Choose Depot</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('pickup_location') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Pickup Date</label>
                        <input type="date" name="pickup_date" id="pickup_date" class="form-control border-secondary-subtle" min="{{ date('Y-m-d') }}" value="{{ request('pickup_date') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Return Date</label>
                        <input type="date" name="return_date" id="return_date" class="form-control border-secondary-subtle" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('return_date') }}" required>
                    </div>

                    <!-- Coupon application -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Coupon Code (Optional)</label>
                        <div class="input-group">
                            <input type="text" name="coupon_code" id="coupon_code" class="form-control border-secondary-subtle" placeholder="e.g. RENT20" value="{{ request('coupon_code') }}">
                            <button type="button" class="btn btn-outline-primary" id="applyCouponBtn">Apply</button>
                        </div>
                        <small class="form-text d-none" id="couponStatusMessage"></small>
                    </div>

                    <!-- AJAX Dynamic Cost Calculations Display -->
                    <div class="bg-light-subtle rounded-3 p-3 mb-4 d-none" id="calculationsSection" style="border: 1px dashed var(--primary-color);">
                        <div class="d-flex justify-content-between small mb-2 text-secondary">
                            <span>Rental Duration:</span>
                            <span class="fw-bold text-dark" id="calcDays">0 days</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2 text-secondary">
                            <span>Daily Base Rate:</span>
                            <span class="fw-bold text-dark" id="calcPricePerDay">{{ App\Models\Setting::get('currency_symbol', '₹') }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold text-dark" id="calcSubtotal">{{ App\Models\Setting::get('currency_symbol', '₹') }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success d-none" id="discountRow">
                            <span class="fw-semibold">Discount</span>
                            <span class="fw-bold" id="calcDiscount">-{{ App\Models\Setting::get('currency_symbol', '₹') }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom border-secondary-subtle pb-3">
                            <span class="text-muted">Tax ({{ App\Models\Setting::get('tax_rate', '10') }}%)</span>
                            <span class="fw-bold text-dark" id="calcTax">{{ App\Models\Setting::get('currency_symbol', '₹') }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Total Amount</span>
                            <span class="fs-4 fw-extrabold text-primary" id="calcTotal">{{ App\Models\Setting::get('currency_symbol', '₹') }}0.00</span>
                        </div>
                    </div>

                    @auth
                        @if($vehicle->status === 'available')
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" id="bookingSubmitBtn"><i class="fa-solid fa-credit-card me-2"></i>Proceed to Reservation</button>
                        @else
                            <button type="button" class="btn btn-secondary w-100 py-3 fw-bold" disabled>Currently Unavailable</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ request()->url() }}" class="btn btn-outline-primary w-100 py-3 fw-bold">Sign In to Book Vehicle</a>
                    @endauth
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize Swiper slider for gallery
    const swiper = new Swiper('.gallery-slider', {
        slidesPerView: 'auto',
        spaceBetween: 10,
        freeMode: true,
    });

    const pickupInput = document.getElementById('pickup_date');
    const returnInput = document.getElementById('return_date');
    const couponInput = document.getElementById('coupon_code');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    
    const calculationsSection = document.getElementById('calculationsSection');
    const calcDays = document.getElementById('calcDays');
    const calcPricePerDay = document.getElementById('calcPricePerDay');
    const calcSubtotal = document.getElementById('calcSubtotal');
    const discountRow = document.getElementById('discountRow');
    const calcDiscount = document.getElementById('calcDiscount');
    const calcTax = document.getElementById('calcTax');
    const calcTotal = document.getElementById('calcTotal');
    const couponStatusMessage = document.getElementById('couponStatusMessage');

    // Run calculation check on trigger changes
    pickupInput.addEventListener('change', fetchCalculations);
    returnInput.addEventListener('change', fetchCalculations);
    applyCouponBtn.addEventListener('click', fetchCalculations);

    // Initial check on load if fields completed
    if (pickupInput.value && returnInput.value) {
        fetchCalculations();
    }

    function fetchCalculations() {
        const vehicleId = "{{ $vehicle->id }}";
        const pickupDate = pickupInput.value;
        const returnDate = returnInput.value;
        const couponCode = couponInput.value;

        if (!pickupDate || !returnDate) {
            calculationsSection.classList.add('d-none');
            return;
        }

        const formData = new FormData();
        formData.append('vehicle_id', vehicleId);
        formData.append('pickup_date', pickupDate);
        formData.append('return_date', returnDate);
        formData.append('coupon_code', couponCode);
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('booking.calculate') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calculationsSection.classList.remove('d-none');
                calcDays.innerText = data.days + ' days';
                calcPricePerDay.innerText = '{{ App\Models\Setting::get("currency_symbol", "₹") }}' + data.price_per_day;
                calcSubtotal.innerText = '{{ App\Models\Setting::get("currency_symbol", "₹") }}' + data.subtotal;
                
                if (data.discount > 0) {
                    discountRow.classList.remove('d-none');
                    calcDiscount.innerText = '-{{ App\Models\Setting::get("currency_symbol", "₹") }}' + data.discount;
                } else {
                    discountRow.classList.add('d-none');
                }
                
                calcTax.innerText = '{{ App\Models\Setting::get("currency_symbol", "₹") }}' + data.tax;
                calcTotal.innerText = '{{ App\Models\Setting::get("currency_symbol", "₹") }}' + data.total;

                if (couponCode) {
                    couponStatusMessage.classList.remove('d-none');
                    if (data.coupon_valid) {
                        couponStatusMessage.className = "form-text text-success";
                        couponStatusMessage.innerText = data.coupon_message;
                    } else {
                        couponStatusMessage.className = "form-text text-danger";
                        couponStatusMessage.innerText = data.coupon_message;
                    }
                } else {
                    couponStatusMessage.classList.add('d-none');
                }
            } else {
                calculationsSection.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Calculation error:', error);
            calculationsSection.classList.add('d-none');
        });
    }

    // Toggle Wishlist Functionality
    function toggleWishlist(vehicleId) {
        const wishlistBtn = document.getElementById('wishlistBtn');
        const wishlistIcon = document.getElementById('wishlistIcon');
        const wishlistText = document.getElementById('wishlistText');

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
            if (data.success) {
                if (data.in_wishlist) {
                    wishlistIcon.className = "fa-solid fa-heart text-danger";
                    wishlistText.innerText = "Saved in Wishlist";
                    wishlistBtn.className = "btn btn-outline-danger active d-flex align-items-center gap-2 py-2 px-3 shadow-sm border-danger";
                } else {
                    wishlistIcon.className = "fa-regular fa-heart";
                    wishlistText.innerText = "Save to Wishlist";
                    wishlistBtn.className = "btn btn-outline-danger d-flex align-items-center gap-2 py-2 px-3 shadow-sm border-secondary-subtle";
                }
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
