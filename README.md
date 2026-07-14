# eStock

Professional online stock marketplace built with PHP and MySQL. Customers browse inventory, manage a cart, and check out with stock validation. Merchants manage products, categories, orders, and low-stock alerts from a dashboard.

## Requirements

- PHP 8.0+ (Laragon recommended on Windows)
- Composer 2.x
- MySQL 5.7+ or MariaDB 10.3+
- Apache with `mod_rewrite` optional (plain PHP file routing)
- PDO MySQL extension enabled

## Quick start (Laragon)

1. Place the project in your web root, e.g. `C:\laragon\www\estock`
2. Start **Apache** and **MySQL** from Laragon
3. Install PHP dependencies:

```bash
composer install
```

4. Copy environment config (also done automatically after `composer install` if `.env` is missing):

```bash
copy .env.example .env
```

5. Edit `.env` if needed (defaults work with Laragon's root user and empty password):

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=estock
DB_USER=root
DB_PASS=
APP_NAME=eStock
APP_URL=http://estock.test
APP_CURRENCY=MWK
APP_DEBUG=true
```

6. Create / migrate the database:

```bash
php artisan migrate:fresh --seed
```

Other options:

```bash
php artisan migrate
php artisan db:seed
```

Or import the SQL dump manually:

```bash
mysql -u root < sql/estock_file.sql
```

7. Ensure the uploads folder is writable:

```bash
mkdir uploads
```

On Linux/macOS:

```bash
chmod -R 775 uploads
```

8. Open the app:

- Virtual host: http://estock.test  
- Or: http://localhost/estock/

## Demo accounts

After `php artisan db:seed` or `migrate:fresh --seed`:

| Role     | Email                 | Password    |
|----------|-----------------------|-------------|
| Customer | customer@estock.test  | password123 |
| Merchant | merchant@estock.test  | password123 |

## Project structure

```
estock/
├── artisan                  # CLI: migrate, seed, etc.
├── database/
│   ├── migrations/          # SQL migrations
│   └── seeders/             # Demo seed data
├── config/bootstrap.php     # Env loader, session, helpers
├── connection.php           # PDO database class
├── models/                  # User, Product, Order, Category, ProductImage
├── merchant/                # Merchant dashboard
├── sql/                     # Legacy dump + upgrade scripts
├── uploads/                 # Product images (not committed)
├── vendor/                  # Composer dependencies
├── .env.example
├── composer.json
└── index.php
```

## Features

### Customers

- Browse and search products
- Session shopping cart with stock limits
- Checkout with shipping address and payment method
- Order confirmation and order history
- Customer / merchant registration

### Merchants

- Dashboard with product, order, revenue, and pending stats
- Low-stock alerts
- Product CRUD with images, SKU, and stock levels
- Category management
- Order list, detail view, and status updates
- Ownership checks on product and order actions

### Platform

- Environment-based database configuration
- Inventory deduction and movement log on checkout
- Consistent MWK currency formatting
- CSRF helpers available (`csrf_token()`, `verify_csrf()`)

## Common commands

```bash
# Install PHP dependencies
composer install

# Update dependencies
composer update

# Regenerate autoload after adding model classes
composer dump-autoload

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Copy env file
copy .env.example .env

# Fresh database install
mysql -u root -e "DROP DATABASE IF EXISTS estock; CREATE DATABASE estock CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root estock < sql/estock_file.sql

# Upgrade existing schema
mysql -u root estock < sql/upgrade.sql

# Check PHP version
php -v

# Check PDO MySQL
php -m | findstr pdo_mysql
```

## Local development tips

- Keep `APP_DEBUG=true` while developing to see database errors.
- Set `APP_DEBUG=false` on any shared/public host.
- Product images are stored under `uploads/`.
- Currency label comes from `APP_CURRENCY` in `.env`.

## Roles

| Role     | Access |
|----------|--------|
| Customer | Storefront, cart, checkout, my orders |
| Merchant | Merchant dashboard only (cannot place customer orders) |

## Troubleshooting

**Database connection failed**  
Check `.env` credentials and that MySQL is running.

**Blank product images**  
Confirm files exist in `uploads/` and the folder is writable. Fallback image: `assets/default-image.svg`.

**Cannot log in as merchant**  
Confirm the user has a Merchant role in `user_roles`. Use the demo merchant account after a fresh import.

**Checkout says insufficient stock**  
Cart quantity exceeds current `products.quantity`. Reduce quantity or restock as merchant.

**Phone/address registration errors on old DB**  
Run `sql/upgrade.sql` to add the newer columns.

## License

Private / project use unless otherwise specified.
