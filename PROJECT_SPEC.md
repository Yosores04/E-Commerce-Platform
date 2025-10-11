# Multi-Vendor E-Commerce Marketplace - Project Specification

## 1. PROJECT OVERVIEW

### System Description

This is a **multi-vendor marketplace platform** where multiple sellers can register, list products, and manage their storefronts, while buyers can browse, purchase from different vendors in a single order, and leave reviews. Administrators oversee the entire platform, approve vendors, manage categories, and handle disputes.

### End-User Perspectives

#### **Buyer Perspective**

- Browse products from multiple vendors in one unified marketplace
- Search, filter, and sort products by category, price, rating, vendor
- Add products from multiple vendors to a single cart
- Checkout and pay for all items at once (platform handles vendor payouts)
- Track orders and view order history
- Leave reviews and ratings for products and vendors
- Manage profile, saved addresses, payment methods
- Receive notifications about order status, promotions, and new products from followed vendors
- Contact vendors with questions
- Request returns/refunds

#### **Vendor Perspective**

- Register and submit business information for approval
- Create and manage their storefront/shop profile
- Add, edit, and delete products with variants (size, color, etc.)
- Manage inventory levels and stock alerts
- View and fulfill orders for their products
- Set product pricing and run promotions/discounts
- Access sales analytics and dashboard (revenue, best sellers, order trends)
- Respond to customer inquiries and reviews
- Manage shipping settings and options
- Receive notifications for new orders, low inventory, reviews
- Request payouts and view payment history
- Upload product images and manage media

#### **Admin Perspective**

- Approve or reject vendor registration applications
- Manage all users (buyers, vendors) - suspend/activate accounts
- Create and manage product categories and attributes
- Oversee all orders and transactions across the platform
- Handle disputes between buyers and vendors
- Manage platform-wide settings (commissions, fees, payment gateways)
- View comprehensive analytics (GMV, active users, vendor performance)
- Manage content (banners, featured products, homepage content)
- Configure shipping zones and methods
- Send platform-wide announcements
- Generate financial and operational reports
- Moderate reviews and product listings
- Manage refunds and cancellations

---

## 2. USER ROLES & PERMISSIONS

### Role Definitions

#### **Guest (Not Logged In)**

**Can:**

- Browse products and categories
- Search products
- View product details and reviews
- View vendor profiles
- Add products to cart (session-based)

**Cannot:**

- Checkout/place orders
- Leave reviews
- Contact vendors
- Save items to wishlist
- View order history

#### **Buyer (Authenticated Customer)**

**Can:**

- All Guest permissions
- Complete checkout and place orders
- View order history and track orders
- Leave reviews and ratings
- Manage profile and addresses
- Save items to wishlist
- Follow vendors for updates
- Contact vendors
- Request returns/refunds
- Save payment methods

**Cannot:**

- Access vendor dashboard
- List products for sale
- Access admin functions

#### **Vendor (Authenticated Seller)**

**Can:**

- All Buyer permissions (can also purchase)
- Manage their shop profile
- CRUD operations on their products
- Manage inventory for their products
- View and fulfill orders containing their products
- Access vendor dashboard and analytics (own data only)
- Respond to customer questions
- Reply to reviews on their products
- Manage their product categories/tags
- Set up promotions/discounts for their products
- Configure shipping options
- Request payouts
- Upload and manage product media

**Cannot:**

- Access other vendors' data
- Approve/manage users
- Create platform categories
- Access admin dashboard
- Modify platform settings
- View other vendors' sales data

#### **Admin (Platform Administrator)**

**Can:**

- Full access to all platform features
- Approve/reject vendor applications
- Manage all users (CRUD, suspend, activate)
- Manage platform categories and attributes
- View all orders and transactions
- Handle disputes and refunds
- Configure platform settings
- Access all analytics and reports
- Moderate content (products, reviews)
- Manage payment and shipping configurations
- Send platform notifications
- Override vendor/buyer actions when necessary

**Cannot:**

- (No restrictions - full platform access)

---

## 3. KEY FEATURES / MODULES

### Module 1: User Management & Authentication

#### Sub-Features:

- User registration (email/social auth)
- Email verification
- Login/logout with Remember Me
- Password reset flow
- Two-factor authentication (2FA)
- Profile management (avatar, personal info, preferences)
- Address book (multiple shipping addresses)
- Saved payment methods (tokenized)
- Account deactivation

#### Edge Cases:

- Email already exists
- Expired verification links
- Concurrent login sessions
- Social auth account merging
- Password reset token expiration
- Inactive account login attempts

#### Security Concerns:

- Rate limiting on login attempts
- CSRF protection
- XSS prevention in user inputs
- Password strength requirements
- Secure password hashing (bcrypt)
- Session hijacking prevention
- API authentication (Sanctum/Passport)

#### Performance:

- Cache user sessions
- Optimize profile queries with eager loading
- Index email and username fields

---

### Module 2: Vendor Management & Onboarding

#### Sub-Features:

- Vendor registration/application form
- Business verification (documents upload)
- Admin approval workflow
- Vendor profile/storefront customization
- Business information management
- Bank account/payout setup
- Vendor dashboard with key metrics
- Subscription tiers (basic, premium, enterprise)
- Vendor verification badges
- Commission rate configuration per vendor

#### Edge Cases:

- Incomplete application submissions
- Rejected applications - resubmission
- Vendor account suspension - impact on orders
- Multiple stores per vendor
- Vendor changing business structure
- Duplicate business registrations

#### Security Concerns:

- Document verification authenticity
- Prevent fraudulent vendor accounts
- Secure storage of sensitive business data
- PII encryption for vendor documents
- Access control for vendor data

#### Performance:

- Async document processing
- Cache vendor profile data
- Index vendor status for admin queries
- Background jobs for verification

---

### Module 3: Product Management

#### Sub-Features:

- Product CRUD (title, description, price, images)
- Product variants (size, color, material, etc.)
- SKU management
- Category and subcategory assignment
- Tags and attributes
- Product status (draft, active, inactive, out of stock)
- Bulk product upload (CSV/Excel)
- Product images (multiple images, thumbnail, gallery)
- Product SEO (meta title, description, slug)
- Related products and upsells
- Product duplication/cloning
- Product scheduling (publish date)

#### Edge Cases:

- Deleting products with active orders
- Product variants with different pricing/inventory
- Image upload failures
- Invalid CSV data on bulk upload
- Category changes affecting filters
- Product slug conflicts
- Orphaned images after product deletion

#### Security Concerns:

- Image upload validation (type, size, malware)
- SQL injection in search/filters
- Authorization checks (vendors can only edit own products)
- XSS in product descriptions
- Price manipulation prevention

#### Performance:

- Image optimization and CDN storage
- Database indexing on category, status, price
- Full-text search optimization (Laravel Scout + Algolia/Meilisearch)
- Cache product listings
- Lazy loading product images
- Pagination for large product sets
- Queue bulk operations

---

### Module 4: Category & Attribute Management

#### Sub-Features:

- Category hierarchy (parent-child relationships)
- Category images and icons
- Category-specific attributes
- Dynamic filters based on category
- Category ordering/sorting
- Category status (active/inactive)
- SEO-friendly category URLs

#### Edge Cases:

- Circular category relationships
- Deleting categories with products
- Moving products between categories
- Multiple category assignments
- Category depth limits

#### Security Concerns:

- Admin-only access to category management
- Validate category hierarchy integrity

#### Performance:

- Cache category trees
- Materialized path or nested set for hierarchy
- Index parent_id and slug

---

### Module 5: Shopping Cart & Wishlist

#### Sub-Features:

- Add/remove/update cart items
- Cart persistence (logged in users)
- Session-based cart (guests)
- Cart summary with totals
- Multi-vendor cart handling
- Apply coupon codes
- Save for later
- Wishlist management
- Move items between cart and wishlist
- Cart abandonment notifications

#### Edge Cases:

- Out of stock items in cart
- Price changes while item in cart
- Vendor deactivation with items in cart
- Cart merging on login (guest → user)
- Expired coupon codes
- Quantity exceeds available stock

#### Security Concerns:

- Cart tampering (price manipulation)
- Server-side price validation
- Session security for guest carts

#### Performance:

- Cache cart data
- Optimize cart queries with eager loading
- Real-time inventory checks
- Debounce quantity updates

---

### Module 6: Order Management & Checkout

#### Sub-Features:

- Checkout flow (shipping, payment, review)
- Address selection/creation at checkout
- Shipping method selection
- Order splitting by vendor
- Order summary and review
- Order placement
- Order status tracking (pending, processing, shipped, delivered, cancelled)
- Order history for buyers
- Vendor order dashboard
- Order fulfillment workflow
- Shipping label generation
- Order cancellation (buyer/vendor/admin)
- Order notes and communication
- Invoice generation

#### Edge Cases:

- Payment failure after order creation
- Stock depletion during checkout
- Order cancellation policies
- Partial order fulfillment
- Split shipments
- Address validation failures
- Concurrent purchases of limited stock
- Order modification requests

#### Security Concerns:

- Payment gateway security (PCI compliance)
- Order data authorization
- Prevent order enumeration attacks
- Secure order status updates

#### Performance:

- Transaction management for order creation
- Queue order confirmation emails
- Optimize order queries with filters
- Index order status and dates
- Cache order summaries

---

### Module 7: Payment Processing

#### Sub-Features:

- Multiple payment gateway support (Stripe, PayPal, etc.)
- Credit/debit card payments
- Wallet payments
- Bank transfers
- Payment method management
- Payment status tracking
- Refund processing
- Split payments to vendors
- Platform commission calculation
- Vendor payout management
- Payment history and receipts
- Failed payment handling
- Payment retry mechanism

#### Edge Cases:

- Gateway timeout errors
- Partial refunds
- Chargeback handling
- Currency conversion
- Payment verification delays
- Duplicate payment prevention
- Refund to original payment method

#### Security Concerns:

- PCI DSS compliance
- Tokenized payment storage
- HTTPS enforcement
- Payment webhook verification
- Fraud detection
- 3D Secure authentication

#### Performance:

- Async payment processing
- Webhook queue handling
- Payment status caching
- Batch payout processing

---

### Module 8: Shipping & Logistics

#### Sub-Features:

- Shipping zone configuration
- Shipping method setup (standard, express, etc.)
- Shipping rate calculation
- Vendor-specific shipping settings
- Real-time carrier integration (optional)
- Tracking number management
- Shipping label generation
- Multi-package shipments
- Free shipping rules
- International shipping

#### Edge Cases:

- Unavailable shipping zones
- Weight/dimension limit violations
- Shipping method changes after order
- Lost/damaged package handling
- Undeliverable addresses
- Cross-border customs

#### Security Concerns:

- Address validation
- Carrier API authentication

#### Performance:

- Cache shipping rates
- Background shipping label generation
- Optimize shipping calculations

---

### Module 9: Inventory Management

#### Sub-Features:

- Real-time stock tracking
- Low stock alerts
- Stock adjustment (add/remove)
- Inventory history log
- Multi-location inventory (optional)
- Reserved inventory (cart/pending orders)
- Bulk inventory updates
- Stock reports
- Automatic stock reduction on order
- Backorder management

#### Edge Cases:

- Negative stock scenarios
- Overselling prevention
- Reserved stock expiration
- Inventory sync failures
- Concurrent stock updates
- Stock allocation across warehouses

#### Security Concerns:

- Authorization for inventory changes
- Audit trail for stock adjustments

#### Performance:

- Database locking for stock updates
- Queue stock notifications
- Optimize inventory queries
- Index product_id and quantity

---

### Module 10: Reviews & Ratings

#### Sub-Features:

- Product reviews and ratings (1-5 stars)
- Vendor ratings
- Review text and images
- Verified purchase badge
- Review moderation (admin)
- Review replies (vendor)
- Helpful/not helpful voting
- Review sorting and filtering
- Review summary statistics
- Email review requests after delivery

#### Edge Cases:

- Reviews for cancelled orders
- Multiple reviews per product per user
- Review editing/deletion
- Inappropriate content flagging
- Fake review detection
- Review for product variants

#### Security Concerns:

- Spam prevention
- Review bombing protection
- XSS in review content
- Authenticated users only
- Prevent vendor self-reviews

#### Performance:

- Cache review aggregates
- Index product_id and user_id
- Paginate reviews
- Background review notifications

---

### Module 11: Search & Filtering

#### Sub-Features:

- Full-text product search
- Autocomplete suggestions
- Search result ranking
- Advanced filters (price range, category, brand, rating, etc.)
- Faceted search
- Search history (personalized)
- Popular searches
- Vendor search
- Search analytics

#### Edge Cases:

- Empty search results
- Special characters in queries
- Misspellings and typos
- Very broad queries
- Search performance degradation

#### Security Concerns:

- SQL injection prevention
- XSS in search terms
- Rate limiting search API

#### Performance:

- Search engine integration (Algolia, Elasticsearch, Meilisearch)
- Index optimization
- Cache search results
- Async indexing
- Query result pagination

---

### Module 12: Promotions & Discounts

#### Sub-Features:

- Coupon code creation (% or fixed amount)
- Vendor-specific coupons
- Platform-wide coupons
- Product-specific discounts
- Bulk/tier pricing
- Flash sales
- Bundle deals
- First-time buyer discounts
- Loyalty points system
- Referral programs
- Minimum purchase requirements
- Usage limits (per user, total uses)

#### Edge Cases:

- Expired coupons
- Minimum purchase not met
- Stacking multiple coupons
- Coupon conflicts
- Invalid coupon codes
- Coupon fraud prevention

#### Security Concerns:

- Coupon code guessing prevention
- Usage limit enforcement
- Authorization checks

#### Performance:

- Index coupon codes
- Cache active promotions
- Validate coupons server-side

---

### Module 13: Notifications & Messaging

#### Sub-Features:

- Email notifications (order confirmation, shipping, etc.)
- SMS notifications (optional)
- In-app notifications
- Push notifications (mobile)
- Notification preferences
- Buyer-vendor messaging
- Admin announcements
- Newsletter subscriptions
- Order status updates
- Promotional emails
- Abandoned cart reminders

#### Edge Cases:

- Email delivery failures
- Notification overload
- Unsubscribe handling
- Notification timing (timezone)
- Template rendering errors

#### Security Concerns:

- Prevent notification spam
- Secure message content
- Email spoofing prevention
- Rate limiting

#### Performance:

