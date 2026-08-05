@extends('layouts.app')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(79, 70, 229, 0.4)), url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        padding: 100px 0 140px 0;
        color: #ffffff;
    }

    .search-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1.5rem;
        box-shadow: var(--shadow-lg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        margin-top: -60px;
        z-index: 10;
        position: relative;
    }

    /* Responsive Layout Adjustments */
    @media (max-width: 768px) {
        .hero-section {
            padding: 80px 0 100px 0;
        }
        .search-card {
            margin-top: -40px;
            border-radius: 1rem;
        }
        .category-icon {
            font-size: 2rem;
        }
    }

    /* Category Cards */
    .category-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: var(--text-color);
        display: block;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .category-icon {
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }

    /* Stats Card */
    .stat-card {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: white;
        border-radius: 1.25rem;
        border: none;
    }
</style>
@endsection

@section('content')
<!-- Hero Banner -->
<section class="hero-section text-center animated-fade-in">
    <div class="container py-5">
        <span class="badge bg-indigo-500 text-white px-3 py-2 mb-3 text-uppercase fw-bold" style="background-color: var(--primary-color);">Drive Your Dreams Today</span>
        <h1 class="display-3 fw-bold mb-3">Premium Vehicle Rentals</h1>
        <p class="lead mb-4 text-white-50 opacity-75">Unbeatable rates for cars, electric vehicles, SUVs, vans, and bikes across India.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary btn-lg">Browse Inventory</a>
            <a href="#how-it-works" class="btn btn-outline-light btn-lg">How it Works</a>
        </div>
    </div>
</section>

<!-- Search Rental Form -->
<div class="container">
    <div class="search-card p-4 p-md-5">
        <form action="{{ route('vehicles.index') }}" method="GET">
            <h4 class="fw-bold mb-4 text-center text-md-start"><i class="fa-solid fa-search me-2 text-primary"></i>Find Your Perfect Ride</h4>
            <div class="row g-3">
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label small fw-semibold text-muted">Vehicle Type</label>
                    <select name="category" class="form-select border-secondary-subtle py-2.5">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label small fw-semibold text-muted">Pickup Location</label>
                    <select name="pickup_location" class="form-select border-secondary-subtle py-2.5" required>
                        <option value="">Select Pickup Depot</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <label class="form-label small fw-semibold text-muted">Pickup Date</label>
                    <input type="date" name="pickup_date" class="form-control border-secondary-subtle py-2.5" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <label class="form-label small fw-semibold text-muted">Return Date</label>
                    <input type="date" name="return_date" class="form-control border-secondary-subtle py-2.5" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div class="col-lg-2 col-md-12 col-12 d-grid align-items-end mt-4 mt-lg-0">
                    <button type="submit" class="btn btn-primary py-2.5 fw-bold"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Popular Categories -->
<section class="py-5 mt-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Popular Rental Categories</h2>
            <p class="text-muted">Find the type of vehicle that fits your transport needs.</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $cat)
                <div class="col-lg-3 col-md-6 col-6">
                    <a href="{{ route('vehicles.index', ['category' => $cat->id]) }}" class="category-card">
                        <div class="category-icon">
                            @if($cat->name === 'Cars')
                                <i class="fa-solid fa-car"></i>
                            @elseif($cat->name === 'Bikes')
                                <i class="fa-solid fa-motorcycle"></i>
                            @elseif($cat->name === 'Scooters')
                                <i class="fa-solid fa-moped"></i>
                            @elseif($cat->name === 'Bicycles')
                                <i class="fa-solid fa-bicycle"></i>
                            @elseif($cat->name === 'Electric Vehicles')
                                <i class="fa-solid fa-bolt"></i>
                            @elseif($cat->name === 'Luxury Cars')
                                <i class="fa-solid fa-gem"></i>
                            @elseif($cat->name === 'SUVs')
                                <i class="fa-solid fa-mountain"></i>
                            @else
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1">{{ $cat->name }}</h6>
                        <small class="text-muted">{{ $cat->vehicles()->count() }} vehicles</small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Vehicles -->
