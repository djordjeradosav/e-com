# Laravel E-commerce Shopping Cart

A simple e-commerce shopping cart system built with Laravel, React (Inertia.js), and Tailwind CSS. This project demonstrates best practices for building modern Laravel applications with features like queued jobs, scheduled tasks, and authenticated user management.

## Features

- ✅ User Authentication (Laravel Breeze)
- ✅ Product Browsing
- ✅ Shopping Cart Management (Add, Update, Remove)
- ✅ User-specific Cart (Database-stored, not session-based)
- ✅ Stock Management
- ✅ Checkout Process
- ✅ Low Stock Email Notifications (Queued Jobs)
- ✅ Daily Sales Report (Scheduled Tasks)
- ✅ Responsive Design (Tailwind CSS)

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: React with Inertia.js
- **Styling**: Tailwind CSS
- **Database**: MySQL/SQLite
- **Queue**: Database driver

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or SQLite

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/shopping-cart.git
cd shopping-cart
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopping_cart
DB_USERNAME=root
DB_PASSWORD=
```

For SQLite (simpler option):
```env
DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite
```

### 5. Configure Mail and Queue

Add these to your `.env`:

```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
ADMIN_EMAIL=admin@example.com
```

**Note**: Emails will be logged to `storage/logs/laravel.log`. For production, configure a proper mail service like SMTP or Mailgun.

### 6. Run Migrations and Seeders

```bash
# Create database tables
php artisan migrate

# Create queue jobs table
php artisan queue:table
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

## Running the Application

You need to run these commands in separate terminal windows:

### Terminal 1: Laravel Development Server
```bash
php artisan serve
```

### Terminal 2: Vite Development Server (for hot reload)
```bash
npm run dev
```

### Terminal 3: Queue Worker
```bash
php artisan queue:work
```

### Terminal 4: Task Scheduler (for testing scheduled tasks)
```bash
php artisan schedule:work
```

Visit: `http://localhost:8000`

## Default User Credentials

**Admin User**:
- Email: admin@example.com
- Password: password

**Test User**:
- Email: user@example.com
- Password: password

## Key Features Explanation

### 1. Shopping Cart
- Cart items are tied to authenticated users and stored in the database
- Users can add products, update quantities, and remove items
- Stock validation ensures users cannot add more items than available

### 2. Low Stock Notifications
- When a product's stock falls below the threshold after checkout, a job is queued
- The job sends an email notification to the admin
- Check `storage/logs/laravel.log` to see the email content

### 3. Daily Sales Report
- Scheduled to run daily at 6:00 PM
- Sends a comprehensive report of all orders placed that day
- Includes order details and product sales summary

**Test manually**:
```bash
php artisan app:send-daily-sales-report
```

## Project Structure

```
app/
├── Console/Commands/
│   └── SendDailySalesReport.php      # Daily sales report command
├── Events/
│   └── ProductStockLow.php           # Low stock event
├── Http/Controllers/
│   ├── CartController.php            # Cart operations
│   ├── CheckoutController.php        # Checkout process
│   └── ProductController.php         # Product listing
├── Jobs/
│   └── SendLowStockNotification.php  # Low stock email job
├── Listeners/
│   └── TriggerLowStockNotification.php
├── Mail/
│   ├── DailySalesReport.php
│   └── LowStockAlert.php
├── Models/
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   └── User.php
database/
├── migrations/
└── seeders/
resources/
├── js/Pages/
│   ├── Cart/Index.jsx
│   ├── Checkout/Success.jsx
│   └── Products/Index.jsx
└── views/emails/
    ├── daily-sales-report.blade.php
    └── low-stock-alert.blade.php
```

## Testing the Features

### Test Low Stock Notification
1. Login as a user
2. Browse products
3. Find a product with low stock (e.g., "Wireless Mouse" has 8 units, threshold is 10)
4. Add multiple items to cart and checkout
5. Check `storage/logs/laravel.log` for the email notification

### Test Daily Sales Report
1. Place some orders during the day
2. Run the command manually:
   ```bash
   php artisan app:send-daily-sales-report
   ```
3. Check `storage/logs/laravel.log` for the report

## Production Deployment

### Cron Job Setup
Add this to your server's crontab:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (Supervisor)
For production, use Supervisor to keep the queue worker running:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/worker.log
```

## API Endpoints

| Method | URI | Description |
|--------|-----|-------------|
| GET | /products | List all products |
| GET | /cart | View shopping cart |
| POST | /cart | Add item to cart |
| PATCH | /cart/{cartItem} | Update cart item quantity |
| DELETE | /cart/{cartItem} | Remove item from cart |
| POST | /checkout | Process checkout |
| GET | /checkout/success | Checkout success page |

## Database Schema

### Products
- id, name, description, price, stock_quantity, low_stock_threshold, image, timestamps

### Cart Items
- id, user_id, product_id, quantity, timestamps
- Unique constraint on (user_id, product_id)

### Orders
- id, user_id, total_amount, status, timestamps

### Order Items
- id, order_id, product_id, quantity, price_at_purchase, timestamps

## Contributing

This is a demo project created for a technical assessment. Feel free to fork and modify as needed.

## License

This project is open-sourced software licensed under the MIT license.

## Troubleshooting

**Queue not processing?**
- Make sure you're running `php artisan queue:work`
- Check `storage/logs/laravel.log` for errors

**Emails not sending?**
- Emails are logged to `storage/logs/laravel.log` by default
- Check your `MAIL_MAILER=log` setting in `.env`

**Migrations failing?**
- Ensure database credentials are correct in `.env`
- Run `php artisan migrate:fresh --seed` to reset database

**Assets not loading?**
- Run `npm run dev` for development
- Run `npm run build` for production

## Contact

For questions or issues, please contact the repository owner.# e-com
