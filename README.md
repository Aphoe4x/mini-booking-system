# Book with Aphoe

A multi-company booking marketplace built with Laravel. Aphoe sits between companies and their clients: companies publish the services they offer and their prices, and clients browse, compare and book available slots in one place.

> **Live demo:** not currently hosted. The previous Railway instance is no longer active — run it locally with the [quick start](#quick-start), or see [Deployment](#deployment-optional) to host it again.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpine.js&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite&logoColor=white)
![Tests](https://img.shields.io/badge/tests-29_passing-brightgreen)

---

## Table of contents

- [Overview](#overview)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Quick start](#quick-start)
- [Demo accounts](#demo-accounts)
- [Seeded data](#seeded-data)
- [Business rules](#business-rules)
- [Routes](#routes)
- [Data model](#data-model)
- [Testing](#testing)
- [Deployment](#deployment-optional)
- [Project structure](#project-structure)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## Overview

Most booking tools assume a single business. **Book with Aphoe** is built for a marketplace of many companies, each with its own catalogue and prices:

- **Clients** discover companies, compare services and prices, and book a slot.
- **Companies** (represented in the catalogue) offer one or more services at their own prices.
- **Aphoe / admin** oversees the bookings and confirms or cancels them, acting as the go-between.

The UI follows a clean, Stripe-dashboard-style layout: a persistent sidebar, a top bar with global search and a user menu, a light/dark theme toggle, and fully responsive navigation for mobile, tablet and desktop.

## Features

### Marketplace

- Company directory with search by name or city.
- Each company card shows the number of available services and its starting price.
- Company detail pages group services by category, list durations and prices, and deep-link into a pre-filled booking form (`?company=…&service=…`).
- Only active companies and available services are listed.

### Bookings

- Booking form with company → service dropdowns and a live price/duration summary.
- The price shown to the client comes from the database and is **recalculated on the server**; a tampered `price_paid` in the request is ignored.
- Clients see their own upcoming and past bookings.
- Clients can cancel their own bookings.
- Admin sees all bookings and can mark them **confirmed** or **cancelled**.
- Status changes and new requests send an email (queued `BookingStatusChanged` mailable).

### UX

- Stripe-style dashboard with a shared sidebar store (Alpine.js) used by the navbar, drawer and backdrop.
- Icon-only light/dark theme toggle; the choice is persisted in `localStorage` and applied before first paint to avoid a flash.
- Off-canvas sidebar on mobile/tablet, static sidebar on desktop.
- Back buttons on detail/form pages and breadcrumb-style links back to the home page.
- Accessible labels, `x-cloak` to prevent flicker, and reduced-motion-safe transitions.
- Dark mode palette matches the navbar across every screen.

### Security

- Laravel Breeze authentication, email verification, password reset and password confirmation.
- Role check (`admin` vs `client`) enforced in the controller **and** the UI.
- Clients are blocked from admin routes; guests are redirected to login.
- CSRF protection on all mutating routes.
- In production the app forces HTTPS and trusts proxy headers (Railway/Render-compatible).

## Tech stack

| Layer | Choice |
| --- | --- |
| Framework | Laravel 12 (PHP 8.2+) |
| Auth | Laravel Breeze |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js 3 |
| Build | Vite 7 |
| Database | SQLite (zero-config local default; MySQL/Postgres supported) |
| Mail | Laravel Mail (`log` driver locally, queued mailable) |
| Tests | PHPUnit 11 |

## Quick start

### Requirements

- PHP **8.2+** with `pdo_sqlite`
- Composer 2
- Node.js 18+ and npm

### Install

```bash
git clone https://github.com/Aphoe4x/mini-booking-system.git
cd mini-booking-system

composer install
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate

# Create the SQLite database and seed demo data
touch database/database.sqlite   # Windows: New-Item database\database.sqlite
php artisan migrate --seed

npm install
npm run build

php artisan serve
```

Open <http://127.0.0.1:8000> and log in with one of the [demo accounts](#demo-accounts).

> The app uses `SESSION_DRIVER=database`, so sessions and the cache live in SQLite too — no extra services required.

### Day-to-day development

```bash
composer run dev   # serve + queue listener + logs + vite in one command
npm run dev        # Vite dev server only
```

## Demo accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Client | `test@example.com` | `password` |

The seeder is idempotent (`updateOrCreate`), so re-running it will not duplicate records.

## Seeded data

Five companies, seven services, plus two sample bookings:

| Company | Service | Price | Duration |
| --- | --- | --- | --- |
| Glow Spa & Wellness | Relaxation Massage | $85.00 | 60 min |
| Glow Spa & Wellness | Deep Cleanse Facial | $65.00 | 45 min |
| City Dental Care | Teeth Cleaning | $120.00 | 45 min |
| City Dental Care | Dental Checkup | $80.00 | 30 min |
| Elite Barbershop | Haircut | $35.00 | 30 min |
| Fitness Hub Personal Training | Personal Training Session | $75.00 | 60 min |
| ConsultPro Business Advisors | Business Consulting | $150.00 | 60 min |

Reset everything at any time:

```bash
php artisan migrate:fresh --seed
```

> This wipes sessions and logs every browser tab out.

## Business rules

| Rule | Where it lives |
| --- | --- |
| Price always comes from `company_services.price`; posted prices are ignored | `BookingController@store` |
| Bookings only Monday–Friday, 09:00–18:00 | `BookingController` constants |
| One booking per `company_service_id + booking_date + booking_time`, cancelled slots are reusable | `BookingController@store` + DB index |
| Times are normalised to `H:i:s`; dates are cast to `Y-m-d` to keep SQLite comparisons correct | `Booking` casts |
| `pending` → `confirmed` / `cancelled` (admin); clients may only cancel their own | `BookingController` |
| Admin routes require `role === 'admin'` | `BookingController` + `User::isAdmin()` |

> **Money and trust:** never add a hidden `price` input that the server trusts. All amounts must be looked up from the `company_services` row.

## Routes

| Method | URI | Name | Access |
| --- | --- | --- | --- |
| GET | `/` | — | Redirects to `/bookings` |
| GET | `/home` | `dashboard` | Auth |
| GET | `/dashboard` | — | Redirects to `/home` |
| GET | `/companies` | `companies.index` | Auth |
| GET | `/companies/{company}` | `companies.show` | Auth |
| GET | `/bookings` | `bookings.index` | Auth |
| GET | `/bookings/create` | `bookings.create` | Auth |
| POST | `/bookings` | `bookings.store` | Auth |
| PATCH | `/bookings/{booking}/cancel` | `bookings.cancel` | Auth (owner) |
| GET | `/admin/bookings` | `admin.bookings` | Admin |
| PATCH | `/admin/bookings/{booking}/status` | `admin.bookings.status` | Admin |
| GET/PATCH/DELETE | `/profile` | `profile.*` | Auth |
| — | `/login`, `/register`, `/forgot-password`, … | Breeze | Guest |
| GET | `/up` | — | Health check |

## Data model

```
users ──< bookings >── company_services >── companies
                            │
                            └── services

company_services = companies ⨯ services (pivot with price, duration, availability)
```

- `users.role` is `admin` or `client`.
- `bookings.company_service_id` points at the priced pivot row; `bookings.service_name` is a denormalised label kept for display/backward compatibility.
- `bookings.price_paid` snapshots the price at booking time.
- `companies.logo` exists for future uploads; the UI currently renders generated initials instead.

## Testing

```bash
php artisan test          # 29 tests, 87 assertions
composer test             # clears config then runs the suite
./vendor/bin/pint         # format PHP (Pint)
./vendor/bin/pint --test  # check formatting
```

Coverage highlights:

- Authentication, password reset/confirmation, registration, profile management.
- Company listing/search/detail.
- Server-side pricing (tampered price ignored).
- Duplicate-slot rejection, weekend rejection, after-hours rejection.
- Client/admin authorisation boundaries.

## Deployment (optional)

The app is a standard Laravel 12 project and runs on any PHP host. An earlier Railway instance is no longer active, but Railway, Render, Fly.io or a plain VPS all work with the settings below.

Typical settings:

| Setting | Value |
| --- | --- |
| Build | `composer install --no-dev --optimize-autoloader && npm ci && npm run build` |
| Release | `php artisan migrate --force` |
| Start | `php artisan serve --host=0.0.0.0 --port=$PORT` (or a php-fpm/nginx image) |
| Env | `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY=…`, `APP_URL=https://your-domain` |

Notes:

- `bootstrap/app.php` trusts proxy headers and `AppServiceProvider` forces HTTPS in production.
- The default database is SQLite. On ephemeral hosts, attach a persistent volume (e.g. mount at `/data`) and set `DB_DATABASE=/data/database.sqlite`, otherwise data resets on every deploy. For anything beyond a demo, prefer managed Postgres/MySQL.
- Set `MAIL_MAILER` to a real transport; locally it is `log`, so emails are written to `storage/logs/laravel.log`.
- Run `php artisan config:cache route:cache view:cache` after deploy if you are not using a release step.

## Project structure

```
app/
├── Http/Controllers/
│   ├── BookingController.php      # booking CRUD, business rules, admin actions
│   ├── CompanyController.php      # marketplace listing + detail
│   ├── DashboardController.php    # Home metrics
│   └── ProfileController.php
├── Mail/BookingStatusChanged.php  # queued status email
└── Models/
    ├── Booking.php  Company.php  CompanyService.php  Service.php  User.php

database/
├── migrations/                    # users, bookings, companies, services, pivot
└── seeders/DatabaseSeeder.php     # demo users, companies, services, bookings

resources/
├── css/app.css                    # Tailwind + dark mode
├── js/app.js                      # Alpine + sidebar store
└── views/
    ├── layouts/app.blade.php      # shell: navbar, sidebar, backdrop, content
    ├── components/app-nav.blade.php
    ├── companies/{index,show}.blade.php
    ├── bookings/{index,create}.blade.php
    ├── admin/bookings.blade.php
    ├── dashboard.blade.php        # "Home"
    └── emails/booking-status.blade.php

routes/web.php                     # web routes
tests/Feature/MarketplaceBookingTest.php
```

## Troubleshooting

| Symptom | Fix |
| --- | --- |
| Styles or icons look stale | `npm run build` (or `npm run dev`), then hard-refresh |
| Blade changes not showing | `php artisan view:clear` |
| `no such table` / empty data | `php artisan migrate --seed` |
| Logged out after reseeding | Expected — `migrate:fresh` clears the database session table |
| Emails not arriving | Local mailer is `log`; check `storage/logs/laravel.log` |
| Sidebar does not move while inspecting in a background tab | Browsers pause CSS transitions in hidden tabs; check in a visible window |

## License

Released under the [MIT License](https://opensource.org/licenses/MIT).
