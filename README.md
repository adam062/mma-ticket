# MMA Championship Ticket Booking System

A modern Laravel web application for MMA event ticketing with dark mode, multi-step booking, admin/gate dashboards, and Telegram integration.

## Features

- **Black Theme Design** - Pure black background with red accent UI
- **Multi-Step Booking** - 4 steps: Customer Info → Ticket & Quantity → Payment Method → Payment Proof
- **Animated Logo** - SVG with pulse and bounce animations
- **Admin Dashboard** - Manage bookings, tickets, ticket types, settings, and Telegram bots
- **Gate Dashboard** - QR scanning, ticket verification, and lookup
- **Telegram Integration** - Link tickets to Telegram for real-time notifications
- **Payment Methods** - Vodafone Cash and InstaPay support
- **Responsive Design** - Works on all devices with RTL Arabic support

## Requirements

- PHP 8.4+
- Composer
- Node.js 20+
- MySQL or SQLite

## Installation

```bash
# Clone the repository
git clone https://github.com/adam062/mma-ticket.git
cd mma-ticket

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Configure database (MySQL or SQLite)
# Edit .env with your database credentials

# Run migrations
php artisan migrate --seed

# Start development server
php artisan serve
# Then in another terminal:
npm run dev
```

## Usage

### Public Access
- Visit `http://localhost:8000` for the home page
- Click **Book Ticket** to start the 4-step booking process
- Select ticket type, quantity, payment method, and upload payment proof

### Admin Access
- Login at `/login/admin`
- Default credentials: `admin@mma.test` / `password`
- Access admin dashboard at `/admin`

### Gate Access
- Login at `/login/gate`
- Default credentials: `gate@mma.test` / `password`
- Access gate dashboard at `/gate/dashboard`

## Booking Flow

1. **Step 1 - Customer Information**: Enter name, phone, and email
2. **Step 2 - Ticket & Quantity**: Select ticket type and quantity (with live total calculation)
3. **Step 3 - Payment Method**: Choose Vodafone Cash or InstaPay
4. **Step 4 - Payment Details**: Enter transfer phone, upload screenshot, submit

## Admin Dashboard

- View and manage all bookings
- Approve/deny requests or request resubmission
- Manage ticket types (create, edit, assign)
- Configure event settings (name, dates, logo)
- Configure payment methods (Vodafone Cash, InstaPay)
- Set up Telegram bot integration

## Gate Dashboard

- Scan QR codes to validate tickets
- Look up tickets by serial number
- Verify ticket status (active, used, cancelled)
- View ticket usage statistics

## Development Commands

```bash
# Testing
php artisan test

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Admin login simulation (for testing)
php artisan tinker --execute "..."
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin dashboard controllers
│   │   ├── Gate/           # Gate scanner controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── Public/         # Public-facing controllers
│   ├── Middleware/         # Auth, role middleware
│   └── Requests/           # Form validation requests
├── Models/                 # Eloquent models
└── Notifications/          # Email notifications
resources/
├── views/                  # Blade templates
│   ├── admin/              # Admin views
│   ├── gate/               # Gate/scanner views
│   ├── public/             # Public-facing views
│   ├── auth/               # Auth views
│   └── layouts/            # Shared layouts
routes/
├── web.php                 # Public routes
├── admin.php               # Admin routes
└── gate.php                # Gate routes
```

## License

MIT License. See `LICENSE` file for details.
