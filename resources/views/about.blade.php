@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 900px;">
    <div class="text-center mb-5">
        <span class="badge bg-primary text-uppercase px-3 py-2 mb-2">Our Story</span>
        <h1 class="fw-extrabold text-dark">About {{ App\Models\Setting::get('site_name', 'RentMoto') }}</h1>
        <p class="text-muted lead">Redefining mobility with flexible, affordable, and high-quality rentals.</p>
    </div>

    <!-- Company bio -->
    <div class="card card-custom p-4 p-md-5 border-0 shadow-sm mb-5" style="background-color: var(--card-bg);">
        <h3 class="fw-bold mb-3 text-primary">The Vision & Mission</h3>
        <p class="text-muted" style="line-height: 1.7;">Founded in 2024, our company was built to remove the friction from vehicle rentals. We believe in providing eco-friendly, modern transportation options across major Indian hubs. Whether you need a high-performance EV like Tesla, a spacious SUV for family getaways, or a simple step-through scooter for city tours, we have you covered.</p>
        
        <div class="row g-4 mt-3">
            <div class="col-md-6">
                <h5 class="fw-bold text-dark"><i class="fa-solid fa-bullseye me-2 text-primary"></i>Our Mission</h5>
                <p class="text-secondary small">To make renting a vehicle instant, transparent, and eco-friendly by incorporating digital checkouts and introducing hybrid/electric models to our daily fleet.</p>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold text-dark"><i class="fa-solid fa-eye me-2 text-primary"></i>Our Vision</h5>
                <p class="text-secondary small">To establish a zero-emission, decentralized urban mobility system where cars, bikes, and electric micro-mobility options are accessible in minutes.</p>
            </div>
        </div>
    </div>

    <!-- Core Team -->
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark">Meet Our Leadership Team</h3>
        <p class="text-muted small">The innovators driving {{ App\Models\Setting::get('site_name', 'RentMoto') }} forward.</p>
    </div>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-4" style="width: 70px; height: 70px;">
                   TP
                </div>
                <h5 class="fw-bold mb-1 text-dark">Tejpalsinh Pal</h5>
                <small class="text-primary fw-bold text-uppercase d-block mb-2">CEO & Founder</small>
                <p class="text-muted small mb-0">Renting enthusiast with 15+ years experience in urban transport logistics.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-4" style="width: 70px; height: 70px;">
                    AR
                </div>
                <h5 class="fw-bold mb-1 text-dark">Adarsh Rajput</h5>
                <small class="text-info fw-bold text-uppercase d-block mb-2">Chief Fleet Officer</small>
                <p class="text-muted small mb-0">Maintains quality standards and manages electrification of our Indian fleets.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-4" style="width: 70px; height: 70px;">
                    AP
                </div>
                <h5 class="fw-bold mb-1 text-dark">Akshar Patel</h5>
                <small class="text-success fw-bold text-uppercase d-block mb-2">Customer Experience</small>
                <p class="text-muted small mb-0">Ensures seamless depot pickups and leads customer support systems.</p>
            </div>
        </div>
    </div>
</div>
@endsection
