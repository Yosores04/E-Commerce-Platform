# Xerxia - Premium E-Commerce Marketplace

**Elegance in every purchase** ✨

A comprehensive multi-vendor e-commerce marketplace built with Laravel 11.x and Vue.js 3. Xerxia provides a premium shopping experience where curated vendors showcase quality products to discerning customers.

## 🎨 Brand Identity

- **Name**: Xerxia
- **Tagline**: Elegance in every purchase
- **Primary Color**: Wine (#5B2333)
- **Secondary Color**: White Smoke (#F7F4F3)
- **Accent Colors**: Gold, Burgundy, Rose Gold, Champagne
- **Typography**: Inter (body), Playfair Display (headings)
- **Currency**: Philippine Peso (₱)

## 🚀 Project Status

**Current Phase**: Phase 1 - Foundation & MVP Core  
**Sprint**: Sprint 2 - UI Modernization & Xerxia Rebranding  
**Completion**: 95% ✅  
**Branch Strategy**:

- `main` - Production-ready code (currently empty)
- `integration` - Development branch (active development)

## 📋 Features

### Implemented ✅

- **Complete Database Architecture** - 35 tables with proper relationships
- **Authentication System** - Laravel Sanctum API authentication with session support
- **Authorization System** - Role-based access control (4 roles, 30+ permissions)
- **Modern Frontend UI** - Vue.js 3.5 + Tailwind CSS 3.4 with Xerxia design system
- **Design System** - Wine & White Smoke color palette with elegant gradients
- **UI Components** - Headless UI + Hero Icons integration
- **Multi-Vendor Support** - Vendor registration, approval workflow, payout tracking
- **Product Management** - Products, variants, images, categories, tags
- **Order Management** - Orders, payments, refunds, shipping
- **Review System** - Product reviews, vendor reviews, moderation
- **Shopping Features** - Cart, wishlist, addresses, coupons
- **Inventory Tracking** - Stock management, inventory logs
- **System Features** - Notifications, messaging, audit logs, settings
- **Currency System** - Philippine Peso (₱) with proper formatting

### In Progress 🔄

- Authentication Pages (Login/Register) with Xerxia theme
- Product Listing & Detail Pages
- Advanced search and filtering
- Order tracking and management pages

## 🛠️ Tech Stack

### Backend

- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (API + Session)
- **Authorization**: Spatie Laravel Permission
- **Cache/Queue**: Database driver (Redis optional)
- **Storage**: Local (S3 planned)

### Frontend

- **Framework**: Vue.js 3.5.13 + Vite 7.1.9
- **Styling**: Tailwind CSS 3.4.17
- **UI Components**: Headless UI 2.2.0
- **Icons**: Hero Icons 2.2.0
- **State Management**: Pinia 3.0.3
- **HTTP Client**: Axios 1.12.2
- **Routing**: Vue Router 4.5.1

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
   # Backend dependencies
   cd marketplace
   composer install

   # Frontend dependencies
   cd ../frontend-app
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
   # Terminal 1: Start Laravel backend
   cd marketplace
   php artisan serve
   # Backend runs on http://127.0.0.1:8000

   # Terminal 2: Start Vue.js frontend
   cd frontend-app
   npm run dev
   # Frontend runs on http://localhost:5173
   ```

Visit the application: `http://localhost:5173`  
API Backend: `http://127.0.0.1:8000`

## 📚 Documentation

- **[PROJECT_SPEC.md](PROJECT_SPEC.md)** - Complete technical specification (3,400+ lines)
- **[PROGRESS.md](PROGRESS.md)** - Detailed progress tracking
- **[SETUP.md](SETUP.md)** - Comprehensive setup guide
- **[MIGRATION_SUMMARY.md](MIGRATION_SUMMARY.md)** - Database migration details
- **[UI_MODERNIZATION.md](UI_MODERNIZATION.md)** - UI/UX design documentation
- **[WHATS_NEW.md](WHATS_NEW.md)** - Latest features and updates

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

- ✅ Sprint 1: Project Setup & Authentication (100% complete)
- ✅ Sprint 2: UI Modernization & Xerxia Rebranding (95% complete)
- ⏳ Sprint 3: Product Management & Cart System (Next)

### Phase 2: Advanced Features (Weeks 7-12)

- Complete shopping cart & checkout flow
- Product detail pages with reviews
- Vendor dashboard
- Admin panel
- Order management system

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

- Search functionality pending (Meilisearch configuration needed)
- Product images need CDN integration for production
- Email notifications pending SMTP configuration

## 📄 License

All rights reserved.

## 📧 Contact

For questions or support, please contact the project maintainer.

---

**Last Updated**: October 11, 2025  
**Version**: v0.2.0-alpha  
**Status**: Active Development 🚀  
**Brand**: Xerxia - Elegance in every purchase ✨
