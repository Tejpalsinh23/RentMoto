@extends('layouts.app')

@section('styles')
<style>
    .filter-sidebar {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
    }

    .vehicle-card-list {
        display: flex;
        flex-direction: row;
        height: 250px;
    }

    .vehicle-card-list .img-container {
        width: 35%;
        height: 100%;
    }

    .vehicle-card-list .card-body {
        width: 65%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Grid layout specific */
    .vehicle-card-grid {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .vehicle-card-grid .img-container {
        width: 100%;
        height: 180px;
    }

    .vehicle-card-grid .card-body {
        width: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .vehicle-card-list {
            flex-direction: column;
            height: auto;
        }
        .vehicle-card-list .img-container {
            width: 100%;
            height: 200px;
        }
        .vehicle-card-list .card-body {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5 animated-fade-in">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
                <form action="{{ route('vehicles.index') }}" method="GET" id="filterForm">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-sliders me-2 text-primary"></i>Filters</h5>
                    
                    <!-- Search Input -->
                    <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                        <label class="form-label small fw-semibold text-muted">Search Name</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="e.g. Tesla" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Category Selector -->
                    <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                        <label class="form-label small fw-semibold text-muted">Category</label>
                        <select name="category" class="form-select border-secondary-subtle">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand Selector -->
                    <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                        <label class="form-label small fw-semibold text-muted">Brand</label>
                        <select name="brand" class="form-select border-secondary-subtle">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Transmission Type -->
                    <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                        <label class="form-label small fw-semibold text-muted">Transmission</label>
                        <select name="transmission" class="form-select border-secondary-subtle">
                            <option value="">All Transmissions</option>
                            <option value="Manual" {{ request('transmission') === 'Manual' ? 'selected' : '' }}>Manual</option>
                            <option value="Automatic" {{ request('transmission') === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        </select>
                    </div>

                    <!-- Fuel Type -->
                    <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                        <label class="form-label small fw-semibold text-muted">Fuel Type</label>
                        <select name="fuel" class="form-select border-secondary-subtle">
                            <option value="">All Fuels</option>
                            <option value="Petrol" {{ request('fuel') === 'Petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="Diesel" {{ request('fuel') === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Electric" {{ request('fuel') === 'Electric' ? 'selected' : '' }}>Electric</option>
                            <option value="Hybrid" {{ request('fuel') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <!-- Price range -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Daily Rate ($)</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" class="form-control border-secondary-subtle" placeholder="Min" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="form-control border-secondary-subtle" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-2"></i>Apply Filters</button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Reset All</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vehicle Listing Grid -->
        <div class="col-lg-9">
            <!-- Top bar tools -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <div>
                    <h5 class="fw-bold mb-0 text-capitalize">Available Vehicles ({{ $vehicles->total() }})</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Layout switches -->
                    <div class="btn-group shadow-none" role="group" aria-label="Layout switches">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="gridToggle" title="Grid View"><i class="fa-solid fa-th-large"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="listToggle" title="List View"><i class="fa-solid fa-list"></i></button>
                    </div>

                    <!-- Sort -->
                    <select name="sort" class="form-select form-select-sm border-secondary-subtle" onchange="document.getElementById('sortInput').value = this.value; document.getElementById('filterForm').submit();" style="width: auto;">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Sort input placeholder in filterForm -->
            <script>
                document.write(`<input type="hidden" name="sort" id="sortInput" form="filterForm" value="{{ request('sort', 'newest') }}">`);
            </script>

            <!-- Grid container -->
            <div class="row g-4" id="vehiclesContainer">
                @forelse($vehicles as $vehicle)
                    <div class="col-md-6 col-lg-4 vehicle-item-card">
                        <div class="card card-custom overflow-hidden vehicle-card-grid">
                            <div class="img-container" style="position: relative; overflow: hidden; background: #e2e8f0;">
                                @if($vehicle->main_image)
                                    <img src="{{ $vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $vehicle->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-car fs-2"></i></div>
                                @endif
                                <div class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2 text-uppercase fw-bold">Available</div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold">{{ $vehicle->category->name }}</span>
                                        <span class="text-warning small"><i class="fa-solid fa-star"></i> {{ $vehicle->average_rating }}</span>
                                    </div>
                                    <h5 class="fw-bold card-title mb-2">{{ $vehicle->name }}</h5>
                                    <p class="text-muted small mb-3 text-truncate">{{ $vehicle->description }}</p>
                                    
                                    <div class="row g-2 mb-3 text-muted small border-top pt-3 border-secondary-subtle">
                                        <div class="col-6"><i class="fa-solid fa-gears me-1 text-primary"></i>{{ $vehicle->transmission }}</div>
                                        <div class="col-6"><i class="fa-solid fa-chair me-1 text-primary"></i>{{ $vehicle->seats }} Seats</div>
                                        <div class="col-6"><i class="fa-solid fa-gas-pump me-1 text-primary"></i>{{ $vehicle->fuel_type }}</div>
                                        <div class="col-6"><i class="fa-solid fa-palette me-1 text-primary"></i>{{ $vehicle->color }}</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <div>
                                        <span class="fs-4 fw-extrabold text-primary">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($vehicle->price_per_day, 2) }}</span>
                                        <small class="text-muted">/ day</small>
                                    </div>
                                    <a href="{{ route('vehicles.show', $vehicle->slug) }}" class="btn btn-primary btn-sm">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted fs-4 mb-3"><i class="fa-solid fa-car-burst fs-1 text-primary mb-3 d-block"></i>No matching vehicles found.</div>
                        <p class="text-secondary small">Try resetting some of your active filter settings.</p>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-2">View All Vehicles</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $vehicles->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const vehiclesContainer = document.getElementById('vehiclesContainer');
    const gridToggle = document.getElementById('gridToggle');
    const listToggle = document.getElementById('listToggle');
    const vehicleCards = document.querySelectorAll('.vehicle-item-card');

    // Layout Toggle logic
    gridToggle.addEventListener('click', () => {
        gridToggle.classList.add('active');
        listToggle.classList.remove('active');

        vehicleCards.forEach(cardCol => {
            cardCol.className = "col-md-6 col-lg-4 vehicle-item-card";
            const card = cardCol.querySelector('.card');
            card.classList.remove('vehicle-card-list');
            card.classList.add('vehicle-card-grid');
        });
    });

    listToggle.addEventListener('click', () => {
        listToggle.classList.add('active');
        gridToggle.classList.remove('active');

        vehicleCards.forEach(cardCol => {
            cardCol.className = "col-12 vehicle-item-card mb-4";
            const card = cardCol.querySelector('.card');
            card.classList.remove('vehicle-card-grid');
            card.classList.add('vehicle-card-list');
        });
    });

    // Set grid layout default active
    gridToggle.classList.add('active');
</script>
@endsection
