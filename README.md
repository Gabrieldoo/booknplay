# BookNPlay

BookNPlay is a Laravel 11 sports field booking system with authentication, user/admin dashboards, booking calendar, reviews, notifications, and payment integration skeleton.

## Features

- Authentication (register, login, logout, session-based)
- Role-based access (`admin`, `user`) via middleware
- Booking management with validation and conflict prevention
- Rating and review system (1-5 stars + comments)
- Laravel notifications (database + optional email)
  - Booking success
  - Booking cancellation
  - Booking reminder (scheduled command)
- Midtrans payment skeleton
  - Initiate payment endpoint
  - Callback endpoint
- Booking calendar view (booked/available slots)
- Admin dashboard statistics + Chart.js
- Booking filter, sorting, search, pagination
- Contact page with Google Maps embed
- Professional footer + floating WhatsApp button

## Tech Stack

- Laravel 11
- PHP 8.2+
- MySQL
- Blade + Bootstrap 5

## Installation

1. Clone the repository and enter the project directory.
2. Install dependencies:
   - `composer install`
   - `npm install`
3. Copy environment file:
   - `cp .env.example .env`
4. Generate key:
   - `php artisan key:generate`
5. Configure `.env`:
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `MAIL_*` (optional, for email notifications)
   - `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION` (skeleton only)
6. Run migrations and seeders:
   - `php artisan migrate --seed`
7. Create storage link:
   - `php artisan storage:link`
8. Build assets:
   - `npm run build`
9. Run app:
   - `php artisan serve`

## Default Accounts

- Admin: `admin@booknplay.test` / `password`
- User: `user@booknplay.test` / `password`

## Scheduled Reminder

Run scheduler on server:

- `php artisan schedule:work`

Reminder command:

- `php artisan bookings:send-reminders`

## Deployment Notes

- Keep `.env` server-specific and never commit real keys.
- Use `php artisan config:cache` and `php artisan route:cache` in production.
- Ensure write permissions for `storage/` and `bootstrap/cache/`.
- Backup database using `mysqldump` periodically.

## File Structure Highlights

- `app/Http/Controllers/BookingController.php`
- `app/Http/Controllers/AdminDashboardController.php`
- `app/Http/Controllers/AdminBookingController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/ReviewController.php`
- `app/Services/PaymentService.php`
- `app/Notifications/*`
- `database/migrations/*reviews*`
- `resources/views/admin/bookings/index.blade.php`
- `resources/views/booking.blade.php`
- `resources/views/userdashboard.blade.php`
- `resources/views/jadwal.blade.php`
- `resources/views/contact.blade.php`
- `resources/views/partials/footer.blade.php`

## Security Notes

- Eloquent queries are used to avoid SQL injection risk.
- Backend and frontend form validation are implemented.
- Passwords are hashed (`Hash::make` / bcrypt under Laravel).
- Routes are protected by `auth`, `admin`, and `user` middleware.
