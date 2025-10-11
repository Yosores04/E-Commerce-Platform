# E-Commerce Marketplace Platform

A comprehensive multi-vendor e-commerce marketplace built with Laravel 11.x. This platform allows multiple vendors to sell their products while providing customers with a seamless shopping experience.

## 🚀 Project Status

**Current Phase**: Phase 1 - Foundation & MVP Core  
**Sprint**: Sprint 1 - Project Setup & Authentication  
**Completion**: 90% ✅  
**Branch Strategy**: 
- `main` - Production-ready code (currently empty)
- `integration` - Development branch (active development)

## 📋 Features

### Implemented ✅

- **Complete Database Architecture** - 35 tables with proper relationships
- **Authentication System** - Laravel Sanctum API authentication
- **Authorization System** - Role-based access control (4 roles, 30+ permissions)
- **Multi-Vendor Support** - Vendor registration, approval workflow, payout tracking
- **Product Management** - Products, variants, images, categories, tags
- **Order Management** - Orders, payments, refunds, shipping
- **Review System** - Product reviews, vendor reviews, moderation
- **Shopping Features** - Cart, wishlist, addresses, coupons
- **Inventory Tracking** - Stock management, inventory logs
- **System Features** - Notifications, messaging, audit logs, settings

### In Progress 🔄

- API Controllers (Auth, Products, Orders, etc.)
- API Routes with middleware
- Form Request validators
- File storage configuration
- Search functionality (Laravel Scout + Meilisearch)

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Search**: Laravel Scout (planned)
- **Cache/Queue**: Database driver (Redis optional)
- **Storage**: Local (S3 planned)
- **Frontend**: TBD (Inertia + Vue 3 recommended)

## 📦 Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 8.0
- Node.js & NPM (for frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Yosores04/E-Commerce-Platform.git
   cd E-Commerce-Platform
   ```

2. **Switch to integration branch**
   ```bash
   git checkout integration
   ```

3. **Install dependencies**
   ```bash
   cd marketplace
   composer install
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   - Update `.env` with your database credentials:
   ```env
   DB_DATABASE=marketplace
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RolesAndPermissionsSeeder
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

Visit: `http://localhost:8000`

## 📚 Documentation

- **[PROJECT_SPEC.md](PROJECT_SPEC.md)** - Complete technical specification (3,400+ lines)
- **[PROGRESS.md](PROGRESS.md)** - Detailed progress tracking
- **[SETUP.md](SETUP.md)** - Comprehensive setup guide
- **[MIGRATION_SUMMARY.md](MIGRATION_SUMMARY.md)** - Database migration details

## 🗄️ Database Schema

The platform includes 35 database tables organized into:

- **Core System** - migrations, cache, jobs, sessions
- **Authentication** - users, tokens, permissions, roles
- **Vendors** - vendor profiles, payouts
- **Products** - products, variants, images, categories, tags
- **Shopping** - cart, wishlist, addresses, coupons
- **Orders** - orders, order items, payments, refunds
- **Reviews** - product reviews, vendor reviews, votes
- **Communication** - messages, notifications
- **Shipping** - zones, methods
- **System** - inventory logs, settings, audit logs

## 👥 User Roles

1. **Super Admin** - Full system access
2. **Admin** - Management and moderation
3. **Vendor** - Product and order management
4. **Customer** - Shopping and reviews

## 🔐 Permissions

30+ granular permissions including:
- Product management (view, create, edit, delete)
- Order management (view, create, edit, cancel, refund)
- User management (view, create, edit, delete, ban)
- Vendor management (view, approve, suspend, edit)
- Category management
- Review moderation
- Reports and analytics
- System settings

## 🚧 Development Roadmap

### Phase 1: Foundation & MVP Core (Weeks 1-6)
- ✅ Sprint 1: Project Setup & Authentication (90% complete)
- ⏳ Sprint 2: Product Management API (Next)
- ⏳ Sprint 3: Order & Payment System

### Phase 2: Advanced Features (Weeks 7-12)
- Shopping cart & checkout
- Review system
- Vendor dashboard
- Admin panel

### Phase 3: Enhancements (Weeks 13-18)
- Search & filters
- Notifications
- Analytics
- Performance optimization

### Phase 4: Polish & Launch (Weeks 19-22)
- Testing
- Documentation
- Deployment
- Monitoring

## 🤝 Contributing

This is a private project. For collaboration:

1. Always work on the `integration` branch
2. Create feature branches from `integration`
3. Submit pull requests to merge back to `integration`
4. Only merge to `main` after thorough testing

## 📝 Git Workflow

```bash
# Start new feature
git checkout integration
git pull origin integration
git checkout -b feature/your-feature-name

# Commit your changes
git add .
git commit -m "Description of changes"

# Push to remote
git push origin feature/your-feature-name

# Create pull request to integration branch (not main!)
```

## 🐛 Known Issues

- Redis PHP extension not installed (using database driver as fallback)
- Search functionality pending (Meilisearch configuration needed)

## 📄 License

All rights reserved.

## 📧 Contact

For questions or support, please contact the project maintainer.

---

**Last Updated**: January 11, 2025  
**Version**: v0.1.0-alpha  
**Status**: Active Development 🚀
