<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVehicleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminMiscController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC FRONTEND ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [HomeController::class, 'vehicles'])->name('vehicles.index');
Route::get('/vehicles/{slug}', [HomeController::class, 'vehicleDetails'])->name('vehicles.show');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [HomeController::class, 'blog.show'])->name('blog.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubmit'])->name('newsletter.subscribe');

// ==========================================
// CUSTOMER AUTHENTICATED ROUTES
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Overwrite default dashboard to redirect to customer dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/bookings', [CustomerDashboardController::class, 'bookings'])->name('dashboard.bookings');
    Route::get('/dashboard/wishlist', [CustomerDashboardController::class, 'wishlist'])->name('dashboard.wishlist');
    Route::post('/dashboard/wishlist/toggle', [CustomerDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/dashboard/reviews', [CustomerDashboardController::class, 'reviews'])->name('dashboard.reviews');
    Route::post('/dashboard/reviews/store', [CustomerDashboardController::class, 'storeReview'])->name('dashboard.reviews.store');
    
    Route::get('/dashboard/settings', [CustomerDashboardController::class, 'settings'])->name('dashboard.settings');
    Route::post('/dashboard/settings/profile', [CustomerDashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/settings/password', [CustomerDashboardController::class, 'updatePassword'])->name('dashboard.password.update');

    // Standard profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking calculations & flow
    Route::post('/booking/calculate', [BookingController::class, 'calculate'])->name('booking.calculate');
    Route::post('/booking/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    // Payment Processing
    Route::get('/payment/gateway', [PaymentController::class, 'gateway'])->name('payment.gateway');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{id}', [PaymentController::class, 'success'])->name('payment.success');

    // PDF Invoices
    Route::get('/booking/invoice/{bookingNumber}/download', [InvoiceController::class, 'download'])->name('booking.invoice.download');
});

// ==========================================
// ADMIN DASHBOARD ROUTES (AUTH + ADMIN)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Vehicles CRUD
    Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [AdminVehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles/store', [AdminVehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}/edit', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
    Route::post('/vehicles/{vehicle}/update', [AdminVehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');
    Route::delete('/vehicles/gallery/{id}', [AdminVehicleController::class, 'deleteGalleryImage'])->name('vehicles.gallery.delete');

    // Categories CRUD
    Route::get('/categories', [AdminVehicleController::class, 'categories'])->name('categories.index');
    Route::post('/categories/store', [AdminVehicleController::class, 'categoryStore'])->name('categories.store');
    Route::post('/categories/{id}/update', [AdminVehicleController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminVehicleController::class, 'categoryDestroy'])->name('categories.destroy');

    // Brands CRUD
    Route::get('/brands', [AdminVehicleController::class, 'brands'])->name('brands.index');
    Route::post('/brands/store', [AdminVehicleController::class, 'brandStore'])->name('brands.store');
    Route::post('/brands/{id}/update', [AdminVehicleController::class, 'brandUpdate'])->name('brands.update');
    Route::delete('/brands/{id}', [AdminVehicleController::class, 'brandDestroy'])->name('brands.destroy');

    // Bookings Manage
    Route::get('/bookings', [AdminBookingController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminBookingController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{id}/status', [AdminBookingController::class, 'updateBookingStatus'])->name('bookings.status.update');

    // Customers List
    Route::get('/customers', [AdminBookingController::class, 'customers'])->name('customers.index');

    // Payments List
    Route::get('/payments', [AdminBookingController::class, 'payments'])->name('payments.index');

    // Reviews Approval
    Route::get('/reviews', [AdminBookingController::class, 'reviews'])->name('reviews.index');
    Route::post('/reviews/{id}/approve', [AdminBookingController::class, 'approveReview'])->name('reviews.approve');
    Route::post('/reviews/{id}/reject', [AdminBookingController::class, 'rejectReview'])->name('reviews.reject');

    // Coupons CRUD
    Route::get('/coupons', [AdminBookingController::class, 'coupons'])->name('coupons.index');
    Route::post('/coupons/store', [AdminBookingController::class, 'couponStore'])->name('coupons.store');
    Route::post('/coupons/{id}/update', [AdminBookingController::class, 'couponUpdate'])->name('coupons.update');
    Route::delete('/coupons/{id}', [AdminBookingController::class, 'couponDestroy'])->name('coupons.destroy');

    // Reports & Downloads
    Route::get('/reports', [AdminBookingController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export', [AdminBookingController::class, 'exportReport'])->name('reports.export');

    // Locations CRUD
    Route::get('/locations', [AdminMiscController::class, 'locations'])->name('locations.index');
    Route::post('/locations/store', [AdminMiscController::class, 'locationStore'])->name('locations.store');
    Route::post('/locations/{id}/update', [AdminMiscController::class, 'locationUpdate'])->name('locations.update');
    Route::delete('/locations/{id}', [AdminMiscController::class, 'locationDestroy'])->name('locations.destroy');

    // FAQs CRUD
    Route::get('/faqs', [AdminMiscController::class, 'faqs'])->name('faqs.index');
    Route::post('/faqs/store', [AdminMiscController::class, 'faqStore'])->name('faqs.store');
    Route::post('/faqs/{id}/update', [AdminMiscController::class, 'faqUpdate'])->name('faqs.update');
    Route::delete('/faqs/{id}', [AdminMiscController::class, 'faqDestroy'])->name('faqs.destroy');

    // Contact Submissions
    Route::get('/contacts', [AdminMiscController::class, 'contacts'])->name('contacts.index');
    Route::post('/contacts/{id}/read', [AdminMiscController::class, 'contactMarkRead'])->name('contacts.read');
    Route::post('/contacts/{id}/reply', [AdminMiscController::class, 'contactReply'])->name('contacts.reply');

    // Newsletter Subscribers
    Route::get('/newsletter', [AdminMiscController::class, 'newsletter'])->name('newsletter.index');

    // Settings Manage
    Route::get('/settings', [AdminMiscController::class, 'settings'])->name('settings.index');
    Route::post('/settings/update', [AdminMiscController::class, 'settingsUpdate'])->name('settings.update');

    // Blogs CRUD
    Route::get('/blogs', [AdminMiscController::class, 'blogs'])->name('blogs.index');
    Route::post('/blogs/store', [AdminMiscController::class, 'blogStore'])->name('blogs.store');
    Route::post('/blogs/{id}/update', [AdminMiscController::class, 'blogUpdate'])->name('blogs.update');
    Route::delete('/blogs/{id}', [AdminMiscController::class, 'blogDestroy'])->name('blogs.destroy');
});

// Require breeze auth paths
require __DIR__.'/auth.php';
