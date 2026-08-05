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

    .gallery-img-thumb {
        position: relative;
        height: 80px;
        width: 120px;
        overflow: hidden;
        border-radius: 0.5rem;
    }

    .gallery-delete-btn {
        position: absolute;
        top: 2px;
        end: 2px;
        background: rgba(239, 68, 68, 0.85);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
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
                    <a href="{{ route('admin.vehicles.index') }}" class="admin-nav-link active"><i class="fa-solid fa-car me-2"></i>Vehicles Fleet</a>
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
                    <a href="{{ route('admin.contacts.index') }}" class="admin-nav-link"><i class="fa-solid fa-envelope me-2"></i>Contact Messages</a>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Reports</a>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link"><i class="fa-solid fa-cog me-2"></i>System Settings</a>
                </div>
            </div>
        </div>

        <!-- Main Form Content -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-edit me-1 text-primary"></i>Modify Fleet Vehicle</h3>
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to Fleet</a>
            </div>

            <div class="card card-custom p-4 p-md-5 border-0 shadow-sm" style="background-color: var(--card-bg);">
                <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Vehicle Name -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Vehicle Model Name</label>
                            <input type="text" name="name" class="form-control border-secondary-subtle" value="{{ $vehicle->name }}" required>
                        </div>
                        <!-- License Plate -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">License Plate Number</label>
                            <input type="text" name="license_plate" class="form-control border-secondary-subtle" value="{{ $vehicle->license_plate }}" required>
                        </div>

                        <!-- Category -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Category</label>
                            <select name="category_id" class="form-select border-secondary-subtle" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $vehicle->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Brand -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Brand</label>
                            <select name="brand_id" class="form-select border-secondary-subtle" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $vehicle->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Model Year -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Model Year</label>
                            <input type="number" name="model_year" class="form-control border-secondary-subtle" value="{{ $vehicle->model_year }}" required>
                        </div>

                        <!-- Transmission -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Transmission</label>
                            <select name="transmission" class="form-select border-secondary-subtle" required>
                                <option value="Automatic" {{ $vehicle->transmission === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="Manual" {{ $vehicle->transmission === 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                        <!-- Fuel Type -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Fuel Type</label>
                            <select name="fuel_type" class="form-select border-secondary-subtle" required>
                                <option value="Petrol" {{ $vehicle->fuel_type === 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                <option value="Diesel" {{ $vehicle->fuel_type === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="Electric" {{ $vehicle->fuel_type === 'Electric' ? 'selected' : '' }}>Electric</option>
                                <option value="Hybrid" {{ $vehicle->fuel_type === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="Bicycle" {{ $vehicle->fuel_type === 'Bicycle' ? 'selected' : '' }}>Bicycle (Pedal)</option>
                            </select>
                        </div>
                        <!-- Seats count -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Seats count</label>
                            <input type="number" name="seats" class="form-control border-secondary-subtle" value="{{ $vehicle->seats }}" min="1" required>
                        </div>

                        <!-- Mileage -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Mileage (Range / MPG)</label>
                            <input type="text" name="mileage" class="form-control border-secondary-subtle" value="{{ $vehicle->mileage }}">
                        </div>
                        <!-- Engine Size -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Engine Size / Motor</label>
                            <input type="text" name="engine_size" class="form-control border-secondary-subtle" value="{{ $vehicle->engine_size }}">
                        </div>
                        <!-- Color -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Exterior Color</label>
                            <input type="text" name="color" class="form-control border-secondary-subtle" value="{{ $vehicle->color }}">
                        </div>
                        <!-- Price Per Day -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Daily Rate ($)</label>
                            <input type="number" name="price_per_day" class="form-control border-secondary-subtle" step="0.01" value="{{ $vehicle->price_per_day }}" required>
                        </div>

                        <!-- Main Image File -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Main Cover Image (Upload to change)</label>
                            <input type="file" name="main_image" class="form-control border-secondary-subtle" accept="image/*">
                            @if($vehicle->main_image)
                                <div class="mt-2" style="width: 150px; height: 100px; overflow: hidden; border-radius: 0.375rem; border: 1px solid var(--border-color);">
                                    <img src="{{ $vehicle->main_image }}" class="w-100 h-100 object-fit-cover" alt="Cover Image">
                                </div>
                            @endif
                        </div>
                        <!-- Gallery Images Files -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Add Sub Images</label>
                            <input type="file" name="gallery_images[]" class="form-control border-secondary-subtle" accept="image/*" multiple>
                            
                            <!-- Display current gallery -->
                            @if($vehicle->images->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($vehicle->images as $img)
                                        <div class="gallery-img-thumb">
                                            <img src="{{ $img->image_path }}" class="w-100 h-100 object-fit-cover" alt="Image">
                                            <button type="button" class="gallery-delete-btn" onclick="deleteGalleryImage({{ $img->id }})" title="Delete image">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Features Checklist -->
                        <div class="col-12 mt-4">
                            <label class="form-label small fw-semibold text-muted d-block mb-3 border-bottom pb-2">Included Amenities Checklist</label>
                            <div class="row g-3">
                                @php
                                    $features = ['GPS Navigation', 'Air Conditioning', 'Leather Seats', 'Heated Seats', 'Bluetooth', 'USB Port', 'Backup Camera', 'Sunroof', 'Apple CarPlay / Android Auto', 'Autopilot', 'Lane Assist', 'Adaptive Cruise Control', 'Helmets Included', 'Underseat Storage'];
                                @endphp
                                @foreach($features as $f)
                                    <div class="col-md-3 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="features[]" value="{{ $f }}" id="feat-{{ Str::slug($f) }}" {{ is_array($vehicle->features) && in_array($f, $vehicle->features) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="feat-{{ Str::slug($f) }}">
                                                {{ $f }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Description Box -->
                        <div class="col-12 mt-4">
                            <label class="form-label small fw-semibold text-muted">Description / Terms overview</label>
                            <textarea name="description" class="form-control border-secondary-subtle" rows="4" placeholder="Detail specifications, vehicle condition, rules, insurance coverage...">{{ $vehicle->description }}</textarea>
                        </div>

                        <!-- Flags & Status -->
                        <div class="col-md-4 mt-4">
                            <label class="form-label small fw-semibold text-muted">Vehicle Status</label>
                            <select name="status" class="form-select border-secondary-subtle">
                                <option value="available" {{ $vehicle->status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ $vehicle->status === 'unavailable' ? 'selected' : '' }}>Unavailable (Booked)</option>
                                <option value="maintenance" {{ $vehicle->status === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4 align-self-end">
                            <div class="form-check form-switch py-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ $vehicle->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold" for="isFeatured">Mark as Featured (Shown on Home)</label>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 align-self-end">
                            <div class="form-check form-switch py-2">
                                <input class="form-check-input" type="checkbox" name="is_popular" id="isPopular" value="1" {{ $vehicle->is_popular ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold" for="isPopular">Mark as Popular</label>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="col-12 mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-sm"><i class="fa-solid fa-save me-1"></i>Update Vehicle</button>
                        </div>
                    </div>
                </form>

                <!-- Hidden Delete Gallery Sub Form -->
                <form id="deleteGalleryForm" method="POST" action="" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function deleteGalleryImage(id) {
        if (confirm("Are you sure you want to delete this gallery image?")) {
            const form = document.getElementById('deleteGalleryForm');
            form.action = "/admin/vehicles/gallery/" + id;
            form.submit();
        }
    }
</script>
@endsection
