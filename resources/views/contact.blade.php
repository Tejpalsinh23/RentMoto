@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 900px;">
    <div class="text-center mb-5">
        <span class="badge bg-primary text-uppercase px-3 py-2 mb-2">Get In Touch</span>
        <h1 class="fw-extrabold text-dark">Contact Customer Support</h1>
        <p class="text-muted lead">Have queries? Fill out the form or drop by our depot desk.</p>
    </div>

    <div class="row g-4">
        <!-- Left: Contact Form -->
        <div class="col-md-7">
            <div class="card card-custom p-4 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-paper-plane me-1 text-primary"></i>Send Message</h5>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Your Name</label>
                            <input type="text" name="name" class="form-control border-secondary-subtle" placeholder="Full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control border-secondary-subtle" placeholder="Email address" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Subject</label>
                            <input type="text" name="subject" class="form-control border-secondary-subtle" placeholder="What is this regarding?" required>
                        </div>
                        <div class="col-12 text-start">
                            <label class="form-label small fw-semibold text-muted">Message Details</label>
                            <textarea name="message" class="form-control border-secondary-subtle" rows="5" placeholder="Details about your enquiry..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-2.5">Send Enquiry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Contact info & mock map -->
        <div class="col-md-5">
            <div class="card card-custom p-4 border-0 shadow-sm mb-4" style="background-color: var(--card-bg);">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-map-marked-alt me-1 text-primary"></i>Depot Head Office</h5>
                <p class="text-secondary small mb-3"><i class="fa-solid fa-map-marker-alt text-primary me-2"></i> {{ App\Models\Setting::get('site_address', '100 Rental Plaza, SF, CA') }}</p>
                <p class="text-secondary small mb-3"><i class="fa-solid fa-phone text-primary me-2"></i> {{ App\Models\Setting::get('site_phone', '+1 (555) 234-5678') }}</p>
                <p class="text-secondary small mb-0"><i class="fa-solid fa-envelope text-primary me-2"></i> {{ App\Models\Setting::get('site_email', 'support@apexwheels.com') }}</p>
            </div>

            <!-- Mock Map -->
            <div class="card card-custom overflow-hidden border-0 shadow-sm" style="height: 200px; background: #e2e8f0; position: relative;">
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4 text-center">
                    <i class="fa-solid fa-map-location-dot fs-1 text-primary mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">Mumbai, MH Desk</h6>
                    <small class="text-secondary">Depot map visualizer offline (API limits)</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
