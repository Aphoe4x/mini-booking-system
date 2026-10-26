# AGENTS.md

Guidance for AI coding agents (and humans) working in this repository.

## What this project is

**Book with Aphoe** — a multi-company booking marketplace built on Laravel 12 + Blade + Tailwind CSS + Alpine.js.

- `client` users browse companies, compare services/prices, and book slots.
- `admin` users see every booking and confirm or cancel it.
- Aphoe is the agent in the middle: companies publish services with prices, clients book them.

Read `README.md` for the product overview, routes and schema.

## First-time setup

```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed    # creates + seeds database/database.sqlite
npm install
npm run build
php artisan serve
```

Demo accounts: `admin@example.com` / `password`, `test@example.com` / `password`.

## Commands

| Task | Command |
| --- | --- |
| Run all tests | `php artisan test` |
| Run one test file | `php artisan test tests/Feature/MarketplaceBookingTest.php` |
| Format PHP | `./vendor/bin/pint` (or `./vendor/bin/pint <file>`) |
| Check formatting | `./vendor/bin/pint --test` |
| Build assets | `npm run build` |
| Dev servers | `composer run dev` or `npm run dev` |
| Clear compiled views | `php artisan view:clear` |
| Reset + reseed DB | `php artisan migrate:fresh --seed` |

> Windows/PowerShell: use `curl.exe`, avoid `&&`, run one command per call.

## Architecture map

| Concern | Location |
| --- | --- |
| Web routes | `routes/web.php`, `routes/auth.php` |
| Booking rules | `app/Http/Controllers/BookingController.php` |
| Marketplace listing/detail | `app/Http/Controllers/CompanyController.php` |
| Home metrics | `app/Http/Controllers/DashboardController.php` |
| Models | `app/Models/{Booking,Company,CompanyService,Service,User}.php` |
| Email | `app/Mail/BookingStatusChanged.php`, `resources/views/emails/booking-status.blade.php` |
| App shell / sidebar | `resources/views/layouts/app.blade.php` |
| Navbar / theme toggle | `resources/views/components/app-nav.blade.php` |
| Sidebar link component | `resources/views/components/dashboard-nav-link.blade.php` |
| Alpine stores | `resources/js/app.js` |
| Seed data | `database/seeders/DatabaseSeeder.php` |
| Regression tests | `tests/Feature/MarketplaceBookingTest.php` |

## Non-negotiable business rules

Do not change these without an explicit request and updated tests:

1. **Prices are server-side only.** `BookingController@store` looks up `company_services.price` and writes it to `bookings.price_paid`. Never trust a submitted price.
2. **Bookings are Monday–Friday, 09:00–18:00.** Constants `BUSINESS_HOURS_START`, `BUSINESS_HOURS_END`, `BUSINESS_DAYS` in `BookingController`.
3. **Slot uniqueness** is `company_service_id + booking_date + booking_time`, ignoring `cancelled` bookings.
4. **Time normalisation:** store `H:i:s`, validate input as `H:i`. `Booking` casts `booking_time` to `datetime:H:i` and `booking_date` to `date:Y-m-d` so SQLite text comparisons behave.
5. **Authorisation:** admin = `users.role === 'admin'`. Admin routes check it in the controller (`abort_unless`) *and* hide links in the UI. Clients may only cancel their own bookings.
6. **Home route:** `/home` carries the name `dashboard`; `/dashboard` redirects to `/home` for old links.

## Conventions

- **Blade components:** `@props([...])` removes declared attributes from `$attributes`. If an attribute must reach the DOM, print it explicitly, e.g. `<a href="{{ $href }}" {{ $attributes->merge(...) }}>`.
- **Styling:** Tailwind utility classes only. Support both light and dark mode (`dark:` variants) for every new surface. Match the existing slate/blue palette.
- **Interactivity:** Alpine.js. The sidebar uses a global store: `Alpine.store('sidebar')` with `open`, `toggle()`, `close()`.
- **Layouts:** authenticated pages use `resources/views/layouts/app.blade.php`; auth pages use `layouts/guest.blade.php`.
- **English UI copy** via `__()` where the surrounding file already does so.
- **No new JS frameworks** (React/Vue/etc.) and no CDN Tailwind — keep the Vite pipeline.
- Prefer named routes (`route('bookings.index')`) over hard-coded URLs.

## Asset / view workflow (common source of "it didn't change")

Tailwind and Vite compile ahead of time:

1. After editing Blade markup or Tailwind classes: `npm run build` (or keep `npm run dev` running).
2. After editing Blade templates: `php artisan view:clear`.
3. Hard-refresh the browser (Ctrl+F5) to bypass cached assets.
4. Never edit `public/build/*` by hand — it is generated.

## Debugging gotchas

- **Background browser tabs pause CSS transitions and `requestAnimationFrame`.** Measurements of a closing/opening sidebar taken in a hidden tab will look "stuck". Verify in a visible window or temporarily disable transitions.
- **SQLite date/time:** always compare dates with `whereDate()` and keep the stored format consistent (`Y-m-d`, `H:i:s`).
- **`migrate:fresh --seed` destroys sessions** (database session driver) and logs everyone out.
- **`SESSION_DRIVER=database`** means the app needs the sessions table migrated before login works.
- **Money fields** use `decimal(8,2)` and casts; avoid float arithmetic for totals.

## Testing expectations

- Any behavioural change to bookings, pricing, slots, hours or authorisation **requires** a test in `tests/Feature/MarketplaceBookingTest.php` (or a new feature test).
- Run the full suite before proposing a commit: `php artisan test`.
- Run `./vendor/bin/pint` on files you touched. A global `pint --test` may report pre-existing issues in untouched files; do not reformat unrelated code.
- UI-only changes still need a manual check at mobile (≈375px), tablet (≈768–1000px) and desktop (≥1280px) widths.

## Git hygiene

- Do **not** commit: `.env`, `database/database.sqlite`, `storage/logs/*`, `public/build/*`, `vendor/`, `node_modules/`, compiled views.
- Keep commits focused and use conventional prefixes (`feat:`, `fix:`, `docs:`, `refactor:`, `test:`).
- Never push to `origin/main` without explicit user confirmation.
- Do not rewrite published history or force-push shared branches.

## When in doubt

1. Read the relevant controller and its feature test first.
2. Make the smallest change that satisfies the requirement.
3. Run tests + build.
4. Report what changed, why, and anything that still needs a manual check.
