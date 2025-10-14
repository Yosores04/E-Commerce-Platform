<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Password Reset (TODO: Implement in next sprint)
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Public Product Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // List products with filters
    Route::get('/{id}', [ProductController::class, 'show']); // Product details
    Route::get('/{id}/related', [ProductController::class, 'related']); // Related products
});

// Public Category Routes
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); // List categories
    Route::get('/{id}', [CategoryController::class, 'show']); // Category details
    Route::get('/{id}/products', [CategoryController::class, 'products']); // Products in category
});

// Public Vendor Routes
Route::prefix('vendors')->group(function () {
    Route::get('/', [VendorController::class, 'index']); // List vendors
    Route::get('/{id}', [VendorController::class, 'show']); // Vendor details
    Route::get('/{id}/products', [VendorController::class, 'products']); // Vendor's products
});

// Order Tracking (Public - by order number)
Route::get('/orders/track/{orderNumber}', [OrderController::class, 'track']);

// ============================================
// PROTECTED ROUTES (Authentication Required)
// ============================================

Route::middleware('auth:sanctum')->group(function () {
    
    // ========================================
    // AUTH & PROFILE ROUTES
    // ========================================
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    // ========================================
    // CUSTOMER ROUTES
    // ========================================
    
    // Shopping Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']); // Get cart
        Route::post('/items', [CartController::class, 'addItem']); // Add item
        Route::put('/items/{itemId}', [CartController::class, 'updateItem']); // Update quantity
        Route::delete('/items/{itemId}', [CartController::class, 'removeItem']); // Remove item
        Route::delete('/clear', [CartController::class, 'clear']); // Clear cart
        Route::get('/summary', [CartController::class, 'summary']); // Cart summary for checkout
        Route::post('/coupon', [CartController::class, 'applyCoupon']); // Apply coupon
    });

    // Order Routes (Customer)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']); // List user orders
        Route::post('/', [OrderController::class, 'store']); // Create order from cart
        Route::get('/{id}', [OrderController::class, 'show']); // Order details
        Route::post('/{id}/cancel', [OrderController::class, 'cancel']); // Cancel order
        Route::post('/{id}/refund', [OrderController::class, 'requestRefund']); // Request refund
    });

    // ========================================
    // IMAGE UPLOAD ROUTES
    // ========================================
    
    Route::prefix('upload')->group(function () {
        // User Avatar
        Route::post('/avatar', [ImageUploadController::class, 'uploadAvatar']);
        
        // Product Images (Vendor/Admin)
        Route::post('/product-image', [ImageUploadController::class, 'uploadProductImage']);
        Route::post('/product-images', [ImageUploadController::class, 'uploadProductImages']);
        
        // Vendor Assets (Vendor/Admin)
        Route::post('/vendor-logo', [ImageUploadController::class, 'uploadVendorLogo']);
        Route::post('/vendor-banner', [ImageUploadController::class, 'uploadVendorBanner']);
        
        // Category Images (Admin)
        Route::post('/category-image', [ImageUploadController::class, 'uploadCategoryImage']);
        
        // Review Images (Customer)
        Route::post('/review-image', [ImageUploadController::class, 'uploadReviewImage']);
        
        // Delete Image
        Route::delete('/image', [ImageUploadController::class, 'deleteImage']);
    });

    // ========================================
    // VENDOR ROUTES
    // ========================================
    
    Route::middleware(['role:vendor'])->prefix('vendor')->group(function () {
        
        // Vendor Registration (for users to become vendors)
        Route::post('/register', [VendorController::class, 'store']);
        
        // Vendor Dashboard
        Route::get('/dashboard', [VendorController::class, 'dashboard']);
        
        // Vendor Profile Management
        Route::get('/profile', function(Request $request) {
            return $request->user()->vendor->load('user');
        });
        Route::put('/profile', function(Request $request) {
            $vendor = $request->user()->vendor;
            return app(VendorController::class)->update($request, $vendor->id);
        });
        
        // Vendor's Product Management
        Route::prefix('products')->group(function () {
            Route::get('/', function(Request $request) {
                $vendor = $request->user()->vendor;
                return app(VendorController::class)->products($request, $vendor->id);
            });
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
            Route::post('/{id}/restore', [ProductController::class, 'restore']);
        });
        
        // Vendor's Order Management
        Route::prefix('orders')->group(function () {
            Route::get('/', function(Request $request) {
                $vendor = $request->user()->vendor;
                return Order::with(['user', 'items.product'])
                    ->whereHas('items', function($query) use ($vendor) {
                        $query->where('vendor_id', $vendor->id);
                    })
                    ->paginate(15);
            });
            Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
        });
    });

    // ========================================
    // ADMIN ROUTES
    // ========================================
    
    Route::middleware(['role:admin|super-admin'])->prefix('admin')->group(function () {
        
        // Dashboard Statistics
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        
        // Category Management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        
        // Vendor Management
        Route::prefix('vendors')->group(function () {
            Route::get('/', [VendorController::class, 'index']);
            Route::get('/{id}', [VendorController::class, 'show']);
            Route::post('/{id}/approve', [VendorController::class, 'approve']);
            Route::post('/{id}/reject', [VendorController::class, 'reject']);
            Route::post('/{id}/suspend', [VendorController::class, 'suspend']);
            Route::delete('/{id}', [VendorController::class, 'destroy']);
        });
        
        // Product Management (Admin can manage all products)
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
            Route::post('/{id}/restore', [ProductController::class, 'restore']);
        });
        
        // Order Management (Admin can manage all orders)
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
        });
        
        // User Management (TODO: Implement in next sprint)
        // Route::prefix('users')->group(function () {
        //     Route::get('/', [UserController::class, 'index']);
        //     Route::get('/{id}', [UserController::class, 'show']);
        //     Route::put('/{id}', [UserController::class, 'update']);
        //     Route::delete('/{id}', [UserController::class, 'destroy']);
        // });
    });
});

// ============================================
// FALLBACK ROUTE
// ============================================

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found'
    ], 404);
});