- Queue all notifications
- Batch email sending
- Use notification service (SNS, Firebase)
- Optimize notification queries

---

### Module 14: Analytics & Reporting

#### Sub-Features:

- Vendor dashboard (sales, orders, revenue, top products)
- Admin dashboard (GMV, active users, vendor metrics)
- Sales reports (daily, weekly, monthly)
- Product performance reports
- Customer insights
- Financial reports
- Inventory reports
- Export to CSV/Excel
- Custom date range filtering
- Real-time metrics

#### Edge Cases:

- Large dataset exports
- Report generation timeout
- Data inconsistencies
- Missing data points

#### Security Concerns:

- Role-based report access
- Sensitive data protection
- Export file security

#### Performance:

- Pre-aggregate metrics
- Cache dashboard data
- Background report generation
- Optimize analytical queries
- Use read replicas for reporting

---

### Module 15: Admin Panel

#### Sub-Features:

- User management (list, edit, suspend)
- Vendor approval workflow
- Product moderation
- Order management (view all, cancel, refund)
- Category management
- Content management (homepage, banners)
- Settings configuration
- Commission/fee management
- Dispute resolution
- System logs and audit trails
- Platform announcements
- Role and permission management

#### Edge Cases:

- Bulk operations on users/products
- Cascading deletions
- Permission conflicts
- Audit log storage limits

#### Security Concerns:

- Super admin access
- Activity logging
- Sensitive operation confirmation
- IP whitelisting (optional)

#### Performance:

- Optimize admin queries
- Paginate large lists
- Cache settings
- Background bulk operations

---

### Module 16: Mobile API

#### Sub-Features:

- RESTful API for mobile apps
- API authentication (tokens)
- API versioning
- Response pagination
- Error handling
- API documentation
- Rate limiting
- Webhook support

#### Security Concerns:

- API token security
- OAuth 2.0 implementation
- Request validation
- CORS configuration

#### Performance:

- API response caching
- Optimize API queries
- Compression (gzip)

---

## 4. DATABASE SCHEMA SUGGESTION

### Core Tables

#### **users**

```
id (BIGINT, PK, AUTO_INCREMENT)
uuid (UUID, UNIQUE, INDEXED)
name (VARCHAR 255)
email (VARCHAR 255, UNIQUE, INDEXED)
email_verified_at (TIMESTAMP, NULLABLE)
password (VARCHAR 255)
phone (VARCHAR 50, NULLABLE)
avatar (VARCHAR 255, NULLABLE)
role (ENUM: 'admin', 'vendor', 'buyer', DEFAULT: 'buyer', INDEXED)
status (ENUM: 'active', 'inactive', 'suspended', DEFAULT: 'active', INDEXED)
two_factor_secret (TEXT, NULLABLE, ENCRYPTED)
two_factor_enabled (BOOLEAN, DEFAULT: false)
remember_token (VARCHAR 100, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
deleted_at (TIMESTAMP, NULLABLE) -- soft deletes

INDEXES:
- email, status
- role, status
- created_at
```

