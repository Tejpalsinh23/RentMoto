@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in">
    <div class="row">
        <!-- Review booking options -->
        <div class="col-lg-7 mb-4">
            <div class="card card-custom p-4 border-0 shadow-sm">
                <h4 class="fw-bold mb-4 border-bottom pb-3"><i class="fa-solid fa-clipboard-list me-2 text-primary"></i>Review Reservation details</h4>
                
                <!-- Vehicle Info -->
                <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                    <div style="width: 120px; height: 80px; overflow: hidden; border-radius: 0.5rem; background: #e2e8f0;">
                        @if($vehicle->main_image)
                            <img src="{{ $vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $vehicle->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car"></i></div>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold">{{ $vehicle->category->name }}</span>
                        <h5 class="fw-bold mb-0 mt-1">{{ $vehicle->name }}</h5>
                        <small class="text-muted"><i class="fa-solid fa-building me-1"></i> Brand: {{ $vehicle->brand->name }}</small>
                    </div>
                </div>

                <!-- Locations & Dates -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light-subtle rounded-3" style="border: 1px solid var(--border-color);">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1"><i class="fa-solid fa-map-pin me-1 text-primary"></i> Pickup Point</small>
                            <span class="fw-semibold">{{ $pickupLoc->name }}</span>
                            <small class="d-block text-secondary mt-1">{{ $pickupLoc->address }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light-subtle rounded-3" style="border: 1px solid var(--border-color);">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1"><i class="fa-solid fa-map-pin me-1 text-primary"></i> Return Point</small>
                            <span class="fw-semibold">{{ $returnLoc->name }}</span>
                            <small class="d-block text-secondary mt-1">{{ $returnLoc->address }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light-subtle rounded-3" style="border: 1px solid var(--border-color);">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i> Pickup Date</small>
                            <span class="fw-semibold">{{ Carbon\Carbon::parse($params['pickup_date'])->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light-subtle rounded-3" style="border: 1px solid var(--border-color);">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i> Return Date</small>
                            <span class="fw-semibold">{{ Carbon\Carbon::parse($params['return_date'])->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Driver details -->
                <h5 class="fw-bold mb-3 mt-4 border-top pt-4">Renter Information</h5>
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <span class="text-muted d-block">Full Name:</span>
                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block">Email Address:</span>
                        <span class="fw-semibold">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block">Phone:</span>
                        <span class="fw-semibold">{{ Auth::user()->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block">Billing Address:</span>
                        <span class="fw-semibold">{{ Auth::user()->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout forms & breakdown sidebar -->
        <div class="col-lg-5">
            <div class="card card-custom p-4 border-0 shadow-lg mb-4" style="background-color: var(--card-bg);">
                <h5 class="fw-bold mb-3 border-bottom pb-3"><i class="fa-solid fa-receipt me-2 text-primary"></i>Cost Summary</h5>
                
                <div class="d-flex justify-content-between small mb-2 text-secondary">
                    <span>Rental Duration:</span>
                    <span class="fw-bold text-dark">{{ $days }} days</span>
                </div>
                <div class="d-flex justify-content-between small mb-2 text-secondary">
                    <span>Base Rate:</span>
                    <span class="fw-bold text-dark">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($vehicle->price_per_day, 2) }} / day</span>
                </div>
                <div class="d-flex justify-content-between small mb-2 text-secondary">
                    <span>Subtotal:</span>
                    <span class="fw-bold text-dark">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($subtotal, 2) }}</span>
                </div>
                @if($discount > 0)
                    <div class="d-flex justify-content-between small mb-2 text-success">
                        <span>Promo Code Applied:</span>
                        <span class="fw-bold">-{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($discount, 2) }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between small mb-2 text-secondary">
                    <span>Taxes (12%):</span>
                    <span class="fw-bold text-dark">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($tax, 2) }}</span>
                </div>
                <hr class="my-2 border-secondary opacity-25">
                <div class="d-flex justify-content-between fw-bold fs-5 text-primary mb-4">
                    <span>Grand Total:</span>
                    <span>{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($total, 2) }}</span>
                </div>

                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $params['vehicle_id'] }}">
                    <input type="hidden" name="pickup_location_id" value="{{ $params['pickup_location_id'] }}">
                    <input type="hidden" name="return_location_id" value="{{ $params['return_location_id'] }}">
                    <input type="hidden" name="pickup_date" value="{{ $params['pickup_date'] }}">
                    <input type="hidden" name="return_date" value="{{ $params['return_date'] }}">
                    <input type="hidden" name="coupon_code" value="{{ $params['coupon_code'] }}">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Renter Notes / Special Requests</label>
                        <textarea name="notes" class="form-control border-secondary-subtle" rows="2" placeholder="e.g. child seat, early arrival..."></textarea>
                    </div>

                    <!-- Payment Gateway Selector -->
                    <h6 class="fw-bold mb-3 border-top pt-3 text-secondary">Choose Payment Method</h6>
                    <div class="d-grid gap-2 mb-4">
                        <div class="form-check p-3 bg-light-subtle rounded-3 border border-secondary-subtle d-flex align-items-center" style="padding-left: 2.5rem !important;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payStripe" value="stripe">
                            <label class="form-check-label fw-bold ms-2 w-100 cursor-pointer d-flex justify-content-between align-items-center" for="payStripe">
                                <span><i class="fa-brands fa-cc-stripe fs-4 text-primary me-2"></i>Stripe Credit Card</span>
                            </label>
                        </div>
                        <div class="form-check p-3 bg-light-subtle rounded-3 border border-secondary-subtle d-flex align-items-center" style="padding-left: 2.5rem !important;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payPaypal" value="paypal">
                            <label class="form-check-label fw-bold ms-2 w-100 cursor-pointer d-flex justify-content-between align-items-center" for="payPaypal">
                                <span><i class="fa-brands fa-paypal fs-4 text-info me-2"></i>PayPal Checkout</span>
                            </label>
                        </div>
                        <div class="form-check p-3 bg-light-subtle rounded-3 border border-secondary-subtle d-flex align-items-center" style="padding-left: 2.5rem !important;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payRazorpay" value="razorpay">
                            <label class="form-check-label fw-bold ms-2 w-100 cursor-pointer d-flex justify-content-between align-items-center" for="payRazorpay">
                                <span><i class="fa-solid fa-wallet fs-4 text-warning me-2"></i>Razorpay Wallet</span>
                            </label>
                        </div>
                        <div class="form-check p-3 bg-light-subtle rounded-3 border border-secondary-subtle d-flex align-items-center" style="padding-left: 2.5rem !important;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="cod" checked>
                            <label class="form-check-label fw-bold ms-2 w-100 cursor-pointer d-flex justify-content-between align-items-center" for="payCod">
                                <span><i class="fa-solid fa-hand-holding-dollar fs-4 text-success me-2"></i>Cash on Delivery</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold"><i class="fa-solid fa-lock me-2"></i>Complete & Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
