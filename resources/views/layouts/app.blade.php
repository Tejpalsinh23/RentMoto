<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ App\Models\Setting::get('site_name', 'RentMoto') }} - Vehicle Rental System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Initialize Theme before rendering to prevent flashing -->
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- Swiper Slider (for image galleries) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Premium Custom Styles -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --accent-color: #06b6d4;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --navbar-bg: rgba(255, 255, 255, 0.85);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --glass-blur: blur(12px);
        }

        [data-theme="dark"] {
            --bg-color: #0b0f19;
            --card-bg: #151f32;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #1e293b;
            --navbar-bg: rgba(15, 23, 42, 0.85);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.5);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background-color: var(--navbar-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
            z-index: 1050;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-color) !important;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(79, 70, 229, 0.08);
        }

        /* Standard Cards Styling */
        .card-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        /* Footer styling */
        footer {
            background-color: #0b0f19;
            color: #94a3b8;
            border-top: 1px solid #1e293b;
        }

        /* Interactive Elements styling */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Dark Mode Toggle */
        .theme-toggle-btn {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .theme-toggle-btn:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fa-solid fa-car-side me-2"></i>{{ App\Models\Setting::get('site_name', 'RentMoto') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <!-- Light/Dark Mode Switcher -->
                    <button class="theme-toggle-btn" id="themeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon fs-5" id="themeIcon"></i>
                    </button>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" id="authDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle fs-5"></i>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="authDropdown" style="background-color: var(--card-bg); border: 1px solid var(--border-color) !important;">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item py-2 fw-semibold text-primary" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-lock me-2"></i>Admin Panel</a></li>
                                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                @endif
                                <li><a class="dropdown-item py-2 text-decoration-none" href="{{ route('dashboard') }}" style="color: var(--text-color);"><i class="fa-solid fa-gauge me-2 text-muted"></i>Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard.bookings') }}" style="color: var(--text-color);"><i class="fa-solid fa-calendar-check me-2 text-muted"></i>My Bookings</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard.wishlist') }}" style="color: var(--text-color);"><i class="fa-solid fa-heart me-2 text-muted"></i>Wishlist</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard.settings') }}" style="color: var(--text-color);"><i class="fa-solid fa-sliders me-2 text-muted"></i>Settings</a></li>
                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item py-2 text-danger" type="submit">
                                            <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-reset fw-semibold">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        <!-- Toast Notification Area -->
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show card-custom p-3 border-0 d-flex align-items-center shadow-sm" role="alert" style="border-left: 5px solid #22c55e !important; background-color: var(--card-bg);">
                    <i class="fa-solid fa-circle-check text-success fs-4 me-3"></i>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show card-custom p-3 border-0 d-flex align-items-center shadow-sm" role="alert" style="border-left: 5px solid #ef4444 !important; background-color: var(--card-bg);">
                    <i class="fa-solid fa-circle-exclamation text-danger fs-4 me-3"></i>
                    <div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Area -->
    <footer class="py-5 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3"><i class="fa-solid fa-car-side me-2 text-primary"></i>{{ App\Models\Setting::get('site_name', 'RentMoto') }}</h5>
                    <p class="small mb-4 text-secondary">Premium vehicle rental platform offering cars, electric vehicles, bikes, and luxury rentals across India. Simple booking, affordable pricing, and top-tier customer support.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-white">Home</a></li>
                        <li class="mb-2"><a href="{{ route('vehicles.index') }}" class="text-secondary text-decoration-none hover-white">Find Vehicles</a></li>
                        <li class="mb-2"><a href="{{ route('blog.index') }}" class="text-secondary text-decoration-none hover-white">Latest Blogs</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-secondary text-decoration-none hover-white">About Company</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-secondary text-decoration-none hover-white">Contact Support</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#faq-section" class="text-secondary text-decoration-none hover-white">FAQs</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none hover-white">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none hover-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Newsletter</h6>
                    <p class="small text-secondary mb-3">Subscribe to receive promotions, travel discounts, and our seasonal guides.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="input-group">
                        @csrf
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="Your email address" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="border-secondary my-4 opacity-25">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-secondary">
                <p class="mb-0">&copy; {{ date('Y') }} {{ App\Models\Setting::get('site_name', 'RentMoto') }}. All rights reserved.</p>
                <p class="mb-0">Built with Laravel 12 & Bootstrap 5</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Dark Mode & Theme Toggle Logic -->
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const currentTheme = localStorage.getItem('theme') || 'light';

        document.documentElement.setAttribute('data-theme', currentTheme);
        updateIcon(currentTheme);

        themeToggle.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            let newTheme = theme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });

        function updateIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fa-solid fa-sun fs-5';
            } else {
                themeIcon.className = 'fa-solid fa-moon fs-5';
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
