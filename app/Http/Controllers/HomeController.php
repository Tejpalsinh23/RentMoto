<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredVehicles = Vehicle::with(['brand', 'category'])->where('is_featured', true)->where('status', 'available')->take(6)->get();
        $popularVehicles = Vehicle::with(['brand', 'category'])->where('is_popular', true)->where('status', 'available')->take(6)->get();
        $categories = VehicleCategory::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        $faqs = Faq::where('is_active', true)->orderBy('order_num')->take(5)->get();
        $latestBlogs = Blog::where('is_published', true)->orderBy('created_at', 'desc')->take(3)->get();
        
        // Review statistics
        $reviews = Review::with(['user', 'vehicle'])->where('is_approved', true)->orderBy('created_at', 'desc')->take(6)->get();

        // Statistics counts
        $stats = [
            'vehicles' => Vehicle::count(),
            'customers' => \App\Models\User::where('role', 'customer')->count(),
            'bookings' => \App\Models\Booking::count(),
            'locations' => Location::count(),
        ];

        return view('home', compact('featuredVehicles', 'popularVehicles', 'categories', 'locations', 'faqs', 'latestBlogs', 'reviews', 'stats'));
    }

    public function vehicles(Request $request)
    {
        $query = Vehicle::with(['brand', 'category'])->where('status', 'available');

        // Apply Filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }
        if ($request->filled('fuel')) {
            $query->where('fuel_type', $request->fuel);
        }
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'price_low') {
            $query->orderBy('price_per_day', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price_per_day', 'desc');
        } elseif ($sort === 'popular') {
            $query->orderBy('is_popular', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $vehicles = $query->paginate(9)->withQueryString();

        $categories = VehicleCategory::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('vehicles.index', compact('vehicles', 'categories', 'brands'));
    }

    public function vehicleDetails($slug)
    {
        $vehicle = Vehicle::with(['brand', 'category', 'images', 'reviews' => function($q) {
            $q->where('is_approved', true)->with('user');
        }])->where('slug', $slug)->firstOrFail();

        $locations = Location::where('is_active', true)->get();
        
        // Recommended Vehicles (same category or brand)
        $recommended = Vehicle::with(['brand', 'category'])
            ->where('id', '!=', $vehicle->id)
            ->where('status', 'available')
            ->where(function($q) use ($vehicle) {
                $q->where('category_id', $vehicle->category_id)
                  ->orWhere('brand_id', $vehicle->brand_id);
            })
            ->take(4)
            ->get();

        // Recently Viewed (using session)
        $recentlyViewedIds = session()->get('recently_viewed', []);
        if (($key = array_search($vehicle->id, $recentlyViewedIds)) !== false) {
            unset($recentlyViewedIds[$key]); // remove if already exists to push to top
        }
        array_unshift($recentlyViewedIds, $vehicle->id);
        $recentlyViewedIds = array_slice($recentlyViewedIds, 0, 4);
        session()->put('recently_viewed', $recentlyViewedIds);

        $recentlyViewed = Vehicle::with(['brand', 'category'])
            ->whereIn('id', array_filter($recentlyViewedIds, fn($id) => $id != $vehicle->id))
            ->where('status', 'available')
            ->get();

        return view('vehicles.show', compact('vehicle', 'locations', 'recommended', 'recentlyViewed'));
    }

    public function blog(Request $request)
    {
        $query = Blog::with('category')->where('is_published', true);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(6);
        $blogCategories = BlogCategory::all();

        return view('blog.index', compact('blogs', 'blogCategories'));
    }

    public function blogDetails($slug)
    {
        $blog = Blog::with(['category', 'comments' => function($q) {
            $q->where('is_approved', true)->with('user')->whereNull('parent_id');
        }])->where('slug', $slug)->firstOrFail();

        $blog->increment('views');
        $blogCategories = BlogCategory::all();
        $recentBlogs = Blog::where('id', '!=', $blog->id)->where('is_published', true)->orderBy('created_at', 'desc')->take(4)->get();

        return view('blog.show', compact('blog', 'blogCategories', 'recentBlogs'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        ContactMessage::create($request->all());

        return back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you shortly.');
    }

    public function newsletterSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email'
        ], [
            'email.unique' => 'You have already subscribed to our newsletter!'
        ]);

        NewsletterSubscriber::create([
            'email' => $request->email,
            'token' => encrypt($request->email)
        ]);

        return back()->with('success', 'Successfully subscribed to our newsletter! Get ready for awesome updates and coupon offers.');
    }
}
