<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\Brand;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminVehicleController extends Controller
{
    // ==========================================
    // VEHICLES MANAGEMENT
    // ==========================================

    public function index()
    {
        $vehicles = Vehicle::with(['brand', 'category'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $categories = VehicleCategory::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.vehicles.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'fuel_type' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|in:Manual,Automatic',
            'price_per_day' => 'required|numeric|min:0',
            'mileage' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'features' => 'nullable|array',
            'status' => 'required|string|in:available,unavailable,maintenance'
        ]);

        $data = $request->except(['main_image', 'gallery_images']);
        $data['slug'] = Str::slug($request->name) . '-' . mt_rand(100, 999);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_popular'] = $request->has('is_popular');
        $data['features'] = $request->features ?? [];

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('vehicles', 'public');
            $data['main_image'] = '/storage/' . $path;
        }

        $vehicle = Vehicle::create($data);

        // Upload Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('vehicles/gallery', 'public');
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => '/storage/' . $path
                ]);
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle added successfully!');
    }

    public function edit(Vehicle $vehicle)
    {
        $categories = VehicleCategory::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.vehicles.edit', compact('vehicle', 'categories', 'brands'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'fuel_type' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|in:Manual,Automatic',
            'price_per_day' => 'required|numeric|min:0',
            'mileage' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'features' => 'nullable|array',
            'status' => 'required|string|in:available,unavailable,maintenance'
        ]);

        $data = $request->except(['main_image', 'gallery_images']);
        $data['slug'] = Str::slug($request->name) . '-' . $vehicle->id;
        $data['is_featured'] = $request->has('is_featured');
        $data['is_popular'] = $request->has('is_popular');
        $data['features'] = $request->features ?? [];

        if ($request->hasFile('main_image')) {
            // Delete old
            if ($vehicle->main_image) {
                Storage::delete(str_replace('/storage/', '', $vehicle->main_image));
            }
            $path = $request->file('main_image')->store('vehicles', 'public');
            $data['main_image'] = '/storage/' . $path;
        }

        $vehicle->update($data);

        // Upload Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('vehicles/gallery', 'public');
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => '/storage/' . $path
                ]);
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle soft deleted successfully!');
    }

    public function deleteGalleryImage($id)
    {
        $image = VehicleImage::findOrFail($id);
        Storage::delete(str_replace('/storage/', '', $image->image_path));
        $image->delete();

        return back()->with('success', 'Gallery image deleted.');
    }

    // ==========================================
    // CATEGORIES MANAGEMENT
    // ==========================================

    public function categories()
    {
        $categories = VehicleCategory::orderBy('created_at', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024'
        ]);

        $data = $request->only('name', 'description');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        VehicleCategory::create($data);

        return back()->with('success', 'Category created successfully!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = VehicleCategory::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024'
        ]);

        $data = $request->only('name', 'description');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete(str_replace('/storage/', '', $category->image));
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $category->update($data);

        return back()->with('success', 'Category updated successfully!');
    }

    public function categoryDestroy($id)
    {
        $category = VehicleCategory::findOrFail($id);
        $category->delete();
        return back()->with('success', 'Category soft deleted successfully!');
    }

    // ==========================================
    // BRANDS MANAGEMENT
    // ==========================================

    public function brands()
    {
        $brands = Brand::orderBy('created_at', 'desc')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024'
        ]);

        $data = $request->only('name');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        Brand::create($data);

        return back()->with('success', 'Brand created successfully!');
    }

    public function brandUpdate(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024'
        ]);

        $data = $request->only('name');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::delete(str_replace('/storage/', '', $brand->logo));
            }
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $brand->update($data);

        return back()->with('success', 'Brand updated successfully!');
    }

    public function brandDestroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return back()->with('success', 'Brand soft deleted successfully!');
    }
}
