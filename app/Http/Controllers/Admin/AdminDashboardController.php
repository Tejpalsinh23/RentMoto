<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Payment;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'booked_vehicles' => Vehicle::where('status', 'unavailable')->count(),
            'revenue' => Payment::where('status', 'paid')->sum('amount'),
        ];

        $recentBookings = Booking::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->take(6)->get();

        // 1. Monthly Revenue Chart (last 6 months)
        // Group by month and sum the payment amounts
        $monthlyPayments = Payment::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw("SUM(amount) as total")
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        $chartMonthlyLabels = [];
        $chartMonthlyData = [];

        foreach ($monthlyPayments as $payment) {
            $chartMonthlyLabels[] = $payment->month;
            $chartMonthlyData[] = (float) $payment->total;
        }

        // 2. Vehicle Categories Chart
        $categoriesData = VehicleCategory::withCount('vehicles')->get();
        $chartCategoryLabels = [];
        $chartCategoryData = [];

        foreach ($categoriesData as $category) {
            $chartCategoryLabels[] = $category->name;
            $chartCategoryData[] = $category->vehicles_count;
        }

        return view('admin.dashboard', compact('stats', 'recentBookings', 'chartMonthlyLabels', 'chartMonthlyData', 'chartCategoryLabels', 'chartCategoryData'));
    }
}
