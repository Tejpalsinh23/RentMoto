<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\VehicleCategory;
use App\Models\Brand;
use App\Models\Vehicle;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Settings
        Setting::set('site_name', 'RentMoto', 'general');
        Setting::set('site_email', 'support@rentmoto.com', 'general');
        Setting::set('site_phone', '+1 (555) 234-5678', 'general');
        Setting::set('site_address', '100 Rental Plaza, Suite A, Mumbai, MH, India', 'general');
        Setting::set('currency_symbol', '₹', 'general');
        Setting::set('currency_code', 'INR', 'general');
        Setting::set('tax_rate', '12', 'general'); // 12% tax

        // 2. Seed Users
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@rentalsystem.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+91 98765 43210',
            'address' => 'Admin Headquarters, Mumbai, India',
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'customer@rentalsystem.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+91 91234 56789',
            'address' => '456 Elm Street, Andheri West, Mumbai, MH',
            'email_verified_at' => now(),
        ]);

        // 3. Seed Locations
        $loc1 = Location::create(['name' => 'Chhatrapati Shivaji Maharaj Int Airport (BOM)', 'address' => 'Terminal 2 Arrivals, Mumbai, MH', 'latitude' => 19.0896, 'longitude' => 72.8656]);
        $loc2 = Location::create(['name' => 'Bandra Kurla Complex (BKC) Office', 'address' => 'BKC, Bandra East, Mumbai, MH', 'latitude' => 19.0616, 'longitude' => 72.8643]);
        $loc3 = Location::create(['name' => 'Pune Central Station', 'address' => 'Agarkar Nagar, Pune, MH', 'latitude' => 18.5284, 'longitude' => 73.8739]);
        $loc4 = Location::create(['name' => 'Navi Mumbai Hub', 'address' => 'Vashi, Navi Mumbai, MH', 'latitude' => 19.0745, 'longitude' => 72.9978]);

        // 4. Seed Categories
        $cats = [
            'Cars' => 'Standard four-wheel drive sedans, coupes, and hatchbacks.',
            'Bikes' => 'High-performance motorbikes and cruisers.',
            'Scooters' => 'Nimble urban motorized and electric step-through scooters.',
            'Bicycles' => 'Mountain, road, and hybrid pedal bicycles.',
            'Electric Vehicles' => 'Eco-friendly, quiet, and rapid-acceleration zero-emission EVs.',
            'Luxury Cars' => 'Exotic and premium luxury vehicles offering maximum comfort and power.',
            'SUVs' => 'Spacious sport utility vehicles built for family trips and rugged terrains.',
            'Vans' => 'Multi-seater passenger vans and cargo carriers.'
        ];

        $categoryModels = [];
        foreach ($cats as $name => $desc) {
            $categoryModels[$name] = VehicleCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
                'is_active' => true
            ]);
        }

        // 5. Seed Brands
        $brands = ['Tata', 'Mahindra', 'Maruti Suzuki', 'Force', 'Royal Enfield', 'Bajaj', 'Ather', 'Hero'];
        $brandModels = [];
        foreach ($brands as $b) {
            $brandModels[$b] = Brand::create([
                'name' => $b,
                'slug' => Str::slug($b),
                'is_active' => true
            ]);
        }

        // 6. Seed Vehicles
        $vehiclesData = [
            [
                'name' => 'Tata Nexon EV Empowered',
                'category' => 'Electric Vehicles',
                'brand' => 'Tata',
                'model_year' => 2024,
                'license_plate' => 'MH12-EV-0001',
                'fuel_type' => 'Electric',
                'seats' => 5,
                'transmission' => 'Automatic',
                'mileage' => '465 km range',
                'engine_size' => '106.4 kW Electric Motor',
                'color' => 'Fearless Purple',
                'price_per_day' => 3000.00,
                'status' => 'available',
                'description' => 'Experience the bestselling electric SUV in India. Loaded with tech, ventilated seats, and an impressive range. Perfect for city commutes and weekend getaways.',
                'features' => ['360 Camera', 'Leather Seats', 'Ventilated Seats', 'GPS', 'Bluetooth', 'USB Port', 'Sunroof', 'Air Purifier'],
                'main_image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=800&auto=format&fit=crop',
                'is_featured' => true,
                'is_popular' => true
            ],
            [
                'name' => 'Mahindra XUV700 AX7',
                'category' => 'SUVs',
                'brand' => 'Mahindra',
                'model_year' => 2023,
                'license_plate' => 'MH14-XUV-700',
                'fuel_type' => 'Diesel',
                'seats' => 7,
                'transmission' => 'Automatic',
                'mileage' => '14 kmpl',
                'engine_size' => '2.2L mHawk Diesel',
                'color' => 'Midnight Black',
                'price_per_day' => 4500.00,
                'status' => 'available',
                'description' => 'The ultimate premium SUV for Indian roads. Advanced Driver Assistance Systems (ADAS), panoramic sunroof, and a powerful mHawk engine. Perfect for family road trips.',
                'features' => ['ADAS', 'Sony 3D Audio', 'Panoramic Sunroof', 'Dual Zone Climate Control', 'GPS', 'Leather Seats'],
                'main_image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=800&auto=format&fit=crop',
                'is_featured' => true,
                'is_popular' => true
            ],
            [
                'name' => 'Maruti Suzuki Grand Vitara',
                'category' => 'SUVs',
                'brand' => 'Maruti Suzuki',
                'model_year' => 2023,
                'license_plate' => 'MH02-GV-2023',
                'fuel_type' => 'Hybrid',
                'seats' => 5,
                'transmission' => 'Automatic',
                'mileage' => '27.97 kmpl',
                'engine_size' => '1.5L Intelligent Electric Hybrid',
                'color' => 'Nexa Blue',
                'price_per_day' => 3500.00,
                'status' => 'available',
                'description' => 'Incredibly fuel-efficient strong hybrid SUV. Silent EV mode for city traffic and powerful hybrid engine for highways. Great mileage and premium interiors.',
                'features' => ['Wireless Apple CarPlay', 'HUD', 'Ventilated Seats', '360 Camera', 'Bluetooth'],
                'main_image' => 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?q=80&w=800&auto=format&fit=crop',
                'is_featured' => false,
                'is_popular' => true
            ],
            [
                'name' => 'Royal Enfield Classic 350',
                'category' => 'Bikes',
                'brand' => 'Royal Enfield',
                'model_year' => 2024,
                'license_plate' => 'MH01-RE-350',
                'fuel_type' => 'Petrol',
                'seats' => 2,
                'transmission' => 'Manual',
                'mileage' => '35 kmpl',
                'engine_size' => '349cc J-Series',
                'color' => 'Halcyon Green',
                'price_per_day' => 1500.00,
                'status' => 'available',
                'description' => 'The iconic Indian cruiser. Experience the legendary thump with modern reliability. Perfect for touring the Western Ghats or cruising down the coast.',
                'features' => ['Dual Channel ABS', 'Tripper Navigation', 'USB Charger', 'Disc Brakes'],
                'main_image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?q=80&w=800&auto=format&fit=crop',
                'is_featured' => true,
                'is_popular' => true
            ],
            [
                'name' => 'Ather 450X',
                'category' => 'Scooters',
                'brand' => 'Ather',
                'model_year' => 2023,
                'license_plate' => 'MH43-EV-450',
                'fuel_type' => 'Electric',
                'seats' => 2,
                'transmission' => 'Automatic',
                'mileage' => '105 km range',
                'engine_size' => '6.4 kW PMSM Motor',
                'color' => 'Space Grey',
                'price_per_day' => 800.00,
                'status' => 'available',
                'description' => 'The quickest smart scooter in India. Warp mode acceleration, Google Maps integration, and zero emissions. Perfect for zipping through city traffic.',
                'features' => ['Underseat Storage', 'Touchscreen Display', 'Google Maps', 'Bluetooth', 'Reverse Assist'],
                'main_image' => 'https://images.unsplash.com/photo-1622185135505-2d795003994a?q=80&w=800&auto=format&fit=crop',
                'is_featured' => false,
                'is_popular' => true
            ],
            [
                'name' => 'Bajaj Chetak',
                'category' => 'Scooters',
                'brand' => 'Bajaj',
                'model_year' => 2023,
                'license_plate' => 'MH12-EV-CHETAK',
                'fuel_type' => 'Electric',
                'seats' => 2,
                'transmission' => 'Automatic',
                'mileage' => '90 km range',
                'engine_size' => '4.2 kW BLDC Motor',
                'color' => 'Hazelnut',
                'price_per_day' => 700.00,
                'status' => 'available',
                'description' => 'The iconic Chetak is back in an electric avatar. Premium metal body, elegant design, and reliable performance. A classic ride for modern India.',
                'features' => ['Metal Body', 'Keyless Entry', 'Reverse Gear', 'Bluetooth'],
                'main_image' => 'https://images.unsplash.com/photo-1520106208398-e6b360a0a996?q=80&w=800&auto=format&fit=crop',
                'is_featured' => false,
                'is_popular' => false
            ],
            [
                'name' => 'Force Traveller 3350',
                'category' => 'Vans',
                'brand' => 'Force',
                'model_year' => 2022,
                'license_plate' => 'MH04-TR-3350',
                'fuel_type' => 'Diesel',
                'seats' => 12,
                'transmission' => 'Manual',
                'mileage' => '11 kmpl',
                'engine_size' => '2.6L FM2.6CR',
                'color' => 'Arctic White',
                'price_per_day' => 6000.00,
                'status' => 'available',
                'description' => 'Comfortably seats up to 12 passengers with generous legroom. India\'s favorite people mover is ideal for large family trips, corporate outings, or group tours.',
                'features' => ['Roof AC', 'Reclining Seats', 'Bluetooth Audio', 'Large Luggage Space', 'USB Charger'],
                'main_image' => 'https://images.unsplash.com/photo-1626305018659-1e1644136691?q=80&w=800&auto=format&fit=crop',
                'is_featured' => false,
                'is_popular' => false
            ]
        ];

        foreach ($vehiclesData as $v) {
            $catId = $categoryModels[$v['category']]->id;
            $brandId = $brandModels[$v['brand']]->id;

            $vehicle = Vehicle::create([
                'category_id' => $catId,
                'brand_id' => $brandId,
                'name' => $v['name'],
                'slug' => Str::slug($v['name']),
                'model_year' => $v['model_year'],
                'license_plate' => $v['license_plate'],
                'fuel_type' => $v['fuel_type'],
                'seats' => $v['seats'],
                'transmission' => $v['transmission'],
                'mileage' => $v['mileage'],
                'engine_size' => $v['engine_size'],
                'color' => $v['color'],
                'price_per_day' => $v['price_per_day'],
                'status' => $v['status'],
                'description' => $v['description'],
                'features' => $v['features'],
                'main_image' => $v['main_image'] ?? null,
                'is_featured' => $v['is_featured'],
                'is_popular' => $v['is_popular'],
            ]);

            $reviews = [
                "Renting this {$vehicle->name} was an amazing experience in Mumbai! The pickup was seamless, and the vehicle performed flawlessly on the Indian roads. Will definitely rent again.",
                "Superb condition of the {$vehicle->name}. We took it for a trip to Lonavala and it handled the ghats beautifully. Highly recommended!",
                "Great service by the team. The {$vehicle->name} was clean, fully fueled/charged, and exactly as described. Best rental experience in India.",
                "Affordable price and great vehicle. The {$vehicle->name} gave fantastic mileage which saved us a lot of money on our road trip to Pune."
            ];

            // Add a mock review
            Review::create([
                'user_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'rating' => rand(4, 5),
                'comment' => $reviews[array_rand($reviews)],
                'is_approved' => true
            ]);
        }

        // 7. Seed Coupons
        Coupon::create([
            'code' => 'RENT20',
            'type' => 'percentage',
            'value' => 20.00,
            'start_date' => now()->subDays(1),
            'expiry_date' => now()->addMonths(6),
            'usage_limit' => 500,
            'min_booking_amount' => 4000.00,
            'is_active' => true
        ]);

        Coupon::create([
            'code' => 'WELCOME50',
            'type' => 'fixed',
            'value' => 4000.00,
            'start_date' => now()->subDays(1),
            'expiry_date' => now()->addMonths(12),
            'usage_limit' => 1000,
            'min_booking_amount' => 12000.00,
            'is_active' => true
        ]);

        // 8. Seed FAQs
        $faqs = [
            'What documents are required to rent a vehicle?' => 'You will need a valid driver\'s license (international drivers require a permit), a passport or national ID, and a credit card in the primary driver\'s name.',
            'Is insurance included in the rental price?' => 'Standard third-party liability insurance is included. We also offer premium Full Collision Damage Waiver (CDW) packages during pickup for peace of mind.',
            'What is your cancellation policy?' => 'Cancellations made up to 48 hours before the scheduled pickup time are eligible for a full refund. Cancellations inside 48 hours will incur a one-day rental fee.',
            'Can I pick up a vehicle at one location and return it to another?' => 'Yes, we support one-way rentals between any of our active depots. A minor one-way drop fee may apply depending on the distance.',
            'Is there a mileage limit?' => 'Most of our vehicles (especially cars and electric models) include unlimited mileage. High-performance luxury cars may have daily limits, which are listed on their specification sheets.'
        ];

        $idx = 1;
        foreach ($faqs as $q => $a) {
            Faq::create([
                'question' => $q,
                'answer' => $a,
                'order_num' => $idx++,
                'is_active' => true
            ]);
        }

        // 9. Seed Blog Categories & Posts
        $blogCat = BlogCategory::create(['name' => 'Travel Tips', 'slug' => 'travel-tips']);
        $blogCat2 = BlogCategory::create(['name' => 'Electric Vehicles', 'slug' => 'electric-vehicles']);

        Blog::create([
            'category_id' => $blogCat->id,
            'title' => 'Top 10 Road Trips in Maharashtra',
            'slug' => 'top-10-road-trips-maharashtra',
            'content' => 'Maharashtra offers some of the most scenic drives in the world. From the lush Western Ghats to the stunning Konkan Coast, there\'s no shortage of breathtaking views. In this guide, we explore the best routes, rest stops, and vehicle recommendations for your next coastal getaway...',
            'is_published' => true,
            'views' => 120
        ]);

        Blog::create([
            'category_id' => $blogCat2->id,
            'title' => 'The Complete Guide to Charging Your Rental EV in India',
            'slug' => 'complete-guide-charging-rental-ev-india',
            'content' => 'Renting an electric vehicle in India is an exciting experience, but understanding the charging infrastructure can be daunting. We walk you through Level 2 vs DC Fast Charging, how to use networks like Tata Power EZ Charge and Zeon Charging, and tips to maximize battery range during your rental period...',
            'is_published' => true,
            'views' => 85
        ]);
    }
}
