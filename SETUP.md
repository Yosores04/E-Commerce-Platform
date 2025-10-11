# E-Commerce Marketplace - Setup Guide

## Prerequisites

✅ **Already Installed/Configured:**

- PHP 8.1+
- Composer
- Laravel 11.x project created
- All Laravel packages installed

⏳ **Need to Start/Configure:**

- MySQL 8.0 server
- Redis server (optional for now)
- Meilisearch (optional - for search)

---

## Quick Start

### 1. Start MySQL Server

**Windows (XAMPP):**

```bash
# Start MySQL from XAMPP Control Panel
# Or via command line:
C:\xampp\mysql\bin\mysqld.exe
```

**Windows (MySQL Service):**

```powershell
net start MySQL80
```

**Create Database:**

```sql
mysql -u root -p
CREATE DATABASE marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2. Run Migrations

```bash
cd "c:\Users\Josh\Documents\Clients\Client 02\E-Commerce-Platform\marketplace"

# Run all migrations (creates 31 tables)
php artisan migrate

# Seed roles and permissions
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### 3. Create Test Users

You can create test users via Tinker:

```bash
php artisan tinker
```

```php
// Create Super Admin
$admin = \App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'admin@marketplace.com',
    'password' => bcrypt('password123')
]);
$admin->assignRole('super-admin');

// Create Vendor
$vendor = \App\Models\User::create([
    'name' => 'Test Vendor',
    'email' => 'vendor@example.com',
    'password' => bcrypt('password123')
]);
$vendor->assignRole('vendor');

// Create vendor profile
\App\Models\Vendor::create([
    'user_id' => $vendor->id,
    'shop_name' => 'Test Shop',
    'shop_slug' => 'test-shop',
    'shop_description' => 'A test shop',
    'business_name' => 'Test Business LLC',
    'business_email' => 'vendor@example.com',
    'business_phone' => '1234567890',
    'status' => 'approved',
    'commission_rate' => 10.00,
    'country' => 'US',
]);

// Create Customer
$customer = \App\Models\User::create([
    'name' => 'Test Customer',
    'email' => 'customer@example.com',
    'password' => bcrypt('password123')
]);
$customer->assignRole('customer');

exit
```

### 4. Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Testing the Setup

### Check Database Tables

```bash
php artisan tinker
```

```php
// Check if tables exist
\Illuminate\Support\Facades\Schema::hasTable('users');
\Illuminate\Support\Facades\Schema::hasTable('vendors');
\Illuminate\Support\Facades\Schema::hasTable('products');

// Check roles
\Spatie\Permission\Models\Role::all();

// Check permissions
\Spatie\Permission\Models\Permission::all();

exit
```

### Test API Authentication (Once Controllers are built)

```bash
# Register
POST http://localhost:8000/api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Login
POST http://localhost:8000/api/login
{
    "email": "john@example.com",
    "password": "password123"
}
```

---

## Directory Structure

```
marketplace/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/     [Next: Create controllers here]
│   │   ├── Middleware/
│   │   └── Requests/            [Next: Create validation here]
│   ├── Models/                  [✅ Core models created]
│   └── Services/                [Next: Business logic services]
├── database/
│   ├── migrations/              [✅ 31 migrations ready]
│   └── seeders/                 [✅ Roles seeder ready]
├── routes/
│   ├── api.php                  [Next: Define API routes]
│   └── web.php
└── storage/
    └── app/public/              [Next: Configure for images]
```

---

## What's Next?

### Phase 1 Continuation:

1. **Create API Controllers** (Week 2)

   - [ ] AuthController (register, login, logout)
   - [ ] ProductController (CRUD)
   - [ ] CategoryController (CRUD)
   - [ ] OrderController (checkout, view orders)

2. **Create Form Request Validators** (Week 2)

   - [ ] RegisterRequest
   - [ ] LoginRequest
   - [ ] ProductRequest
   - [ ] OrderRequest

3. **Define API Routes** (Week 2)

   - [ ] Public routes (products, categories)
   - [ ] Protected routes (cart, orders)
   - [ ] Admin routes (vendor approval)

4. **Configure File Storage** (Week 2)

   - [ ] Set up image uploads
   - [ ] Configure thumbnails
   - [ ] Implement image optimization

5. **Implement Core Features** (Week 3-4)

   - [ ] Product catalog with pagination
   - [ ] Shopping cart functionality
   - [ ] Checkout process
   - [ ] Order management

6. **Add Search** (Week 4)
   - [ ] Install Meilisearch
   - [ ] Configure Scout driver
   - [ ] Implement search API

---

## Common Commands

```bash
# Development
php artisan serve              # Start dev server
php artisan tinker            # Laravel REPL

# Database
php artisan migrate           # Run migrations
php artisan migrate:fresh     # Drop all tables and re-migrate
php artisan db:seed          # Run seeders
php artisan migrate:fresh --seed  # Fresh install with data

# Cache
php artisan cache:clear      # Clear application cache
php artisan config:clear     # Clear config cache
php artisan route:clear      # Clear route cache

# Generation
php artisan make:controller  # Create controller
php artisan make:model       # Create model
php artisan make:migration   # Create migration
php artisan make:seeder      # Create seeder
php artisan make:request     # Create form request

# Testing
php artisan test             # Run tests
```

---

## Environment Variables

Key variables in `.env`:

```env
APP_NAME="E-Commerce Marketplace"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Mail (for later)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

# AWS S3 (for later)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
```

---

## Troubleshooting

### Database Connection Refused

- Ensure MySQL is running
- Check DB credentials in `.env`
- Verify database exists

### Permission Errors

- Run: `composer dump-autoload`
- Clear cache: `php artisan cache:clear`

### Class Not Found

- Run: `composer dump-autoload`
- Check namespace in model files

---

## Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Sanctum**: https://laravel.com/docs/sanctum
- **Spatie Permission**: https://spatie.be/docs/laravel-permission
- **Laravel Scout**: https://laravel.com/docs/scout
- **Project Spec**: See `PROJECT_SPEC.md`
- **Progress**: See `PROGRESS.md`

---

**Created**: January 11, 2025
**Status**: Ready for Phase 1 Sprint 2