#### **vendors**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, UNIQUE, INDEXED)
shop_name (VARCHAR 255, UNIQUE, INDEXED)
shop_slug (VARCHAR 255, UNIQUE, INDEXED)
shop_description (TEXT, NULLABLE)
shop_logo (VARCHAR 255, NULLABLE)
shop_banner (VARCHAR 255, NULLABLE)
business_name (VARCHAR 255)
business_email (VARCHAR 255)
business_phone (VARCHAR 50)
tax_id (VARCHAR 100, NULLABLE)
address_line1 (VARCHAR 255)
address_line2 (VARCHAR 255, NULLABLE)
city (VARCHAR 100)
state (VARCHAR 100)
country (VARCHAR 100)
postal_code (VARCHAR 20)
status (ENUM: 'pending', 'approved', 'rejected', 'suspended', DEFAULT: 'pending', INDEXED)
approval_date (TIMESTAMP, NULLABLE)
commission_rate (DECIMAL 5,2, DEFAULT: 10.00) -- platform commission %
bank_account_name (VARCHAR 255, NULLABLE, ENCRYPTED)
bank_account_number (VARCHAR 100, NULLABLE, ENCRYPTED)
bank_name (VARCHAR 255, NULLABLE)
bank_routing_number (VARCHAR 50, NULLABLE, ENCRYPTED)
payout_schedule (ENUM: 'weekly', 'biweekly', 'monthly', DEFAULT: 'weekly')
documents (JSON, NULLABLE) -- verification documents
metadata (JSON, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
deleted_at (TIMESTAMP, NULLABLE)

INDEXES:
- user_id
- shop_slug
- status
- created_at
```

#### **categories**

```
id (BIGINT, PK, AUTO_INCREMENT)
parent_id (BIGINT, FK → categories.id, NULLABLE, INDEXED)
name (VARCHAR 255, INDEXED)
slug (VARCHAR 255, UNIQUE, INDEXED)
description (TEXT, NULLABLE)
image (VARCHAR 255, NULLABLE)
icon (VARCHAR 255, NULLABLE)
order (INT, DEFAULT: 0)
status (ENUM: 'active', 'inactive', DEFAULT: 'active', INDEXED)
meta_title (VARCHAR 255, NULLABLE)
meta_description (TEXT, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
deleted_at (TIMESTAMP, NULLABLE)

INDEXES:
- parent_id, status
- slug
- order
```

#### **products**

```
id (BIGINT, PK, AUTO_INCREMENT)
vendor_id (BIGINT, FK → vendors.id, INDEXED)
category_id (BIGINT, FK → categories.id, INDEXED)
name (VARCHAR 255, INDEXED)
slug (VARCHAR 255, UNIQUE, INDEXED)
description (TEXT)
short_description (VARCHAR 500, NULLABLE)
sku (VARCHAR 100, UNIQUE, INDEXED)
price (DECIMAL 10,2)
compare_at_price (DECIMAL 10,2, NULLABLE) -- original price for discounts
cost (DECIMAL 10,2, NULLABLE) -- cost price for vendor
quantity (INT, DEFAULT: 0, INDEXED)
low_stock_threshold (INT, DEFAULT: 5)
weight (DECIMAL 8,2, NULLABLE) -- in grams
length (DECIMAL 8,2, NULLABLE) -- in cm
width (DECIMAL 8,2, NULLABLE)
height (DECIMAL 8,2, NULLABLE)
status (ENUM: 'draft', 'active', 'inactive', 'out_of_stock', DEFAULT: 'draft', INDEXED)
is_featured (BOOLEAN, DEFAULT: false, INDEXED)
published_at (TIMESTAMP, NULLABLE)
meta_title (VARCHAR 255, NULLABLE)
meta_description (TEXT, NULLABLE)
views (INT, DEFAULT: 0)
rating_avg (DECIMAL 3,2, DEFAULT: 0.00, INDEXED)
rating_count (INT, DEFAULT: 0)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
deleted_at (TIMESTAMP, NULLABLE)

INDEXES:
- vendor_id, status
- category_id, status
- slug
- sku
- price
- rating_avg
- created_at
- is_featured, status

FULLTEXT INDEX on (name, description)
```

#### **product_images**

```
id (BIGINT, PK, AUTO_INCREMENT)
product_id (BIGINT, FK → products.id, INDEXED)
image_url (VARCHAR 255)
thumbnail_url (VARCHAR 255, NULLABLE)
is_primary (BOOLEAN, DEFAULT: false)
order (INT, DEFAULT: 0)
alt_text (VARCHAR 255, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- product_id, order
- product_id, is_primary
```

#### **product_variants**

```
id (BIGINT, PK, AUTO_INCREMENT)
product_id (BIGINT, FK → products.id, INDEXED)
sku (VARCHAR 100, UNIQUE, INDEXED)
name (VARCHAR 255) -- e.g., "Red - Large"
attributes (JSON) -- {"color": "Red", "size": "Large"}
price (DECIMAL 10,2)
compare_at_price (DECIMAL 10,2, NULLABLE)
quantity (INT, DEFAULT: 0)
image_url (VARCHAR 255, NULLABLE)
status (ENUM: 'active', 'inactive', DEFAULT: 'active')
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- product_id, status
- sku
```

#### **product_categories** (Many-to-Many)

```
product_id (BIGINT, FK → products.id)
category_id (BIGINT, FK → categories.id)

PRIMARY KEY (product_id, category_id)
INDEXES:
- category_id
```

#### **product_tags**

```
id (BIGINT, PK, AUTO_INCREMENT)
name (VARCHAR 100, UNIQUE, INDEXED)
slug (VARCHAR 100, UNIQUE, INDEXED)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### **product_tag_pivot** (Many-to-Many)

```
product_id (BIGINT, FK → products.id)
tag_id (BIGINT, FK → product_tags.id)

PRIMARY KEY (product_id, tag_id)
INDEXES:
- tag_id
```

#### **carts**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, NULLABLE, INDEXED)
session_id (VARCHAR 255, NULLABLE, INDEXED) -- for guest carts
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- user_id
- session_id
```

#### **cart_items**

```
id (BIGINT, PK, AUTO_INCREMENT)
cart_id (BIGINT, FK → carts.id, INDEXED)
product_id (BIGINT, FK → products.id, INDEXED)
variant_id (BIGINT, FK → product_variants.id, NULLABLE, INDEXED)
quantity (INT, DEFAULT: 1)
price (DECIMAL 10,2) -- snapshot of price at time of adding
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- cart_id
- product_id
```

#### **wishlists**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, INDEXED)
product_id (BIGINT, FK → products.id, INDEXED)
created_at (TIMESTAMP)

UNIQUE INDEX (user_id, product_id)
```

#### **addresses**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, INDEXED)
type (ENUM: 'shipping', 'billing')
is_default (BOOLEAN, DEFAULT: false)
first_name (VARCHAR 100)
last_name (VARCHAR 100)
phone (VARCHAR 50)
address_line1 (VARCHAR 255)
address_line2 (VARCHAR 255, NULLABLE)
city (VARCHAR 100)
state (VARCHAR 100)
country (VARCHAR 100)
postal_code (VARCHAR 20)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- user_id, is_default
```

#### **orders**

```
id (BIGINT, PK, AUTO_INCREMENT)
order_number (VARCHAR 50, UNIQUE, INDEXED)
user_id (BIGINT, FK → users.id, INDEXED)
status (ENUM: 'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', DEFAULT: 'pending', INDEXED)
payment_status (ENUM: 'pending', 'paid', 'failed', 'refunded', DEFAULT: 'pending', INDEXED)
subtotal (DECIMAL 10,2)
tax (DECIMAL 10,2)
shipping_cost (DECIMAL 10,2)
discount (DECIMAL 10,2, DEFAULT: 0.00)
total (DECIMAL 10,2)
currency (VARCHAR 3, DEFAULT: 'USD')
coupon_code (VARCHAR 100, NULLABLE)
notes (TEXT, NULLABLE)
shipping_address_id (BIGINT, FK → addresses.id)
billing_address_id (BIGINT, FK → addresses.id)
shipping_method (VARCHAR 100, NULLABLE)
tracking_number (VARCHAR 255, NULLABLE)
shipped_at (TIMESTAMP, NULLABLE)
delivered_at (TIMESTAMP, NULLABLE)
cancelled_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- order_number
- user_id, status
- payment_status
- created_at
- status, created_at
```

#### **order_items**

```
id (BIGINT, PK, AUTO_INCREMENT)
order_id (BIGINT, FK → orders.id, INDEXED)
vendor_id (BIGINT, FK → vendors.id, INDEXED)
product_id (BIGINT, FK → products.id, INDEXED)
variant_id (BIGINT, FK → product_variants.id, NULLABLE)
product_name (VARCHAR 255)
product_sku (VARCHAR 100)
variant_name (VARCHAR 255, NULLABLE)
quantity (INT)
price (DECIMAL 10,2) -- unit price at time of purchase
tax (DECIMAL 10,2)
subtotal (DECIMAL 10,2)
total (DECIMAL 10,2)
commission_rate (DECIMAL 5,2) -- snapshot of vendor commission
commission_amount (DECIMAL 10,2)
vendor_earnings (DECIMAL 10,2)
status (ENUM: 'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', DEFAULT: 'pending', INDEXED)
tracking_number (VARCHAR 255, NULLABLE)
shipped_at (TIMESTAMP, NULLABLE)
delivered_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- order_id
- vendor_id, status
- product_id
- status
```

#### **payments**

```
id (BIGINT, PK, AUTO_INCREMENT)
order_id (BIGINT, FK → orders.id, INDEXED)
transaction_id (VARCHAR 255, UNIQUE, INDEXED)
payment_gateway (VARCHAR 50) -- stripe, paypal, etc.
payment_method (VARCHAR 50) -- credit_card, debit_card, paypal, etc.
amount (DECIMAL 10,2)
currency (VARCHAR 3, DEFAULT: 'USD')
status (ENUM: 'pending', 'completed', 'failed', 'refunded', DEFAULT: 'pending', INDEXED)
gateway_response (JSON, NULLABLE)
paid_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- order_id
- transaction_id
- status
```

#### **refunds**

```
id (BIGINT, PK, AUTO_INCREMENT)
order_id (BIGINT, FK → orders.id, INDEXED)
order_item_id (BIGINT, FK → order_items.id, NULLABLE, INDEXED)
payment_id (BIGINT, FK → payments.id, NULLABLE, INDEXED)
amount (DECIMAL 10,2)
reason (TEXT)
status (ENUM: 'pending', 'approved', 'rejected', 'completed', DEFAULT: 'pending', INDEXED)
processed_by (BIGINT, FK → users.id, NULLABLE) -- admin who processed
processed_at (TIMESTAMP, NULLABLE)
notes (TEXT, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- order_id
- status
```

#### **reviews**

```
id (BIGINT, PK, AUTO_INCREMENT)
product_id (BIGINT, FK → products.id, INDEXED)
user_id (BIGINT, FK → users.id, INDEXED)
order_item_id (BIGINT, FK → order_items.id, NULLABLE, INDEXED)
rating (TINYINT, 1-5, INDEXED)
title (VARCHAR 255, NULLABLE)
comment (TEXT, NULLABLE)
images (JSON, NULLABLE)
is_verified_purchase (BOOLEAN, DEFAULT: false, INDEXED)
status (ENUM: 'pending', 'approved', 'rejected', DEFAULT: 'pending', INDEXED)
helpful_count (INT, DEFAULT: 0)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- product_id, status
- user_id
- rating
- is_verified_purchase

UNIQUE INDEX (user_id, product_id, order_item_id)
```

#### **review_replies**

```
id (BIGINT, PK, AUTO_INCREMENT)
review_id (BIGINT, FK → reviews.id, INDEXED)
user_id (BIGINT, FK → users.id) -- vendor or admin
comment (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- review_id
```

#### **review_votes** (helpful/not helpful)

```
id (BIGINT, PK, AUTO_INCREMENT)
review_id (BIGINT, FK → reviews.id, INDEXED)
user_id (BIGINT, FK → users.id, INDEXED)
is_helpful (BOOLEAN)
created_at (TIMESTAMP)

UNIQUE INDEX (review_id, user_id)
```

#### **vendor_reviews**

```
id (BIGINT, PK, AUTO_INCREMENT)
vendor_id (BIGINT, FK → vendors.id, INDEXED)
user_id (BIGINT, FK → users.id, INDEXED)
order_id (BIGINT, FK → orders.id, NULLABLE, INDEXED)
rating (TINYINT, 1-5, INDEXED)
comment (TEXT, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- vendor_id
- rating

UNIQUE INDEX (user_id, vendor_id, order_id)
```

#### **coupons**

```
id (BIGINT, PK, AUTO_INCREMENT)
code (VARCHAR 50, UNIQUE, INDEXED)
vendor_id (BIGINT, FK → vendors.id, NULLABLE, INDEXED) -- null = platform-wide
type (ENUM: 'percentage', 'fixed_amount', DEFAULT: 'percentage')
value (DECIMAL 10,2) -- percentage or fixed amount
min_purchase (DECIMAL 10,2, DEFAULT: 0.00)
max_discount (DECIMAL 10,2, NULLABLE)
usage_limit (INT, NULLABLE) -- total usage limit
usage_per_user (INT, DEFAULT: 1)
used_count (INT, DEFAULT: 0)
start_date (TIMESTAMP)
end_date (TIMESTAMP)
status (ENUM: 'active', 'inactive', 'expired', DEFAULT: 'active', INDEXED)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- code
- vendor_id, status
- start_date, end_date
```

#### **coupon_usage**

```
id (BIGINT, PK, AUTO_INCREMENT)
coupon_id (BIGINT, FK → coupons.id, INDEXED)
user_id (BIGINT, FK → users.id, INDEXED)
order_id (BIGINT, FK → orders.id, INDEXED)
discount_amount (DECIMAL 10,2)
created_at (TIMESTAMP)

INDEXES:
- coupon_id, user_id
- order_id
```

#### **vendor_payouts**

```
id (BIGINT, PK, AUTO_INCREMENT)
vendor_id (BIGINT, FK → vendors.id, INDEXED)
amount (DECIMAL 10,2)
period_start (DATE)
period_end (DATE)
status (ENUM: 'pending', 'processing', 'completed', 'failed', DEFAULT: 'pending', INDEXED)
payout_method (VARCHAR 50)
transaction_reference (VARCHAR 255, NULLABLE)
notes (TEXT, NULLABLE)
processed_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- vendor_id, status
- status
- created_at
```

#### **notifications**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, INDEXED)
type (VARCHAR 100, INDEXED)
title (VARCHAR 255)
message (TEXT)
data (JSON, NULLABLE)
read_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- user_id, read_at
- type
- created_at
```

#### **messages** (Buyer-Vendor communication)

```
id (BIGINT, PK, AUTO_INCREMENT)
sender_id (BIGINT, FK → users.id, INDEXED)
recipient_id (BIGINT, FK → users.id, INDEXED)
subject (VARCHAR 255, NULLABLE)
body (TEXT)
is_read (BOOLEAN, DEFAULT: false, INDEXED)
read_at (TIMESTAMP, NULLABLE)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- sender_id, created_at
- recipient_id, is_read
```

#### **shipping_zones**

```
id (BIGINT, PK, AUTO_INCREMENT)
name (VARCHAR 255)
countries (JSON) -- array of country codes
status (ENUM: 'active', 'inactive', DEFAULT: 'active')
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### **shipping_methods**

```
id (BIGINT, PK, AUTO_INCREMENT)
zone_id (BIGINT, FK → shipping_zones.id, INDEXED)
vendor_id (BIGINT, FK → vendors.id, NULLABLE, INDEXED) -- null = platform default
name (VARCHAR 255)
description (TEXT, NULLABLE)
rate_type (ENUM: 'flat_rate', 'weight_based', 'price_based')
cost (DECIMAL 10,2, NULLABLE) -- for flat rate
min_weight (DECIMAL 8,2, NULLABLE)
max_weight (DECIMAL 8,2, NULLABLE)
min_price (DECIMAL 10,2, NULLABLE)
max_price (DECIMAL 10,2, NULLABLE)
estimated_days (INT, NULLABLE)
status (ENUM: 'active', 'inactive', DEFAULT: 'active')
created_at (TIMESTAMP)
updated_at (TIMESTAMP)

INDEXES:
- zone_id, status
- vendor_id
```

#### **inventory_logs**

```
id (BIGINT, PK, AUTO_INCREMENT)
product_id (BIGINT, FK → products.id, INDEXED)
variant_id (BIGINT, FK → product_variants.id, NULLABLE, INDEXED)
user_id (BIGINT, FK → users.id, NULLABLE)
type (ENUM: 'adjustment', 'order', 'return', 'restock')
quantity_before (INT)
quantity_change (INT)
quantity_after (INT)
reason (TEXT, NULLABLE)
reference_id (BIGINT, NULLABLE) -- order_id, etc.
created_at (TIMESTAMP)

INDEXES:
- product_id, created_at
- variant_id
- type
```

#### **settings**

```
id (BIGINT, PK, AUTO_INCREMENT)
key (VARCHAR 255, UNIQUE, INDEXED)
value (TEXT, NULLABLE)
type (VARCHAR 50) -- string, integer, boolean, json
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### **audit_logs**

```
id (BIGINT, PK, AUTO_INCREMENT)
user_id (BIGINT, FK → users.id, NULLABLE, INDEXED)
action (VARCHAR 255, INDEXED)
model_type (VARCHAR 255, INDEXED)
model_id (BIGINT, NULLABLE)
old_values (JSON, NULLABLE)
new_values (JSON, NULLABLE)
ip_address (VARCHAR 45)
user_agent (TEXT, NULLABLE)
created_at (TIMESTAMP)

INDEXES:
- user_id, created_at
- action, created_at
- model_type, model_id
```

---

## 5. API / WEB ROUTES

### Authentication Routes (Public)

```
POST   /api/register                    - Register new user
POST   /api/login                        - Login user
POST   /api/logout                       - Logout user
POST   /api/password/email               - Send password reset email
POST   /api/password/reset               - Reset password
GET    /api/email/verify/{id}/{hash}    - Verify email
POST   /api/email/resend                 - Resend verification email
```

### User Profile Routes (Authenticated)

```
GET    /api/user                         - Get authenticated user
PUT    /api/user/profile                 - Update profile
POST   /api/user/avatar                  - Upload avatar
GET    /api/user/addresses               - List addresses
POST   /api/user/addresses               - Create address
PUT    /api/user/addresses/{id}          - Update address
DELETE /api/user/addresses/{id}          - Delete address
GET    /api/user/orders                  - Order history
GET    /api/user/orders/{orderNumber}    - View order details
POST   /api/user/2fa/enable              - Enable 2FA
POST   /api/user/2fa/disable             - Disable 2FA
```

### Buyer Routes

#### Product Browsing

```
GET    /api/products                     - List products (filters: category, price, rating, vendor, search)
GET    /api/products/{slug}              - Product details
GET    /api/products/{id}/reviews        - Product reviews
GET    /api/products/featured            - Featured products
GET    /api/products/search              - Search products (query, filters)
GET    /api/products/{id}/related        - Related products
```

#### Categories

```
GET    /api/categories                   - List categories (tree)
GET    /api/categories/{slug}            - Category details
GET    /api/categories/{slug}/products   - Products in category
```

#### Cart & Wishlist

```
GET    /api/cart                         - View cart
POST   /api/cart/add                     - Add item to cart
PUT    /api/cart/items/{id}              - Update cart item quantity
DELETE /api/cart/items/{id}              - Remove from cart
DELETE /api/cart/clear                   - Clear cart
POST   /api/cart/apply-coupon            - Apply coupon
DELETE /api/cart/remove-coupon           - Remove coupon

GET    /api/wishlist                     - View wishlist
POST   /api/wishlist/add                 - Add to wishlist
DELETE /api/wishlist/{productId}         - Remove from wishlist
```

#### Checkout & Orders

```
POST   /api/checkout/calculate           - Calculate totals (shipping, tax)
GET    /api/checkout/shipping-methods    - Available shipping methods
POST   /api/checkout/place-order         - Place order
GET    /api/orders/{orderNumber}         - Track order
POST   /api/orders/{id}/cancel           - Cancel order
POST   /api/orders/{id}/return           - Request return
```

#### Reviews

```
POST   /api/products/{id}/reviews        - Leave product review
PUT    /api/reviews/{id}                 - Update review
DELETE /api/reviews/{id}                 - Delete review
POST   /api/reviews/{id}/vote            - Vote review helpful/not
POST   /api/vendors/{id}/reviews         - Leave vendor review
```

#### Vendors (Browsing)

```
GET    /api/vendors                      - List vendors
GET    /api/vendors/{slug}               - Vendor profile/shop
GET    /api/vendors/{slug}/products      - Vendor's products
GET    /api/vendors/{id}/reviews         - Vendor reviews
POST   /api/vendors/{id}/follow          - Follow vendor
DELETE /api/vendors/{id}/unfollow        - Unfollow vendor
```

#### Messaging

```
GET    /api/messages                     - List conversations
GET    /api/messages/{id}                - View conversation
POST   /api/messages                     - Send message
```

---

### Vendor Routes (Authenticated Vendor)

#### Dashboard

```
GET    /api/vendor/dashboard             - Dashboard metrics
GET    /api/vendor/analytics             - Sales analytics
GET    /api/vendor/reports/sales         - Sales reports
```

#### Vendor Profile

```
GET    /api/vendor/profile               - Get vendor profile
PUT    /api/vendor/profile               - Update profile
POST   /api/vendor/profile/logo          - Upload logo
POST   /api/vendor/profile/banner        - Upload banner
PUT    /api/vendor/bank-details          - Update bank details
```

#### Product Management

```
GET    /api/vendor/products              - List own products
POST   /api/vendor/products              - Create product
GET    /api/vendor/products/{id}         - View product
PUT    /api/vendor/products/{id}         - Update product
DELETE /api/vendor/products/{id}         - Delete product
POST   /api/vendor/products/bulk-upload  - Bulk upload (CSV)

POST   /api/vendor/products/{id}/images  - Upload product images
DELETE /api/vendor/products/images/{id}  - Delete image

POST   /api/vendor/products/{id}/variants     - Create variant
PUT    /api/vendor/products/variants/{id}     - Update variant
DELETE /api/vendor/products/variants/{id}     - Delete variant
```

#### Inventory Management

```
GET    /api/vendor/inventory             - Inventory overview
PUT    /api/vendor/inventory/{productId} - Update stock
GET    /api/vendor/inventory/logs        - Inventory logs
GET    /api/vendor/inventory/low-stock   - Low stock alerts
```

#### Order Management

```
GET    /api/vendor/orders                - List orders (for vendor's products)
GET    /api/vendor/orders/{id}           - Order details
PUT    /api/vendor/orders/{id}/status    - Update order status
POST   /api/vendor/orders/{id}/ship      - Mark as shipped
POST   /api/vendor/orders/{id}/tracking  - Add tracking number
```

#### Coupons & Promotions

```
GET    /api/vendor/coupons               - List coupons
POST   /api/vendor/coupons               - Create coupon
PUT    /api/vendor/coupons/{id}          - Update coupon
DELETE /api/vendor/coupons/{id}          - Delete coupon
```

#### Shipping Settings

```
GET    /api/vendor/shipping              - Shipping settings
PUT    /api/vendor/shipping              - Update shipping settings
```

#### Payouts

```
GET    /api/vendor/payouts               - Payout history
POST   /api/vendor/payouts/request       - Request payout
GET    /api/vendor/earnings              - Earnings summary
```

#### Messages

```
GET    /api/vendor/messages              - Customer messages
POST   /api/vendor/messages/{id}/reply   - Reply to message
```

---

### Admin Routes (Authenticated Admin)

#### Dashboard

```
GET    /api/admin/dashboard              - Platform metrics
GET    /api/admin/analytics              - Platform analytics
GET    /api/admin/reports                - Generate reports
```

#### User Management

```
GET    /api/admin/users                  - List all users
GET    /api/admin/users/{id}             - User details
PUT    /api/admin/users/{id}             - Update user
POST   /api/admin/users/{id}/suspend     - Suspend user
POST   /api/admin/users/{id}/activate    - Activate user
DELETE /api/admin/users/{id}             - Delete user
```

#### Vendor Management

```
GET    /api/admin/vendors                - List all vendors
GET    /api/admin/vendors/pending        - Pending approvals
GET    /api/admin/vendors/{id}           - Vendor details
POST   /api/admin/vendors/{id}/approve   - Approve vendor
POST   /api/admin/vendors/{id}/reject    - Reject vendor
POST   /api/admin/vendors/{id}/suspend   - Suspend vendor
PUT    /api/admin/vendors/{id}           - Update vendor
PUT    /api/admin/vendors/{id}/commission - Update commission rate
```

#### Product Management

```
GET    /api/admin/products               - List all products
GET    /api/admin/products/{id}          - Product details
PUT    /api/admin/products/{id}          - Update product
DELETE /api/admin/products/{id}          - Delete product
POST   /api/admin/products/{id}/feature  - Feature/unfeature product
```

#### Category Management

```
GET    /api/admin/categories             - List categories
POST   /api/admin/categories             - Create category
PUT    /api/admin/categories/{id}        - Update category
DELETE /api/admin/categories/{id}        - Delete category
POST   /api/admin/categories/reorder     - Reorder categories
```

#### Order Management

```
GET    /api/admin/orders                 - List all orders
GET    /api/admin/orders/{id}            - Order details
PUT    /api/admin/orders/{id}/status     - Update order status
POST   /api/admin/orders/{id}/cancel     - Cancel order
POST   /api/admin/orders/{id}/refund     - Process refund
```

#### Review Moderation

```
GET    /api/admin/reviews                - List reviews
GET    /api/admin/reviews/pending        - Pending reviews
POST   /api/admin/reviews/{id}/approve   - Approve review
POST   /api/admin/reviews/{id}/reject    - Reject review
DELETE /api/admin/reviews/{id}           - Delete review
```

#### Coupon Management

```
GET    /api/admin/coupons                - List all coupons
POST   /api/admin/coupons                - Create platform coupon
PUT    /api/admin/coupons/{id}           - Update coupon
DELETE /api/admin/coupons/{id}           - Delete coupon
```

#### Payout Management

```
GET    /api/admin/payouts                - List payouts
GET    /api/admin/payouts/pending        - Pending payouts
POST   /api/admin/payouts/{id}/process   - Process payout
POST   /api/admin/payouts/{id}/reject    - Reject payout
```

#### Shipping Management

```
GET    /api/admin/shipping/zones         - List shipping zones
POST   /api/admin/shipping/zones         - Create zone
PUT    /api/admin/shipping/zones/{id}    - Update zone
DELETE /api/admin/shipping/zones/{id}    - Delete zone

GET    /api/admin/shipping/methods       - List shipping methods
POST   /api/admin/shipping/methods       - Create method
PUT    /api/admin/shipping/methods/{id}  - Update method
DELETE /api/admin/shipping/methods/{id}  - Delete method
```

#### Settings

```
GET    /api/admin/settings               - Get all settings
PUT    /api/admin/settings               - Update settings
```

#### Audit & Logs

```
GET    /api/admin/audit-logs             - Audit logs
GET    /api/admin/activity-logs          - Activity logs
```

#### Notifications

```
POST   /api/admin/notifications/send     - Send platform notification
GET    /api/admin/notifications/templates - Notification templates
```

---

## 6. ACTIONABLE TASKS & UX FLOW

### Phase 1: Setup & Foundation (Weeks 1-2)

- [ ] Set up Laravel project with authentication (Laravel Breeze/Sanctum)
- [ ] Create database migrations for all tables
- [ ] Set up models with relationships
- [ ] Configure file storage (S3/DO Spaces for production)
- [ ] Set up queues (Redis/database)
- [ ] Configure mail service (Mailgun/SES)
- [ ] Implement API authentication with Sanctum
- [ ] Create seeders for categories and test data

### Phase 2: User & Authentication (Week 3)

- [ ] User registration and login
- [ ] Email verification flow
- [ ] Password reset functionality
- [ ] Profile management
- [ ] Address management
- [ ] Two-factor authentication
- [ ] Role-based middleware

### Phase 3: Vendor Onboarding (Week 4)

- [ ] Vendor registration form
- [ ] Document upload functionality
- [ ] Admin approval workflow
- [ ] Vendor profile setup
- [ ] Bank details management
- [ ] Vendor dashboard skeleton

### Phase 4: Product Management (Weeks 5-6)

- [ ] Product CRUD for vendors
- [ ] Product image upload and management
- [ ] Product variants system
- [ ] Category assignment
- [ ] Inventory tracking
- [ ] Bulk product upload (CSV)
- [ ] Product search implementation (Laravel Scout)
- [ ] Product filtering and sorting

### Phase 5: Shopping Experience (Weeks 7-8)

- [ ] Product listing pages
- [ ] Product detail pages
- [ ] Shopping cart functionality
- [ ] Wishlist feature
- [ ] Guest cart to user cart migration
- [ ] Coupon application
- [ ] Real-time inventory checks

### Phase 6: Checkout & Orders (Weeks 9-10)

- [ ] Checkout flow UI
- [ ] Shipping method selection
- [ ] Order creation logic
- [ ] Order splitting by vendor
- [ ] Payment gateway integration (Stripe)
- [ ] Order confirmation emails
- [ ] Order tracking page
- [ ] Order cancellation flow

### Phase 7: Vendor Order Management (Week 11)

- [ ] Vendor order dashboard
- [ ] Order fulfillment workflow
- [ ] Shipping label integration (optional)
- [ ] Inventory reduction on order
- [ ] Order status updates
- [ ] Email notifications to buyers

### Phase 8: Reviews & Ratings (Week 12)

- [ ] Product review submission
- [ ] Vendor review submission
- [ ] Review moderation (admin)
- [ ] Review display on products
- [ ] Review voting system
- [ ] Review replies
- [ ] Rating aggregation

### Phase 9: Admin Panel (Weeks 13-14)

- [ ] Admin dashboard with metrics
- [ ] User management interface
- [ ] Vendor approval interface
- [ ] Product moderation
- [ ] Order management
- [ ] Category management
- [ ] Settings page
- [ ] Audit logs

### Phase 10: Payments & Payouts (Week 15)

- [ ] Vendor earnings calculation
- [ ] Commission tracking
- [ ] Payout request system
- [ ] Admin payout processing
- [ ] Payment history
- [ ] Refund processing

### Phase 11: Notifications & Messaging (Week 16)

- [ ] Email notifications system
- [ ] In-app notifications
- [ ] Notification preferences
- [ ] Buyer-vendor messaging
- [ ] Real-time notifications (Pusher/WebSockets)

### Phase 12: Analytics & Reporting (Week 17)

- [ ] Vendor dashboard analytics
- [ ] Admin analytics dashboard
- [ ] Sales reports
- [ ] Product performance reports
- [ ] Export functionality

### Phase 13: Testing & Optimization (Weeks 18-19)

- [ ] Unit tests for models
- [ ] Feature tests for API endpoints
- [ ] Performance optimization
- [ ] Query optimization
- [ ] Caching implementation
- [ ] Security audit
- [ ] Load testing

### Phase 14: Deployment & Launch (Week 20)

- [ ] Production server setup
- [ ] CI/CD pipeline
- [ ] Database backup strategy
- [ ] Monitoring setup (Sentry, New Relic)
- [ ] SSL configuration
- [ ] CDN setup
- [ ] Final testing
- [ ] Launch

---

## 7. UX FLOW OUTLINES

### Buyer Journey

1. **Discovery** → Land on homepage → Browse categories/featured products
2. **Search** → Use search bar → Apply filters → View results
3. **Product View** → Click product → View details/images/reviews → Add to cart
4. **Cart** → View cart → Update quantities → Apply coupon → Proceed to checkout
5. **Checkout** → Select/add address → Choose shipping method → Enter payment → Review order → Place order
6. **Confirmation** → Receive email → View order details → Track order
7. **Receipt** → Receive product → Leave review → Contact vendor if issues

### Vendor Journey

1. **Registration** → Apply as vendor → Upload documents → Wait for approval
2. **Approval** → Receive approval email → Login → Complete profile setup
3. **Setup** → Configure shop profile → Set up shipping → Add bank details
4. **Listing** → Add first product → Upload images → Set inventory → Publish
5. **Sales** → Receive order notification → View order details → Process order → Mark as shipped
6. **Growth** → View analytics → Create promotions → Add more products → Respond to reviews
7. **Earnings** → Track earnings → Request payout → Receive payment

### Admin Journey

1. **Monitoring** → Login to admin panel → View dashboard metrics
2. **Vendor Management** → Review pending vendors → Approve/reject applications
3. **Content Moderation** → Review flagged products → Moderate reviews → Handle disputes
4. **Configuration** → Manage categories → Update settings → Configure shipping zones
5. **Analytics** → View platform reports → Export data → Make strategic decisions

---

## 8. TECHNICAL CONSIDERATIONS

### Performance Optimizations

- **Database**: Index all foreign keys, status fields, and frequently queried columns
- **Caching**: Redis for sessions, cache, and queues; Cache product listings, categories, vendor data
- **CDN**: Store product images and static assets on CDN (CloudFront, CloudFlare)
- **Search**: Use Laravel Scout with Algolia or Meilisearch for fast product search
- **Queues**: Queue emails, notifications, image processing, report generation
- **Eager Loading**: Use eager loading to prevent N+1 queries
- **Pagination**: Paginate all list views
- **API**: Implement rate limiting and response caching

### Security Measures

- **Authentication**: Use Laravel Sanctum for API tokens
- **Authorization**: Policy-based authorization for all resources
- **Validation**: Server-side validation for all inputs
- **CSRF**: CSRF protection on all forms
- **XSS**: Escape all user-generated content
- **SQL Injection**: Use Eloquent ORM and parameterized queries
- **File Uploads**: Validate file types, sizes; Store outside web root
- **Payment**: PCI compliance through Stripe; Never store raw card data
- **HTTPS**: Enforce HTTPS in production
- **Rate Limiting**: API rate limiting per user/IP
- **2FA**: Two-factor authentication for sensitive operations
- **Audit Logs**: Log all admin and critical actions

### Scalability Considerations

- **Horizontal Scaling**: Design for stateless application servers
- **Database**: Use read replicas for heavy read operations
- **Caching**: Implement multi-layer caching (Redis, CDN)
- **Storage**: Use object storage (S3) for media files
- **Background Jobs**: Use queue workers for async processing
- **Microservices**: Consider splitting payment, notification services later
- **Load Balancing**: Set up load balancer in production
- **Monitoring**: Implement APM (New Relic, Datadog)

### Testing Strategy

- **Unit Tests**: Test models, services, utilities
- **Feature Tests**: Test API endpoints and workflows
- **Browser Tests**: Laravel Dusk for critical user flows
- **Load Testing**: Use tools like k6 or JMeter
- **Security Testing**: Regular penetration testing
- **Accessibility**: WCAG compliance testing

---

## 9. TECHNOLOGY STACK RECOMMENDATIONS

### Backend

- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0 / PostgreSQL
- **Cache**: Redis
- **Queue**: Redis / Laravel Horizon
- **Search**: Laravel Scout + Meilisearch/Algolia
- **Storage**: AWS S3 / DigitalOcean Spaces
- **Payment**: Stripe
- **Email**: Mailgun / AWS SES
- **Authentication**: Laravel Sanctum

### Frontend Options

**Option 1: Laravel Blade + Alpine.js + Livewire**

- Pros: Simpler, less JS complexity, faster initial development
- Cons: Less interactive, harder for complex UIs

**Option 2: Laravel API + Vue.js 3 (Recommended)**

- Pros: Modern, reactive, great UX, reusable components
- Cons: More complex setup, steeper learning curve

**Option 3: Laravel API + React + Next.js**

- Pros: Best for SEO, SSR capabilities, huge ecosystem
- Cons: Most complex, separate deployment

### DevOps

- **Version Control**: Git + GitHub/GitLab
- **CI/CD**: GitHub Actions / GitLab CI
- **Hosting**: AWS / DigitalOcean / Cloudways
- **Monitoring**: Sentry (errors), New Relic (APM)
- **Backups**: Automated database and file backups
- **SSL**: Let's Encrypt / CloudFlare

---

## 10. PAYMENT GATEWAY & CHECKOUT FLOW

### Checkout Flow (Step-by-Step)

#### Step 1: Cart Review

- Display cart items grouped by vendor
- Show product details (name, variant, price, quantity)
- Calculate subtotals per vendor
- Display any applied coupons/discounts
- Allow quantity adjustments or item removal
- Show estimated totals (before shipping)
- "Proceed to Checkout" button

#### Step 2: Authentication Check

- If guest: prompt to login or continue as guest
- If guest checkout: collect email for order confirmation
- If logged in: proceed to shipping

#### Step 3: Shipping Information

- Select from saved addresses OR add new address
- Address validation (format, completeness)
- Option to save address for future use
- Billing address (same as shipping OR different)
- Continue to shipping method

#### Step 4: Shipping Method Selection

- Display available shipping methods per vendor (if split)
- Show shipping costs and estimated delivery times
- Calculate shipping for each vendor's items
- Allow different shipping methods per vendor
- Update total with shipping costs
- Continue to payment

#### Step 5: Payment Information

- Select payment method (credit card, PayPal, bank transfer, etc.)
- For credit cards: collect card details (via Stripe Elements - tokenized)
- Option to save payment method
- Apply final coupon code if not already applied
- Display order summary with all costs

#### Step 6: Order Review

- Final summary of all items, quantities, prices
- Shipping address confirmation
- Shipping method confirmation
- Payment method confirmation
- Total breakdown (subtotal, shipping, tax, discount, total)
- Terms and conditions checkbox
- "Place Order" button

#### Step 7: Payment Processing

- Create order record(s) in database
- Process payment through gateway
- Handle payment success/failure
- Split payment amounts to vendor accounts
- Calculate platform commission
- Reserve inventory

#### Step 8: Confirmation

- Display order confirmation page
- Show order number and details
- Send confirmation email to buyer
- Send order notification to each vendor
- Redirect to order tracking page

### Multiple Payment Gateway Handling

**Supported Gateways:**

- **Stripe** (Primary - credit/debit cards)
- **PayPal** (Alternative wallet payment)
- **Bank Transfer** (Manual verification)
- **Cash on Delivery** (Optional)

**Implementation Strategy:**

```
Payment Gateway Interface
├── StripeGateway (implements PaymentGatewayInterface)
├── PayPalGateway (implements PaymentGatewayInterface)
├── BankTransferGateway (implements PaymentGatewayInterface)
└── Methods: charge(), refund(), verify()
```

**Gateway Selection Logic:**

- Admin configures available gateways
- Buyer selects preferred method at checkout
- System routes payment to appropriate gateway
- Store gateway reference with payment record
- Handle gateway-specific webhooks

### Split Payments & Vendor Commission

**Payment Flow:**

1. Buyer pays full order amount to platform
2. Platform receives payment
3. System calculates per vendor:
   - Vendor earnings = (item total - commission)
   - Platform commission = (item total × commission_rate)
4. Store split in `order_items` table:
   ```
   vendor_earnings = subtotal - commission_amount
   commission_amount = subtotal × commission_rate
   ```
5. Vendor payouts happen on schedule (weekly/monthly)

**Commission Types:**

- Fixed percentage per vendor (e.g., 10%, 15%)
- Category-based commission rates
- Tiered commission (volume-based)
- Platform-wide default with vendor overrides

**Payout Process:**

- Vendors request payout or auto-payout on schedule
- Admin reviews and approves payout requests
- Platform transfers funds to vendor bank account
- Record payout in `vendor_payouts` table
- Send payout confirmation email

### Refund Handling

**Refund Types:**

- Full order refund
- Partial refund (specific items)
- Refund to original payment method
- Store credit refund

**Refund Flow:**

1. Buyer or vendor initiates refund request
2. Admin reviews and approves
3. System processes refund through payment gateway
4. Adjust vendor earnings (deduct refunded amount)
5. Update order status to 'refunded'
6. Notify buyer and vendor
7. Restore inventory if applicable

**Refund Considerations:**

- Gateway fees typically non-refundable
- Partial refunds adjust commission proportionally
- Refund deadlines (e.g., 30 days after delivery)
- Reason tracking for analytics

### Order Status Lifecycle

**Status Flow:**

```
pending → processing → shipped → delivered
   ↓           ↓          ↓
cancelled   cancelled   returned → refunded
```

**Status Definitions:**

- **pending**: Order placed, payment processing
- **processing**: Payment confirmed, vendor preparing items
- **shipped**: Items dispatched with tracking
- **delivered**: Items received by buyer
- **cancelled**: Order cancelled before shipping
- **returned**: Items returned after delivery
- **refunded**: Payment returned to buyer

**Status Triggers:**

- Automated: payment confirmation, delivery tracking
- Manual: vendor marks as shipped, admin approves refund
- Time-based: auto-cancel unpaid orders after 24 hours

---

## 11. IMAGE & ASSET MANAGEMENT

### Storage Strategy

**Recommended Approach: Cloud Storage (S3-compatible)**

- AWS S3 / DigitalOcean Spaces / CloudFlare R2
- CDN for fast global delivery (CloudFront / CloudFlare)
- Laravel's filesystem abstraction for flexibility

**Directory Structure:**

```
storage/
├── products/
│   ├── {vendor_id}/
│   │   ├── {product_id}/
│   │   │   ├── original/
│   │   │   │   ├── image1.jpg
│   │   │   ├── large/
│   │   │   │   ├── image1.jpg (1200x1200)
│   │   │   ├── medium/
│   │   │   │   ├── image1.jpg (600x600)
│   │   │   ├── thumbnail/
│   │   │   │   ├── image1.jpg (200x200)
├── vendors/
│   ├── logos/
│   ├── banners/
├── users/
│   ├── avatars/
├── categories/
│   ├── icons/
└── reviews/
    ├── {review_id}/
```

### Image Processing Workflow

**Upload Process:**

1. Vendor uploads image(s) via form/API
2. Validate file (type, size, dimensions)
   - Allowed: JPG, PNG, WebP, GIF
   - Max size: 5MB per image
   - Min dimensions: 500x500px
3. Generate unique filename (UUID)
4. Store original image
5. Queue image processing job
6. Generate multiple sizes (large, medium, thumbnail)
7. Optimize images (compress, convert to WebP)
8. Upload to cloud storage
9. Store URLs in `product_images` table
10. Return image record to vendor

**Image Sizes:**

- **Original**: As uploaded (backup)
- **Large**: 1200x1200px (product detail page)
- **Medium**: 600x600px (product listing)
- **Thumbnail**: 200x200px (cart, small previews)
- **WebP versions**: For modern browsers (10-30% smaller)

### Variant Images

**Strategy:**

- Each product has default images
- Variants can have optional specific images
- If variant has image, display that; otherwise fallback to product images
- Store variant image in `product_variants.image_url`
- UI shows image when variant is selected

### CDN Configuration

**Benefits:**

- Fast image delivery worldwide
- Reduced server load
- Automatic caching
- Image transformations on-the-fly (optional)

**Implementation:**

- Configure CDN origin to S3 bucket
- Update image URLs to use CDN domain
- Set cache headers (long expiry for images)
- Purge CDN cache when images updated

### Image Security

**Security Measures:**

- Validate MIME types (not just extensions)
- Scan for malware (ClamAV integration)
- Strip EXIF data (privacy)
- Prevent directory traversal attacks
- Generate non-guessable filenames
- Implement rate limiting on uploads
- Restrict direct file access (signed URLs)

### Optimization Tools

**Laravel Packages:**

- **Intervention Image**: Image manipulation
- **Spatie Media Library**: Advanced media handling
- **Laravel Image Optimizer**: Automatic compression

**Background Processing:**

- Queue image processing (don't block uploads)
- Use Laravel queues with Redis
- Retry failed jobs
- Monitor queue health

---

## 12. INVENTORY & STOCK SYNC

### Inventory Tracking Model

**Stock Levels:**

- `quantity`: Current available stock
- `reserved`: Stock in active carts/pending orders
- `available`: quantity - reserved
- `low_stock_threshold`: Alert level (e.g., 5 units)

### Stock Updates on Order

**Order Placement Flow:**

1. Buyer adds items to cart
   - No stock reservation yet (cart is temporary)
2. Buyer proceeds to checkout
   - System checks real-time availability
   - If insufficient stock, show error
3. Buyer places order
   - **Immediately reserve stock** (deduct from quantity)
   - Lock inventory during transaction
   - Create order record
   - Process payment
4. Payment confirmed
   - Stock remains deducted
   - Order moves to "processing"
5. Order cancelled/failed
   - **Return stock** to available quantity
   - Log inventory adjustment

**Database Transaction:**

```php
DB::transaction(function () {
    // Check stock
    $product = Product::lockForUpdate()->find($id);

    if ($product->quantity < $requestedQty) {
        throw new InsufficientStockException();
    }

    // Deduct stock
    $product->decrement('quantity', $requestedQty);

    // Create order
    Order::create([...]);

    // Log inventory change
    InventoryLog::create([...]);
});
```

### Preventing Overselling

**Strategies:**

1. **Database Locking**

   - Use `lockForUpdate()` during checkout
   - Ensures atomic stock checks and updates

2. **Real-Time Validation**

   - Check stock immediately before payment processing
   - Show "Out of Stock" if depleted during checkout

3. **Reserved Stock System** (Advanced)

   - Reserve stock when item added to cart
   - Auto-release reservation after 15 minutes
   - Prevents race conditions during high-traffic sales

4. **Queue-Based Stock Updates**

   - Process stock changes sequentially
   - Prevents concurrent modification issues

5. **Inventory Constraints**
   - Database constraint: `quantity >= 0`
   - Application-level validation
   - Alert when overselling detected

### Vendor Stock Management

**Vendor Capabilities:**

- View current stock levels
- Manually adjust stock (add/remove)
- Set low stock alerts
- Bulk stock updates (CSV import)
- View stock history/audit log
- Auto low-stock notifications

**Stock Adjustment Types:**

- **Restock**: Vendor adds inventory
- **Damage/Loss**: Vendor removes inventory
- **Order**: System deducts on order
- **Return**: System adds back on return
- **Correction**: Manual adjustment

**Inventory Logs:**

- Track every stock change
- Store: product_id, quantity_before, quantity_change, quantity_after, reason, user_id
- Audit trail for disputes
- Analytics on stock turnover

### Multi-Location Inventory (Future Enhancement)

**Concept:**

- Vendors with multiple warehouses
- Stock allocated per location
- Fulfill from nearest warehouse
- Transfer stock between locations

**Schema Addition:**

```
inventory_locations
├── vendor_id
├── location_name
├── address
└── status

product_inventory
├── product_id
├── location_id
├── quantity
└── reserved
```

### Low Stock Alerts

**Alert System:**

- Check stock levels daily (scheduled job)
- If `quantity <= low_stock_threshold`, trigger alert
- Send email/notification to vendor
- Display warning in vendor dashboard
- Option to auto-reorder (integration with suppliers)

**Dashboard Indicators:**

- Red badge: Out of stock (0)
- Yellow badge: Low stock (≤ threshold)
- Green: Adequate stock

---

## 13. NOTIFICATIONS & REAL-TIME UPDATES

### Notification Types

#### Email Notifications

**Buyer Emails:**

- Registration welcome email
- Email verification
- Order confirmation
- Order shipped (with tracking)
- Order delivered
- Password reset
- Review request (post-delivery)
- Abandoned cart reminder
- Promotions/newsletter (if opted in)

**Vendor Emails:**

- Vendor approval/rejection
- New order received
- Low stock alert
- Payout processed
- New review on product
- New message from buyer

**Admin Emails:**

- New vendor application
- Dispute raised
- High-value order notification
- System errors/alerts

#### In-App Notifications

**Real-Time Notification Bell:**

- New orders (vendors)
- Order status changes (buyers)
- New messages
- Payment received (vendors)
- Review posted
- Promotions/announcements

**Implementation:**

- Store in `notifications` table
- Mark as read/unread
- Notification center dropdown
- Badge count on icon

#### SMS Notifications (Optional)

**High-Priority Events:**

- Order confirmation
- Shipped notification with tracking
- Delivery confirmation
- OTP for 2FA

**Provider Integration:**

- Twilio / Vonage / AWS SNS
- Template management
- Opt-in/opt-out handling
- Cost considerations

### Real-Time Features

#### WebSocket Implementation

**Use Cases:**

- Live order status updates
- Real-time chat (buyer-vendor)
- Live dashboard metrics
- Stock level updates
- Notification push

**Technology Options:**

**Option 1: Laravel WebSockets (Recommended for MVP)**

- Self-hosted WebSocket server
- Compatible with Pusher API
- No third-party costs
- Good for low-to-medium traffic

**Option 2: Pusher (Easiest)**

- Hosted service
- Easy Laravel integration
- Free tier available
- Best for quick launch

**Option 3: Laravel Reverb (Laravel 11+)**

- Official Laravel WebSocket server
- Built-in to Laravel
- First-party support
- Modern alternative

**Implementation:**

```php
// Broadcast order update
event(new OrderStatusUpdated($order));

// Listen on frontend
Echo.private(`orders.${orderId}`)
    .listen('OrderStatusUpdated', (e) => {
        // Update UI
    });
```

#### Real-Time Dashboard Updates

**Vendor Dashboard Live Stats:**

- Today's sales (updates every sale)
- Pending orders count
- Low stock alerts
- New reviews

**Admin Dashboard:**

- Platform GMV (real-time)
- Active users count
- Pending vendor approvals
- New orders across platform

**Implementation:**

- WebSocket events for critical updates
- Polling for less critical data (every 30-60s)
- Cache frequently accessed metrics

### Notification Preferences

**User Settings:**

- Email preferences (order updates, promotions, etc.)
- SMS opt-in/opt-out
- In-app notification types
- Notification frequency (immediate, daily digest, weekly)

**Database:**

```
notification_preferences
├── user_id
├── channel (email, sms, in_app)
├── type (order_updates, promotions, etc.)
└── enabled (boolean)
```

### Notification Queue & Delivery

**Queue Strategy:**

- Queue all email/SMS notifications
- Process via background workers
- Retry failed notifications (3 attempts)
- Log delivery status
- Handle bounces and failures

**Rate Limiting:**

- Prevent spam (max notifications per user per hour)
- Throttle promotional emails
- Respect user preferences

---

## 14. SECURITY & PERFORMANCE CONSIDERATIONS

### Authentication & Authorization

**Authentication:**

- Laravel Sanctum for API token authentication
- Session-based auth for web
- Secure password hashing (bcrypt)
- Optional 2FA (TOTP via Google Authenticator)
- Password reset with expiring tokens
- Email verification required
- Remember me functionality
- Rate limiting on login attempts (5 attempts per 15 min)

**Authorization (Policies & Gates):**

- Policy classes for each resource
- Vendor can only access own data
- Buyers can only see own orders
- Admin has unrestricted access

**Example Policies:**

```php
ProductPolicy
├── view(): anyone
├── create(): vendor only
├── update(): owner or admin
├── delete(): owner or admin

OrderPolicy
├── view(): buyer, related vendor, or admin
├── cancel(): buyer (if pending) or admin
├── refund(): admin only
```

### Data Separation & Multi-Tenancy

**Vendor Data Isolation:**

- Query scopes: automatically filter by vendor_id
- Middleware: verify vendor owns resource
- Database: vendor_id on all relevant tables
- API: validate vendor access on every request

**Global Scopes (Laravel):**

```php
protected static function booted()
{
    static::addGlobalScope('vendor', function (Builder $builder) {
        if (auth()->user()->isVendor()) {
            $builder->where('vendor_id', auth()->user()->vendor->id);
        }
    });
}
```

### Input Sanitization & Validation

**Request Validation:**

- Laravel Form Requests for all inputs
- Validate data types, formats, ranges
- Custom validation rules
- Sanitize HTML inputs (strip dangerous tags)

**XSS Prevention:**

- Escape output in Blade templates (`{{ }}`)
- Use `{!! !!}` sparingly and only for trusted content
- Content Security Policy headers
- Sanitize rich text (TinyMCE/CKEditor) with HTML Purifier

**SQL Injection Prevention:**

- Use Eloquent ORM (parameterized queries)
- Never concatenate raw SQL with user input
- Validate/sanitize raw queries when necessary

### File Upload Security

**Upload Validation:**

- Whitelist file types (MIME validation)
- Max file size limits
- Scan for malware (ClamAV)
- Rename files (prevent path traversal)
- Store outside web root
- Use signed URLs for private files

**Image Uploads Specific:**

- Validate image dimensions
- Strip EXIF data
- Re-encode images (prevents malicious embedded code)
- Rate limit uploads (5 per minute per user)

### Payment Security (PCI Compliance)

**PCI DSS Compliance:**

- **Never store raw card data**
- Use Stripe/PayPal tokenization
- Process payments via gateway APIs only
- Store only payment method tokens
- Encrypt sensitive vendor bank details
- Use HTTPS everywhere (enforce SSL)
- Regular security audits

**Secure Payment Flow:**

1. Frontend collects card via Stripe Elements (PCI-compliant)
2. Stripe tokenizes card, returns token
3. Backend receives token only (not card data)
4. Backend processes payment with token
5. Store transaction_id, not card details

### Performance Optimization

#### Caching Strategy

**Cache Layers:**

- **Application Cache** (Redis): frequently accessed data
- **Database Query Cache**: cache query results
- **CDN Cache**: static assets, images
- **HTTP Cache**: full-page caching for public pages

**What to Cache:**

- Product listings (by category, filters)
- Category tree
- Vendor profiles
- Homepage content
- Settings and configurations
- Search results (with TTL)
- User sessions

**Cache Invalidation:**

- Clear product cache when updated
- Use cache tags for granular invalidation
- Time-based expiration (TTL)
- Event-based cache clearing

**Laravel Caching:**

```php
// Cache product listing
$products = Cache::remember('products.category.' . $categoryId, 3600, function () {
    return Product::where('category_id', $categoryId)
        ->with('images', 'vendor')
        ->get();
});
```

#### Database Optimization

**Indexing:**

- Index all foreign keys
- Index frequently queried columns (status, created_at, email)
- Composite indexes for common filter combinations
- Full-text indexes for search

**Query Optimization:**

- Use `select()` to fetch only needed columns
- Eager load relationships (prevent N+1)
- Paginate large datasets
- Use database indexing
- Optimize JOIN queries
- Use `chunk()` for batch processing
- Database query logging (identify slow queries)

**Example N+1 Prevention:**

```php
// Bad (N+1 problem)
$products = Product::all();
foreach ($products as $product) {
    echo $product->vendor->name; // Query per product
}

// Good (eager loading)
$products = Product::with('vendor')->get();
foreach ($products as $product) {
    echo $product->vendor->name; // Single query
}
```

#### Queue & Background Jobs

**Async Processing:**

- Send emails via queue
- Process image uploads
- Generate reports
- Send notifications
- Import bulk products (CSV)
- Calculate analytics

**Queue Configuration:**

- Use Redis for queue backend
- Multiple queues (high, default, low priority)
- Supervisord to manage workers
- Monitor queue depth
- Failed job handling

#### API Rate Limiting

**Throttling:**

- Public API: 60 requests/minute per IP
- Authenticated API: 1000 requests/minute per user
- Admin API: unlimited (or higher limit)
- Vendor API: 500 requests/minute

**Implementation:**

```php
Route::middleware('throttle:60,1')->group(function () {
    // Public routes
});

Route::middleware('throttle:1000,1')->group(function () {
    // Authenticated routes
});
```

### Security Headers

**HTTP Headers:**

```
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
Content-Security-Policy: default-src 'self'
Referrer-Policy: no-referrer-when-downgrade
```

### Monitoring & Logging

**Error Tracking:**

- Sentry for error monitoring
- Log all exceptions
- Alert on critical errors

**Activity Logging:**

- Audit trail for admin actions
- Log user logins
- Log payment transactions
- Log inventory changes

**Performance Monitoring:**

- New Relic / Datadog for APM
- Track slow queries
- Monitor API response times
- Track job queue health

---

## 15. FRONTEND UX / UI FLOW SUGGESTIONS

### Buyer Experience Flows

#### Flow 1: Homepage to Purchase

**1. Homepage (Landing)**

```
┌────────────────────────────────────────┐
│  [Logo]    Search Bar    [Cart] [User] │
├────────────────────────────────────────┤
│                                        │
│         HERO BANNER                    │
│      "Summer Sale - 50% Off"           │
│                                        │
├────────────────────────────────────────┤
│  Featured Categories                   │
│  [Electronics] [Fashion] [Home] [...]  │
├────────────────────────────────────────┤
│  Featured Products                     │
│  [Product] [Product] [Product] [...]   │
│  Rating ⭐⭐⭐⭐⭐ $29.99              │
├────────────────────────────────────────┤
│  Top Vendors                           │
│  [Vendor Logo] [Vendor Logo] [...]     │
└────────────────────────────────────────┘
```

**2. Product Listing Page**

```
┌────────────────────────────────────────┐
│  Home > Electronics > Laptops          │
├──────────┬─────────────────────────────┤
│ FILTERS  │  Sort: [Relevance ▼]        │
│          │                             │
│ Category │  [Grid] [List]   1-24 of 156│
│ ☑ Laptop │                             │
│ ☐ Desktop│  ┌──────┐ ┌──────┐ ┌──────┐ │
│          │  │[IMG] │ │[IMG] │ │[IMG] │ │
│ Price    │  │$899  │ │$1299 │ │$699  │ │
│ ●───○    │  │⭐4.5 │ │⭐4.8 │ │⭐4.2 │ │
│$0 - $2000│  │[Cart]│ │[Cart]│ │[Cart]│ │
│          │  └──────┘ └──────┘ └──────┘ │
│ Brand    │                             │
│ ☑ Dell   │  ┌──────┐ ┌──────┐ ┌──────┐ │
│ ☐ HP     │  │ ...  │ │ ...  │ │ ...  │ │
│ ☐ Apple  │  └──────┘ └──────┘ └──────┘ │
│          │                             │
│ Rating   │  [Load More]   Pagination   │
│ ☑ 4+ ⭐  │                             │
└──────────┴─────────────────────────────┘
```

**3. Product Detail Page**

```
┌────────────────────────────────────────┐
│ Home > Electronics > Laptops > Dell XPS│
├───────────────┬────────────────────────┤
│               │  Dell XPS 15 Laptop    │
│  [Main Image] │  by TechStore ⭐ 4.9   │
│               │                        │
│  [Thumb][Thumb│  $1,299.99  $1,499.99  │
│   [Thumb][Thumb]                       │
│               │  ✓ In Stock (15 units) │
│   [Zoom]      │                        │
│               │  Color: [Silver] [Black]
│               │  RAM:   [8GB] [16GB] [32GB]
│               │  Storage: [256] [512] [1TB]
│               │                        │
│               │  Quantity: [- 1 +]     │
│               │                        │
│               │  [Add to Cart]         │
│               │  [Add to Wishlist ♡]   │
├───────────────┴────────────────────────┤
│ TABS: [Description] [Specs] [Reviews]  │
│                                        │
│ Description text here...               │
├────────────────────────────────────────┤
│ Customer Reviews (248) ⭐⭐⭐⭐⭐ 4.8  │
│                                        │
│ ⭐⭐⭐⭐⭐ John D. | Verified Purchase │
│ "Great laptop, fast delivery!"         │
│ [Helpful 👍 45]  [Reply]              │
├────────────────────────────────────────┤
│ Related Products                       │
│ [Product] [Product] [Product]          │
└────────────────────────────────────────┘
```

**4. Shopping Cart**

```
┌────────────────────────────────────────┐
│  Shopping Cart (3 items)               │
├────────────────────────────────────────┤
│ Vendor: TechStore                      │
│ ┌────────────────────────────────────┐ │
│ │[IMG] Dell XPS 15        $1,299.99  │ │
│ │      RAM: 16GB, Storage: 512GB     │ │
│ │      Qty: [- 1 +]  [Remove] [Save] │ │
│ └────────────────────────────────────┘ │
│                                        │
│ Vendor: HomeGoods                      │
│ ┌────────────────────────────────────┐ │
│ │[IMG] Coffee Maker         $49.99   │ │
│ │      Qty: [- 1 +]  [Remove] [Save] │ │
│ ├────────────────────────────────────┤ │
│ │[IMG] Blender              $79.99   │ │
│ │      Qty: [- 1 +]  [Remove] [Save] │ │
│ └────────────────────────────────────┘ │
├────────────────────────────────────────┤
│ Coupon: [Enter Code]  [Apply]          │
├────────────────────────────────────────┤
│ Subtotal:          $1,429.97           │
│ Shipping:          TBD                 │
│ Tax:               TBD                 │
│ ────────────────────────────           │
│ Estimated Total:   $1,429.97           │
│                                        │
│ [Continue Shopping] [Proceed to Checkout]
└────────────────────────────────────────┘
```

**5. Checkout - Multi-Step**

```
STEP 1: SHIPPING
┌────────────────────────────────────────┐
│ ◉ Shipping  ○ Payment  ○ Review        │
├────────────────────────────────────────┤
│ Shipping Address                       │
│ ⦿ John Doe                             │
│   123 Main St, New York, NY 10001      │
│   [Edit] [Delete]                      │
│                                        │
│ ○ Jane Doe                             │
│   456 Elm St, Brooklyn, NY 11201       │
│   [Edit] [Delete]                      │
│                                        │
│ [+ Add New Address]                    │
│                                        │
│ [Continue to Payment →]                │
└────────────────────────────────────────┘

STEP 2: PAYMENT
┌────────────────────────────────────────┐
│ ○ Shipping  ◉ Payment  ○ Review        │
├────────────────────────────────────────┤
│ Shipping Method                        │
│ Vendor: TechStore                      │
│ ⦿ Standard Shipping - $10.00 (3-5 days)│
│ ○ Express Shipping - $25.00 (1-2 days) │
│                                        │
│ Vendor: HomeGoods                      │
│ ⦿ Standard Shipping - $8.00 (3-5 days) │
│ ○ Express Shipping - $15.00 (1-2 days) │
├────────────────────────────────────────┤
│ Payment Method                         │
│ ⦿ Credit Card                          │
│   Card Number: [________________]      │
│   Expiry: [MM/YY]  CVV: [___]          │
│   ☑ Save for future purchases          │
│                                        │
│ ○ PayPal                               │
│ ○ Bank Transfer                        │
│                                        │
│ [← Back]  [Continue to Review →]      │
└────────────────────────────────────────┘

STEP 3: REVIEW & PLACE ORDER
┌────────────────────────────────────────┐
│ ○ Shipping  ○ Payment  ◉ Review        │
├────────────────────────────────────────┤
│ Order Summary                          │
│                                        │
│ Shipping to: John Doe, 123 Main St...  │
│ Payment: Credit Card ending in 1234    │
│                                        │
│ Items (3):                             │
│ • Dell XPS 15        $1,299.99         │
│ • Coffee Maker          $49.99         │
│ • Blender               $79.99         │
│                                        │
│ Subtotal:            $1,429.97         │
│ Shipping:               $18.00         │
│ Tax:                    $115.00        │
│ Discount (CODE10):     -$142.99        │
│ ────────────────────────────           │
│ Total:               $1,419.98         │
│                                        │
│ ☑ I agree to Terms & Conditions        │
│                                        │
│ [← Back]  [Place Order →]              │
└────────────────────────────────────────┘
```

**6. Order Confirmation**

```
┌────────────────────────────────────────┐
│         ✓ Order Placed!                │
│                                        │
│  Order #ORD-2025-123456                │
│  Estimated Delivery: Oct 18, 2025      │
│                                        │
│  Confirmation sent to: john@email.com  │
├────────────────────────────────────────┤
│ Order Details                          │
│ Items (3)              $1,429.97       │
│ Shipping                  $18.00       │
│ Tax                      $115.00       │
│ Discount                -$142.99       │
│ ────────────────────────────           │
│ Total Paid            $1,419.98        │
├────────────────────────────────────────┤
│ Shipping Address                       │
│ John Doe                               │
│ 123 Main St, New York, NY 10001        │
├────────────────────────────────────────┤
│ [View Order Details]                   │
│ [Continue Shopping]                    │
└────────────────────────────────────────┘
```

#### Flow 2: Search & Filter

**Search Suggestions (Autocomplete)**

```
┌────────────────────────────────────┐
│ Search: [lap_]                     │
├────────────────────────────────────┤
│ 🔍 laptop                          │
│ 🔍 laptop bag                      │
│ 🔍 laptop stand                    │
├────────────────────────────────────┤
│ Products:                          │
│ [IMG] Dell XPS 15 Laptop - $1,299  │
│ [IMG] HP Pavilion Laptop - $899    │
├────────────────────────────────────┤
│ Categories:                        │
│ 📂 Electronics > Laptops           │
└────────────────────────────────────┘
```

---

### Vendor Experience Flows

#### Vendor Dashboard

```
┌────────────────────────────────────────┐
│ [Logo] TechStore    [Orders] [Products]│
│                     [Profile] [Logout] │
├────────────────────────────────────────┤
│ Dashboard Overview        Oct 11, 2025 │
├─────────────┬─────────────┬────────────┤
│ Today Sales │ Pending     │ Total      │
│  $2,458.00  │ Orders: 8   │ Products   │
│  ↑ 12%      │             │    156     │
├─────────────┼─────────────┼────────────┤
│ This Month  │ Low Stock   │ Avg Rating │
│ $45,230.00  │ Items: 3    │  ⭐ 4.8   │
│  ↑ 25%      │ [View]      │  (1,245)   │
└─────────────┴─────────────┴────────────┘
│                                        │
│ Recent Orders                          │
│ ┌────────────────────────────────────┐ │
│ │ #ORD-123456  $1,299.99  Processing │ │
│ │ Dell XPS 15 x1          [View]     │ │
│ ├────────────────────────────────────┤ │
│ │ #ORD-123455    $899.99  Pending    │ │
│ │ HP Laptop x1            [View]     │ │
│ └────────────────────────────────────┘ │
│ [View All Orders]                      │
├────────────────────────────────────────┤
│ Sales Chart (Last 30 Days)             │
│ [Line Chart showing daily sales]       │
└────────────────────────────────────────┘
```

#### Product Management

```
┌────────────────────────────────────────┐
│ My Products                   [+ Add]  │
├────────────────────────────────────────┤
│ [Search products...]  [Filter ▼]       │
├────────────────────────────────────────┤
│ ┌─[IMG]──────────────────────────────┐ │
│ │ Dell XPS 15 Laptop                 │ │
│ │ SKU: DELL-XPS-15-001               │ │
│ │ Price: $1,299.99  Stock: 15        │ │
│ │ Status: ● Active  Rating: ⭐ 4.9  │ │
│ │ [Edit] [Duplicate] [Deactivate]    │ │
│ └────────────────────────────────────┘ │
│ ┌─[IMG]──────────────────────────────┐ │
│ │ HP Pavilion Laptop                 │ │
│ │ SKU: HP-PAV-001                    │ │
│ │ Price: $899.99  Stock: 3 ⚠️        │ │
│ │ Status: ● Active  Rating: ⭐ 4.6  │ │
│ │ [Edit] [Duplicate] [Deactivate]    │ │
│ └────────────────────────────────────┘ │
│                                        │
│ [Pagination: 1 2 3 ... 12]             │
└────────────────────────────────────────┘
```

#### Add/Edit Product

```
┌────────────────────────────────────────┐
│ Add New Product             [Save]     │
├────────────────────────────────────────┤
│ Basic Information                      │
│ Product Name: [_____________________]  │
│ Description:  [Rich Text Editor...]    │
│ Category:     [Select ▼]              │
│ Tags:         [laptop, electronics]    │
├────────────────────────────────────────┤
│ Pricing & Inventory                    │
│ Price:        [$________]              │
│ Compare Price:[$________] (optional)   │
│ SKU:          [_____________________]  │
│ Stock:        [_____]                  │
│ Low Stock:    [__5__] (alert at)       │
├────────────────────────────────────────┤
│ Product Images                         │
│ ┌────┐ ┌────┐ ┌────┐ [+ Upload]       │
│ │[1] │ │[2] │ │[3] │                  │
│ └────┘ └────┘ └────┘                  │
├────────────────────────────────────────┤
│ Variants (Optional)        [+ Add]     │
│ ☑ This product has variants            │
│ • Color: Red,  Size: Large  $1,299.99  │
│   SKU: DELL-XPS-15-RED-L   Stock: 10   │
│ • Color: Black, Size: Large $1,299.99  │
│   SKU: DELL-XPS-15-BLK-L   Stock: 5    │
├────────────────────────────────────────┤
│ Shipping                               │
│ Weight:  [____] kg                     │
│ Dimensions: L[__] x W[__] x H[__] cm   │
├────────────────────────────────────────┤
│ SEO                                    │
│ Meta Title: [_____________________]    │
│ Meta Desc:  [_____________________]    │
│ URL Slug:   [dell-xps-15-laptop]       │
├────────────────────────────────────────┤
│ [Cancel] [Save as Draft] [Publish]     │
└────────────────────────────────────────┘
```

#### Order Management (Vendor)

```
┌────────────────────────────────────────┐
│ Orders                                 │
├────────────────────────────────────────┤
│ [All] [Pending] [Processing] [Shipped] │
│ Search: [________] Date: [Oct 2025 ▼]  │
├────────────────────────────────────────┤
│ ┌────────────────────────────────────┐ │
│ │ Order #ORD-123456    Oct 11, 2025  │ │
│ │ Customer: John Doe                 │ │
│ │ Item: Dell XPS 15 x1    $1,299.99  │ │
│ │ Status: ● Processing               │ │
│ │ [View Details] [Mark as Shipped]   │ │
│ └────────────────────────────────────┘ │
│ ┌────────────────────────────────────┐ │
│ │ Order #ORD-123455    Oct 10, 2025  │ │
│ │ Customer: Jane Smith               │ │
│ │ Item: HP Pavilion x1      $899.99  │ │
│ │ Status: ○ Pending                  │ │
│ │ [View Details] [Process Order]     │ │
│ └────────────────────────────────────┘ │
└────────────────────────────────────────┘
```

---

### Admin Experience Flows

#### Admin Dashboard

```
┌────────────────────────────────────────┐
│ Admin Panel          [Settings] [Logout]│
├────────────────────────────────────────┤
│ Platform Overview         Oct 11, 2025 │
├─────────────┬─────────────┬────────────┤
│ Total GMV   │ Active      │ Pending    │
│ $2.5M       │ Vendors: 85 │ Vendors: 5 │
│ ↑ 18%       │ Users: 12K  │ [Review]   │
├─────────────┼─────────────┼────────────┤
│ Orders      │ Products    │ Disputes   │
│ Today: 156  │ Listed: 5K  │ Open: 2    │
│ Pending: 23 │ Pending: 8  │ [Resolve]  │
└─────────────┴─────────────┴────────────┘
│                                        │
│ Recent Activity                        │
│ • New vendor application: TechHub      │
│ • Dispute opened: Order #ORD-123400    │
│ • Product flagged: iPhone 15 Clone     │
│                                        │
│ Platform Health                        │
│ ✓ All systems operational              │
│ ✓ Payment gateway: connected           │
│ ⚠ Queue: 45 jobs pending              │
└────────────────────────────────────────┘
```

#### Vendor Approval Interface

```
┌────────────────────────────────────────┐
│ Vendor Applications                    │
├────────────────────────────────────────┤
│ Pending Approval (5)                   │
├────────────────────────────────────────┤
│ ┌────────────────────────────────────┐ │
│ │ TechHub Electronics                │ │
│ │ Applied: Oct 10, 2025              │ │
│ │ Business: TechHub LLC              │ │
│ │ Email: contact@techhub.com         │ │
│ │ Documents: ✓ Tax ID, ✓ License    │ │
│ │                                    │ │
│ │ [View Documents]                   │ │
│ │ [✓ Approve] [✗ Reject] [💬 Notes] │ │
│ └────────────────────────────────────┘ │
│ ┌────────────────────────────────────┐ │
│ │ Fashion Forward                    │ │
│ │ Applied: Oct 9, 2025               │ │
│ │ ...                                │ │
│ └────────────────────────────────────┘ │
└────────────────────────────────────────┘
```

#### Product Moderation

```
┌────────────────────────────────────────┐
│ Product Moderation                     │
├────────────────────────────────────────┤
│ [All] [Pending] [Flagged] [Reported]   │
├────────────────────────────────────────┤
│ ┌─[IMG]──────────────────────────────┐ │
│ │ iPhone 15 Clone - $99.99           │ │
│ │ Vendor: ShopX                      │ │
│ │ Status: 🚩 Flagged (counterfeit)   │ │
│ │ Reason: Trademark violation        │ │
│ │                                    │ │
│ │ [View Product] [Approve] [Remove]  │ │
│ │ [Contact Vendor]                   │ │
│ └────────────────────────────────────┘ │
└────────────────────────────────────────┘
```

---

### Wireframe Component Ideas

**Color Scheme Suggestions:**

- Primary: #3B82F6 (Blue) - CTAs, links
- Secondary: #10B981 (Green) - Success, in stock
- Warning: #F59E0B (Amber) - Low stock
- Danger: #EF4444 (Red) - Out of stock, errors
- Neutral: #6B7280 (Gray) - Text, borders

**Typography:**

- Headings: Inter, SF Pro, or Poppins (bold)
- Body: Inter, Roboto, or System Default
- Buttons: 14-16px, semi-bold, uppercase

**Components:**

- Cards with shadows for products
- Toast notifications for actions
- Modal overlays for quick views
- Breadcrumb navigation
- Sticky header on scroll
- Mobile-first responsive design
- Loading skeletons for better UX

**Mobile Considerations:**

- Bottom navigation for key actions (Home, Search, Cart, Profile)
- Swipeable product image galleries
- Collapsible filters
- Thumb-friendly button sizes (min 44x44px)
- Simplified checkout on mobile

---

## 16. MILESTONES & ROUGH TIMELINE / TASK BREAKDOWN

### Phase 1: Foundation & MVP Core (Weeks 1-6) - 6 weeks

**Sprint 1-2: Project Setup & Authentication (2 weeks)**

- Initialize Laravel project with Breeze/Sanctum
- Database design & migrations (all tables)
- User authentication (register, login, email verification, password reset)
- Role management (admin, vendor, buyer)
- Basic middleware & policies
- API authentication setup
- **Deliverable:** Users can register, login, and roles are enforced

**Sprint 3-4: Product Catalog & Browsing (2 weeks)**

- Category CRUD (admin)
- Product CRUD (vendors)
- Product images upload & storage
- Product search & filtering (Laravel Scout)
- Product listing pages
- Product detail pages
- Basic inventory tracking
- **Deliverable:** Vendors can add products, buyers can browse/search

**Sprint 5-6: Shopping Cart & Basic Checkout (2 weeks)**

- Shopping cart functionality
- Guest cart & cart merging on login
- Address management
- Basic checkout flow (address, review, place order)
- Order creation without payment
- Order tracking page
- Email notifications (order confirmation)
- **Deliverable:** MVP with basic e-commerce flow (no payment yet)

---

### Phase 2: Vendor Features & Payments (Weeks 7-11) - 5 weeks

**Sprint 7-8: Vendor Onboarding & Dashboard (2 weeks)**

- Vendor registration & application form
- Document upload
- Admin approval workflow
- Vendor profile management
- Vendor dashboard (sales metrics, recent orders)
- Vendor order management
- Order fulfillment workflow
- **Deliverable:** Vendors can register, get approved, manage their shop

**Sprint 9-10: Payment Integration (2 weeks)**

- Stripe integration
- Payment processing at checkout
- Split payment logic & commission calculation
- Payment success/failure handling
- Refund processing
- Vendor payout request system
- Admin payout approval
- **Deliverable:** Full payment flow with vendor payouts

**Sprint 11: Inventory & Stock Management (1 week)**

- Real-time stock updates on order
- Overselling prevention (locking)
- Low stock alerts
- Inventory logs
- Bulk stock updates (CSV)
- **Deliverable:** Robust inventory management

---

### Phase 3: Reviews, Ratings & Engagement (Weeks 12-14) - 3 weeks

**Sprint 12: Reviews & Ratings (1 week)**

- Product reviews & ratings
- Vendor reviews
- Review moderation (admin)
- Review replies (vendors)
- Review voting (helpful/not helpful)
- Rating aggregation
- **Deliverable:** Full review system

**Sprint 13: Wishlist & Messaging (1 week)**

- Wishlist functionality
- Buyer-vendor messaging system
- Message notifications
- **Deliverable:** Enhanced buyer engagement

**Sprint 14: Promotions & Coupons (1 week)**

- Coupon code system
- Discount application at checkout
- Vendor-specific coupons
- Platform-wide coupons
- Usage limits & validation
- **Deliverable:** Marketing & promotion capabilities

---

### Phase 4: Admin Panel & Advanced Features (Weeks 15-18) - 4 weeks

**Sprint 15-16: Admin Dashboard & Management (2 weeks)**

- Admin dashboard with platform metrics
- User management (suspend, activate, delete)
- Vendor management (approve, reject, suspend)
- Product moderation
- Order management (view all, refund, cancel)
- Dispute resolution interface
- Settings & configuration
- **Deliverable:** Complete admin control panel

**Sprint 17: Notifications & Real-Time Updates (1 week)**

- Email notification system (templates, queues)
- In-app notifications
- WebSocket setup (Laravel WebSockets/Pusher)
- Real-time order status updates
- Real-time dashboard metrics
- Notification preferences
- **Deliverable:** Comprehensive notification system

**Sprint 18: Analytics & Reporting (1 week)**

- Vendor analytics (sales, top products, revenue)
- Admin analytics (GMV, user metrics)
- Sales reports (exportable)
- Product performance reports
- Financial reports
- **Deliverable:** Business intelligence & reporting

---

### Phase 5: Optimization & Launch Prep (Weeks 19-22) - 4 weeks

**Sprint 19: Mobile Responsive & UX Polish (1 week)**

- Mobile-first responsive design refinement
- Touch-friendly interfaces
- Mobile navigation optimization
- Performance optimization for mobile
- Cross-browser testing
- **Deliverable:** Fully responsive platform

**Sprint 20: Performance & Caching (1 week)**

- Redis caching implementation
- Query optimization
- Database indexing review
- CDN setup for assets
- Image optimization
- API rate limiting
- **Deliverable:** Fast, scalable platform

**Sprint 21: Testing & Bug Fixes (1 week)**

- Unit tests for critical functions
- Feature tests for API endpoints
- Integration tests for checkout flow
- Security testing
- Bug fixes from testing
- **Deliverable:** Stable, tested platform

**Sprint 22: Deployment & Go-Live (1 week)**

- Production environment setup
- CI/CD pipeline configuration
- Database migration & seeding
- DNS & SSL setup
- Monitoring tools installation
- Launch & post-launch monitoring
- **Deliverable:** LIVE PLATFORM! 🚀

---

### Phase 6: Post-Launch & Iteration (Ongoing)

**Month 6+:**

- User feedback collection & implementation
- Performance monitoring & optimization
- Feature enhancements based on analytics
- Marketing integrations
- Mobile app development (optional)
- Advanced features from "Nice to Have" list

---

## 17. POSSIBLE EXTENSIONS / "NICE TO HAVE" FEATURES

### Enhanced User Experience

**Wishlist with Collections**

- Create multiple wishlists (e.g., "Gift Ideas", "Save for Later")
- Share wishlists publicly
- Move items between wishlists

**Product Comparison**

- Compare up to 4 products side-by-side
- Compare specs, prices, ratings
- Add to cart from comparison view

**Advanced Search & Filters**

- Visual search (upload image, find similar products)
- Voice search
- Saved searches
- Search filters history
- "Recently Viewed" products

**Live Chat Support**

- Real-time chat with vendors
- Chatbot for common questions
- Chat history
- File/image sharing in chat

**Product Q&A**

- Ask questions on product pages
- Vendor/other buyers answer
- Upvote helpful answers

---

### Internationalization & Localization

**Multi-Language Support**

- Language switcher
- Translate product listings
- RTL support (Arabic, Hebrew)
- Localized content
- Use Laravel localization
- Database: translatable fields (JSON) or separate tables

**Multi-Currency Support**

- Currency converter
- Display prices in user's currency
- Payment processing in multiple currencies
- Exchange rate updates (API integration)
- Currency-specific pricing per vendor

**Regional Settings**

- Timezone handling
- Date/time format preferences
- Unit system (metric/imperial)
- Phone number format validation

---

### Marketing & Growth

**Referral Program**

- Unique referral links
- Rewards for referrals (credits, discounts)
- Track referral conversions
- Leaderboard for top referrers

**Loyalty & Rewards Program**

- Points for purchases
- Tier-based benefits (Bronze, Silver, Gold)
- Redeem points for discounts
- Birthday rewards
- Anniversary bonuses

**Email Marketing**

- Newsletter campaigns
- Segmented email lists
- Abandoned cart emails
- Product recommendations
- Win-back campaigns
- Integration with Mailchimp/Sendinblue

**Social Media Integration**

- Share products on social media
- Social login (Facebook, Google, Twitter)
- Instagram shopping integration
- Social proof widgets

**Affiliate Program**

- Affiliate registration
- Unique tracking links
- Commission tracking
- Affiliate dashboard
- Payout management

---

### Advanced Vendor Features

**Subscription Products**

- Recurring billing
- Subscription management
- Pause/resume subscriptions
- Subscription tiers
- Cancel anytime

**Pre-Orders**

- Accept pre-orders for upcoming products
- Collect payment upfront or on release
- Pre-order countdown
- Notify when available

**Auction/Bidding System**

- Auction-style listings
- Bid management
- Auto-bid functionality
- Auction end notifications

**Vendor Tiers/Plans**

- Free, Basic, Premium, Enterprise plans
- Different commission rates per tier
- Feature limits (products, images, analytics)
- Subscription billing for vendors

**Vendor Collaboration**

- Multiple staff accounts per vendor
- Role-based permissions for staff
- Activity logs per staff member

**Bulk Operations**

- Bulk product edit
- Bulk price changes
- Bulk inventory updates
- Bulk product activation/deactivation

---

### Shipping & Logistics

**Advanced Shipping**

- Real-time carrier rates (USPS, FedEx, UPS)
- Shipping label generation API
- Package tracking integration
- Multiple package shipments
- Shipping insurance
- International customs forms

**Dropshipping Support**

- Connect to dropshipping suppliers
- Auto-fulfill orders
- Supplier inventory sync
- Blind shipping
- Supplier notifications

**Local Pickup**

- Store pickup option
- Multiple pickup locations
- Pickup ready notifications
- QR code for pickup verification

**Delivery Time Slots**

- Choose delivery date/time
- Schedule deliveries
- Delivery calendars per vendor

---

### Analytics & Intelligence

**Advanced Analytics Dashboard**

- Customer lifetime value (CLV)
- Cohort analysis
- Conversion funnel visualization
- A/B testing results
- Heatmaps & session recordings

**AI/ML Features**

- Product recommendations (collaborative filtering)
- Dynamic pricing suggestions
- Fraud detection
- Demand forecasting
- Churn prediction

**Business Intelligence**

- Custom report builder
- Scheduled reports (email delivery)
- Data visualization (charts, graphs)
- Export to Google Sheets/Excel
- API for external BI tools

---

### Technical Enhancements

**Progressive Web App (PWA)**

- Installable on mobile devices
- Offline functionality
- Push notifications
- App-like experience

**Mobile Apps (iOS/Android)**

- Native mobile apps
- React Native or Flutter
- Push notifications
- Mobile-specific features (camera for QR, location)

**Headless Commerce**

- Expose full API
- Separate frontend and backend
- Multiple frontend apps (web, mobile, kiosks)

**Blockchain Integration**

- Cryptocurrency payments (Bitcoin, Ethereum)
- NFT marketplace
- Supply chain transparency

**Voice Commerce**

- Alexa/Google Assistant skills
- Voice-activated shopping
- Voice search

---

### Compliance & Trust

**GDPR Compliance**

- Data export functionality
- Right to be forgotten
- Cookie consent management
- Privacy policy acceptance

**Accessibility (WCAG)**

- Screen reader support
- Keyboard navigation
- Color contrast compliance
- Alt text for images

**Age Verification**

- Age-restricted products
- ID verification integration
- Age gate for restricted categories

**Seller Verification Badges**

- Verified seller badge
- Trust scores
- Seller certifications
- Background checks

---

### Marketplace Enhancements

**Flash Sales / Daily Deals**

- Time-limited deals
- Countdown timers
- Limited quantity deals
- Lightning deals

**Bundles & Packages**

- Buy together discounts
- Bundle creation
- Package deals

**Gift Cards**

- Purchase gift cards
- Redeem gift cards
- Balance tracking
- Gift card expiration

**Product Customization**

- Custom engraving
- Product personalization
- Custom text/images
- Preview before purchase

**B2B Features**

- Wholesale pricing
- Bulk order requests
- Quote system
- Business accounts
- Net payment terms

---

## 18. SUGGESTED TECH STACK & LIBRARIES

### Backend Stack

**Core Framework**

- **Laravel 10.x / 11.x** (PHP 8.1+)
- **Database:** MySQL 8.0 or PostgreSQL 15
- **PHP Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

**Authentication & Authorization**

- **Laravel Sanctum** - API token authentication
- **Laravel Breeze** - Starter authentication scaffolding
- **Spatie Laravel Permission** - Role & permission management

**File Storage & Media**

- **AWS S3** / **DigitalOcean Spaces** / **CloudFlare R2**
- **Intervention Image** - Image manipulation
- **Spatie Laravel Media Library** - Advanced media management
- **Laravel Image Optimizer** - Image compression

**Search**

- **Laravel Scout** - Full-text search abstraction
- **Meilisearch** (Recommended) - Fast, typo-tolerant search
- **Algolia** - Hosted search (paid, excellent)
- **Elasticsearch** - Self-hosted, powerful (more complex)

**Queue & Jobs**

- **Redis** - Queue driver (recommended)
- **Laravel Horizon** - Queue monitoring dashboard
- **Supervisor** - Process monitor for queue workers

**Payments**

- **Stripe** - Primary payment gateway (Laravel Cashier)
- **PayPal** - Alternative payment (Srmklive/PayPal package)
- **Razorpay** / **PayStack** - Regional options

**Email Services**

- **Mailgun** - Transactional emails
- **AWS SES** - Cost-effective option
- **SendGrid** - Good deliverability
- **Postmark** - Fast transactional emails

**Real-Time Features**

- **Laravel WebSockets** - Self-hosted WebSocket server
- **Pusher** - Hosted WebSocket service (easiest)
- **Laravel Reverb** - Official Laravel WebSocket (Laravel 11+)
- **Socket.io** - Alternative WebSocket library

**Notifications**

- **Twilio** - SMS notifications
- **Vonage** (Nexmo) - SMS & Voice
- **Firebase Cloud Messaging** - Push notifications
- **OneSignal** - Web push notifications

**Additional Packages**

- **Spatie Laravel Activitylog** - Activity logging
- **Laravel Backup** - Database & file backups
- **Laravel Debugbar** - Development debugging
- **Laravel Telescope** - Application debugging
- **Barryvdh Laravel DomPDF** - PDF generation
- **Maatwebsite Excel** - Excel import/export
- **Laravel CSV** - CSV handling

---

### Frontend Stack Options

**Option 1: Laravel Blade + Alpine.js + Livewire (Simplest)**

- **Pros:** Simple, less JS complexity, faster development
- **Cons:** Less interactive, traditional page loads
- **Best for:** MVPs, simpler marketplaces, teams comfortable with Blade
- **UI Library:** Tailwind CSS + Tailwind UI / DaisyUI
- **Packages:** Livewire, Alpine.js, Tailwind CSS

**Option 2: Laravel + Inertia.js + Vue 3 (Recommended)**

- **Pros:** SPA-like experience, reactive, modern, good balance
- **Cons:** Learning curve for Inertia
- **Best for:** Modern marketplaces, good UX, single codebase
- **UI Library:** Tailwind CSS + Headless UI / PrimeVue
- **Packages:** Inertia.js, Vue 3, Vue Router, Pinia (state)

**Option 3: Laravel + Inertia.js + React (Alternative)**

- **Pros:** Huge ecosystem, React experience useful elsewhere
- **Cons:** Similar to Vue option
- **Best for:** Teams with React experience
- **UI Library:** Tailwind CSS + Headless UI / shadcn/ui
- **Packages:** Inertia.js, React, React Router, Zustand (state)

**Option 4: Laravel API + Vue 3 / React (Decoupled)**

- **Pros:** Maximum flexibility, scalable, can build mobile apps easily
- **Cons:** More complex deployment, CORS handling
- **Best for:** Large platforms, multiple frontends (web + mobile)
- **Frontend:** Vue 3 + Vite or Next.js (React)
- **API:** Laravel as pure API with Sanctum

**Recommended: Option 2 (Inertia + Vue)**

---

### UI Component Libraries

**For Tailwind CSS:**

- **Tailwind UI** - Premium, official components ($$$)
- **DaisyUI** - Free component library
- **Flowbite** - Open-source components
- **Headless UI** - Unstyled, accessible components
- **Preline UI** - Open-source components

**For Vue:**

- **PrimeVue** - Rich component set
- **Vuetify** - Material Design components
- **Element Plus** - Enterprise UI components
- **Quasar** - Full-featured framework

**For React:**

- **shadcn/ui** - Copy-paste components (Tailwind)
- **Chakra UI** - Accessible components
- **Material-UI (MUI)** - Material Design
- **Ant Design** - Enterprise components

---

### DevOps & Infrastructure

**Hosting Options**

- **AWS** - Scalable, EC2 + RDS + S3 + CloudFront
- **DigitalOcean** - Simpler, App Platform or Droplets
- **Laravel Forge** - Server management (paid)
- **Ploi** - Alternative to Forge
- **Cloudways** - Managed cloud hosting
- **Laravel Vapor** - Serverless deployment on AWS

**CDN**

- **CloudFlare** - Free tier, DDoS protection, CDN
- **AWS CloudFront** - Integrates with S3
- **BunnyCDN** - Affordable, fast

**CI/CD**

- **GitHub Actions** - Built into GitHub
- **GitLab CI/CD** - Built into GitLab
- **Bitbucket Pipelines** - For Bitbucket users
- **Laravel Envoyer** - Zero-downtime deployment (paid)

**Monitoring & Error Tracking**

- **Sentry** - Error tracking
- **New Relic** - APM & monitoring
- **Datadog** - Full-stack monitoring
- **Laravel Telescope** - Local debugging
- **Bugsnag** - Error monitoring

**Database**

- **MySQL 8.0** - Most common
- **PostgreSQL 15** - More features, better performance
- **MariaDB** - MySQL alternative
- **PlanetScale** - Serverless MySQL (paid)

**Caching**

- **Redis** - Recommended for cache + queues
- **Memcached** - Alternative caching
- **Varnish** - HTTP caching

---

## 19. TESTING & DEPLOYMENT

### Testing Strategy

#### Unit Tests

**What to Test:**

- Model methods & relationships
- Business logic in services/classes
- Helper functions
- Validation rules
- Calculations (pricing, commission, tax)

**Tools:**

- **PHPUnit** - Built into Laravel
- **Pest** - Modern testing framework (alternative to PHPUnit)

**Example:**

```php
test('product price with discount calculates correctly', function () {
    $product = Product::factory()->create([
        'price' => 100,
        'discount' => 10
    ]);

    expect($product->final_price)->toBe(90);
});
```

---

#### Feature Tests (API/Integration Tests)

**What to Test:**

- API endpoints (CRUD operations)
- Authentication flows
- Authorization (policies)
- Complex workflows (checkout, order placement)
- Payment processing (use test mode)

**Coverage:**

- User registration & login
- Product creation & listing
- Cart operations
- Checkout flow
- Order placement
- Refund processing
- Vendor approval workflow

---

#### Browser Tests (E2E)

**What to Test:**

- Critical user journeys
- Checkout flow (guest & authenticated)
- Product search & filtering
- Vendor dashboard operations
- Admin panel workflows

**Tools:**

- **Laravel Dusk** - Browser automation (Chromium)
- **Cypress** - Modern E2E testing (JS)
- **Playwright** - Cross-browser E2E

**Example Critical Paths:**

- Browse → Add to Cart → Checkout → Payment → Confirmation
- Vendor: Add Product → Receive Order → Fulfill Order
- Admin: Approve Vendor → Moderate Product

---

#### Testing Best Practices

- **Test Coverage Goal:** 70-80% for critical features
- **CI/CD Integration:** Run tests on every push
- **Test Database:** Use separate test database
- **Factory & Seeders:** Use factories for test data
- **Mock External Services:** Mock Stripe, email services
- **Test in Stages:** Unit → Feature → Browser
- **Performance Tests:** Load test critical endpoints (k6, JMeter)

---

### Deployment Process

#### Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Database migrations ready
- [ ] Environment variables documented
- [ ] Assets compiled (npm run build)
- [ ] Cache cleared locally
- [ ] Code reviewed & merged
- [ ] Backup existing production database
- [ ] Maintenance mode plan

---

#### Server Requirements

**Minimum:**

- PHP 8.1+
- MySQL 8.0 or PostgreSQL 15
- Nginx or Apache
- Redis
- Composer
- Node.js & NPM (for asset compilation)
- SSL certificate

**Recommended Production:**

- 2+ CPU cores
- 4GB+ RAM
- 50GB+ SSD storage
- Separate Redis instance
- Separate database server (for scale)
- Load balancer (for multiple servers)

---

#### Deployment Steps

**Manual Deployment:**

1. Pull latest code: `git pull origin main`
2. Install dependencies: `composer install --optimize-autoloader --no-dev`
3. Install JS dependencies: `npm install && npm run build`
4. Run migrations: `php artisan migrate --force`
5. Clear & cache:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```
6. Restart queue workers: `php artisan queue:restart`
7. Test critical features
8. Exit maintenance mode

**Automated Deployment (CI/CD):**

**GitHub Actions Example:**

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.1"
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
      - name: Deploy to Server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/html
            git pull
            composer install --no-dev
            php artisan migrate --force
            php artisan config:cache
            php artisan queue:restart
```

---

#### Environment Configuration

**.env Production Settings:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=strong-password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=your-redis-host
REDIS_PASSWORD=redis-password

MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your-mailgun-secret

AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-s3-bucket

STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
```

---

#### Monitoring & Maintenance

**Application Monitoring:**

- **Uptime Monitoring:** UptimeRobot, Pingdom
- **Error Tracking:** Sentry (real-time error alerts)
- **APM:** New Relic, Datadog (performance monitoring)
- **Log Management:** Papertrail, Loggly
- **Queue Monitoring:** Laravel Horizon dashboard

**What to Monitor:**

- Server CPU, memory, disk usage
- Database connections & query performance
- Redis memory usage
- Queue depth & failed jobs
- API response times
- Error rates & types
- Payment success/failure rates
- User activity metrics

**Alerts to Set:**

- Server down (uptime < 99%)
- High error rate (> 5%)
- Queue depth > 1000 jobs
- Disk space < 20%
- Database connections > 80%
- Failed payment rate > 2%

---

#### Backup Strategy

**What to Backup:**

- Database (daily automated)
- User uploads (S3 versioning)
- Environment configuration
- Code repository (Git)

**Backup Schedule:**

- **Database:** Daily full backup, retain 30 days
- **Files:** Continuous S3 replication
- **Before Deployment:** Manual backup

**Tools:**

- **Laravel Backup Package** (Spatie)
- **Database:** Automated RDS snapshots (AWS) or cron mysqldump
- **S3:** Enable versioning & lifecycle policies

---

#### Security Best Practices

**Production Security:**

- Always use HTTPS (Let's Encrypt, CloudFlare)
- Set `APP_DEBUG=false` in production
- Use strong, unique passwords
- Restrict database access (firewall rules)
- Keep Laravel & dependencies updated
- Enable CSRF protection
- Implement rate limiting
- Use `.env` for secrets (never commit)
- Set proper file permissions (755 directories, 644 files)
- Regular security audits
- Monitor for vulnerabilities (Snyk, Dependabot)

**Regular Maintenance:**

- Update Laravel & packages monthly
- Review and optimize slow queries
- Clean up old logs & files
- Review failed jobs queue
- Update SSL certificates (auto-renew)
- Database optimization (ANALYZE, OPTIMIZE)
- Clear old sessions & cache periodically

---

This specification provides a comprehensive foundation for your multi-vendor e-commerce platform. You can use it as a reference throughout development, breaking down each phase into sprints and tickets in your project management tool.
