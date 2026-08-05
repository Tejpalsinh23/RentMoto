<div align="center">

# 🏍️ RentMoto

### A Complete Vehicle Rental Management System

![Laravel](https://img.shields.io/badge/Laravel-^13.8-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-^8.3-777BB4?style=for-the-badge&logo=php)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-^3.1-06B6D4?style=for-the-badge&logo=tailwindcss)
![Vite](https://img.shields.io/badge/Vite-^8.0-646CFF?style=for-the-badge&logo=vite)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

RentMoto is a full-featured vehicle rental platform built with **Laravel 13**, **Tailwind CSS**, and **Alpine.js**. It lets customers browse, book, and pay for vehicles online while providing administrators with a powerful dashboard to manage the entire fleet, bookings, payments, and content.

</div>

---

## ✨ Features

### 🚗 Customer Facing
- **Vehicle Catalog** — Browse cars, bikes, scooters, SUVs, EVs, vans, and more with rich filters (category, brand, fuel type, transmission, price).
- **Vehicle Details** — Detailed specs, image galleries, features list, and customer reviews.
- **Online Booking** — Real-time price calculation (days, tax, discounts) with pickup/return location & date selection.
- **Coupon Discounts** — Apply promo codes (percentage or fixed amount) at checkout.
- **Wishlist** — Save favourite vehicles for later.
- **Reviews & Ratings** — Leave star ratings and comments after a rental.
- **Blog** — Travel tips and EV guides with categories and comments.
- **Contact & Newsletter** — Contact form and newsletter subscription.
- **PDF Invoices** — Download printable invoices for booked rentals.

### 👤 Customer Dashboard
- View and track booking history & status.
- Manage wishlist, reviews, and profile settings (name, email, password).

### 🛡️ Admin Panel
- **Dashboard** — Overview of revenue, bookings, fleet, and customers.
- **Vehicle Management** — Full CRUD for vehicles, categories, brands, and image galleries.
- **Booking Management** — View, filter, and update booking statuses (pending → confirmed → completed/cancelled).
- **Customers** — List and manage registered customers.
- **Payments** — Track payment transactions and statuses.
- **Reviews Moderation** — Approve or reject customer reviews.
- **Coupons** — Create and manage discount codes with usage limits.
- **Reports** — Export booking & revenue reports.
- **Content Management** — Locations, FAQs, blogs, contact messages, and newsletter subscribers.
- **Site Settings** — Configure site name, currency, tax rate, and contact information.

---

## 🧰 Tech Stack

| Layer      | Technology                                    |
|------------|-----------------------------------------------|
| Backend    | Laravel 13, PHP ^8.3                           |
| Frontend   | Blade, Tailwind CSS ^3.1, Alpine.js           |
| Build Tool | Vite ^8.0, Laravel Vite Plugin                 |
| Database   | MySQL (compatible with SQLite for development) |
| Auth       | Laravel Breeze (role-based: admin / customer)  |
| PDF        | barryvdh/laravel-dompdf                        |
| Testing    | PHPUnit, Laravel Pint                          |

---

## 📁 Project Structure

```
RentMoto/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin-side controllers
│   │   │   ├── Customer/      # Customer dashboard & booking
│   │   │   ├── Auth/          # Authentication controllers
│   │   │   └── ...            # Home, Payment, Invoice, Profile
│   │   └── Middleware/        # AdminMiddleware (role guard)
│   ├── Models/                # Eloquent models
│   └── View/Components/       # Layout components
├── database/
│   ├── migrations/            # Schema (incl. rental system tables)
│   └── seeders/               # Demo data (vehicles, users, coupons...)
├── resources/views/           # Blade templates
│   ├── admin/                 # Admin panel views
│   ├── customer/              # Customer dashboard views
│   ├── vehicles/              # Catalog & detail views
│   ├── booking/               # Checkout & invoice
│   └── ...
├── routes/
│   └── web.php                # All application routes
└── public/                    # Public assets
```

---

## 🚀 Installation

### Prerequisites
- PHP **^8.3** with common extensions
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm
- MySQL or SQLite

### Setup Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/RentMoto.git
cd RentMoto

# 2. Install PHP dependencies
composer install

# 3. Install front-end dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env, then migrate
php artisan migrate --seed

# 6. Build front-end assets
npm run build

# 7. Start the development server
php artisan serve
```

> 💡 **Tip:** Use the one-command setup script:
> ```bash
> composer run setup
> ```

The app will be available at `http://localhost:8000`.

### Demo Credentials

| Role     | Email                     | Password   |
|----------|---------------------------|------------|
| Admin    | `admin@rentalsystem.com`  | `password` |
| Customer | `customer@rentalsystem.com` | `password` |

---

## 🧪 Running Tests

```bash
composer run test
# or
php artisan test
```

Run code style checks with Laravel Pint:

```bash
./vendor/bin/pint
```

---

## 🖥️ Development

Start the Vite dev server and Laravel dev tools together:

```bash
composer run dev
```

This runs the application server, queue worker, Pail logs, and Vite hot-reload concurrently.

---

## 📬 Routing Overview

| Area     | Prefix    | Description                        |
|----------|-----------|------------------------------------|
| Public   | `/`        | Home, vehicles, blog, about, contact |
| Customer | `/dashboard` | Booking history, wishlist, reviews, settings |
| Booking  | `/booking`  | Price calculation & checkout      |
| Payment  | `/payment`  | Payment gateway, processing, success |
| Admin    | `/admin`    | Full admin dashboard & management  |

---

## 🗄️ Database Schema

The rental system includes 17+ tables:
- `vehicles`, `vehicle_categories`, `brands`, `vehicle_images`
- `locations`, `bookings`, `payments`, `coupons`
- `reviews`, `wishlists`
- `blog_categories`, `blogs`, `comments`
- `faqs`, `contact_messages`, `newsletter_subscribers`, `settings`

---

## 📜 License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">
  Made with ❤️ using Laravel
</div>