<section class="py-5 bg-light-subtle">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5 text-center text-md-start">
            <div>
                <span class="text-primary fw-bold text-uppercase small tracking-wide">Featured Choices</span>
                <h2 class="fw-bold mb-0">Our Fleet Highlights</h2>
            </div>
            <div class="align-self-center align-self-md-auto">
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-primary fw-semibold">View All Vehicles <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        <div class="row g-4">
            @forelse($featuredVehicles as $vehicle)
                <div class="col-lg-4 col-md-6">
                    <div class="card card-custom h-100 overflow-hidden">
                        <div style="position: relative; height: 220px; overflow: hidden; background: #e2e8f0;">
                            @if($vehicle->main_image)
                                <img src="{{ $vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $vehicle->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car fs-1"></i></div>
                            @endif
                            <div class="badge bg-primary position-absolute top-0 end-0 m-3 px-3 py-2 text-uppercase fw-bold fs-7">Featured</div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold">{{ $vehicle->category->name }}</span>
                                <div class="text-warning small">
                                    <i class="fa-solid fa-star"></i> {{ $vehicle->average_rating }}
                                </div>
                            </div>
                            <h5 class="fw-bold card-title mb-3">{{ $vehicle->name }}</h5>
                            <div class="row g-2 mb-4 text-muted small border-bottom pb-3 border-secondary-subtle">
                                <div class="col-6"><i class="fa-solid fa-gears me-1 text-primary"></i>{{ $vehicle->transmission }}</div>
                                <div class="col-6"><i class="fa-solid fa-chair me-1 text-primary"></i>{{ $vehicle->seats }} Seats</div>
                                <div class="col-6"><i class="fa-solid fa-gas-pump me-1 text-primary"></i>{{ $vehicle->fuel_type }}</div>
                                <div class="col-6"><i class="fa-solid fa-palette me-1 text-primary"></i>{{ $vehicle->color }}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fs-4 fw-extrabold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($vehicle->price_per_day, 2) }}</span>
                                    <small class="text-muted">/ day</small>
                                </div>
                                <a href="{{ route('vehicles.show', $vehicle->slug) }}" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center text-muted">No featured vehicles available at the moment.</div>
                @endforelse
        </div>
    </div>
</section>

<!-- Statistics counters -->
<section class="py-5 bg-dark text-white border-top border-secondary-subtle">
    <div class="container py-4">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <h1 class="display-4 fw-extrabold text-primary">{{ $stats['vehicles'] }}+</h1>
                <p class="text-secondary small text-uppercase fw-bold mb-0">Total Fleet Size</p>
            </div>
            <div class="col-md-3 col-6">
                <h1 class="display-4 fw-extrabold text-info">{{ $stats['customers'] }}+</h1>
                <p class="text-secondary small text-uppercase fw-bold mb-0">Happy Customers</p>
            </div>
            <div class="col-md-3 col-6">
                <h1 class="display-4 fw-extrabold text-warning">{{ $stats['bookings'] }}+</h1>
                <p class="text-secondary small text-uppercase fw-bold mb-0">Bookings Completed</p>
            </div>
            <div class="col-md-3 col-6">
                <h1 class="display-4 fw-extrabold text-success">{{ $stats['locations'] }}+</h1>
                <p class="text-secondary small text-uppercase fw-bold mb-0">Pickup Locations</p>
            </div>
        </div>
    </div>
</section>

<!-- How it Works -->
<section id="how-it-works" class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase small">Simple Flow</span>
            <h2 class="fw-bold">How it Works</h2>
            <p class="text-muted">Rent your vehicle in three simple steps.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-magnifying-glass fs-3"></i>
                    </div>
                    <h5 class="fw-bold">1. Choose Vehicle</h5>
                    <p class="text-muted small">Select your perfect vehicle from our extensive collection of cars, bikes, and EVs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-calendar-days fs-3"></i>
                    </div>
                    <h5 class="fw-bold">2. Book Rental</h5>
                    <p class="text-muted small">Set your dates, select pick up locations, and checkout securely using Stripe or PayPal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-key fs-3"></i>
                    </div>
                    <h5 class="fw-bold">3. Unlock & Go</h5>
                    <p class="text-muted small">Pick up your keys at our Mumbai or Pune depots and enjoy your rental adventure!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews -->
@if($reviews->count() > 0)
<section class="py-5 bg-light-subtle">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Customer Testimonials</h2>
            <p class="text-muted">Hear what our drivers have to say about their rental experiences.</p>
        </div>
        <div class="row g-4">
            @foreach($reviews as $rev)
                <div class="col-md-4">
                    <div class="card card-custom h-100 p-4 border-0 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">
                                {{ substr($rev->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $rev->user->name }}</h6>
                                <small class="text-warning">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa-{{ $i <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0 font-italic">"{{ $rev->comment }}"</p>
                        <hr class="my-3 opacity-25 border-secondary-subtle">
                        <small class="text-primary fw-semibold"><i class="fa-solid fa-car me-1"></i> Rented: {{ $rev->vehicle->name }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FAQs Accordion -->
<section id="faq-section" class="py-5">
    <div class="container py-4" style="max-width: 800px;">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Frequently Asked Questions</h2>
            <p class="text-muted">Quick answers to common questions about our rental procedures.</p>
        </div>
        <div class="accordion accordion-flush card-custom p-3" id="faqAccordion" style="background-color: var(--card-bg);">
            @foreach($faqs as $key => $faq)
                <div class="accordion-item bg-transparent border-bottom py-2" style="border-color: var(--border-color) !important;">
                    <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                        <button class="accordion-button collapsed bg-transparent fw-bold text-reset shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}" aria-expanded="false" aria-controls="collapse-{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $faq->id }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
