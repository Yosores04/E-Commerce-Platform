<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get user's cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $cart = Cart::with([
            'items.product.images',
            'items.product.vendor',
            'items.variant'
        ])->firstOrCreate([
            'user_id' => $user->id
        ]);

        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'summary' => [
                    'items_count' => $cart->items->count(),
                    'total_quantity' => $cart->items->sum('quantity'),
                    'subtotal' => $subtotal,
                    'tax' => 0, // Calculate based on tax rules
                    'shipping' => 0, // Calculate based on shipping method
                    'total' => $subtotal
                ]
            ]
        ]);
    }

    /**
     * Add item to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available'
            ], 422);
        }

        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Get or create cart
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id
            ]);

            // Check if item already exists in cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->where('variant_id', $validated['variant_id'] ?? null)
                ->first();

            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem->quantity + $validated['quantity'];
                
                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Maximum available: ' . $product->stock_quantity
                    ], 422);
                }

                $existingItem->update([
                    'quantity' => $newQuantity
                ]);

                $cartItem = $existingItem;
            } else {
                // Create new cart item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product_id'],
                    'variant_id' => $validated['variant_id'] ?? null,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => $cartItem->load('product', 'variant')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     *
     * @param Request $request
     * @param string $itemId
     * @return JsonResponse
     */
    public function updateItem(Request $request, string $itemId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $user = $request->user();
        $cartItem = CartItem::whereHas('cart', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($itemId);

        // If quantity is 0, remove the item
        if ($validated['quantity'] === 0) {
            $cartItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        }

        // Check stock availability
        $product = $cartItem->product;
        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
            ], 422);
        }

        $cartItem->update([
            'quantity' => $validated['quantity']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => $cartItem->fresh()->load('product', 'variant')
        ]);
    }

    /**
     * Remove item from cart
     *
     * @param Request $request
     * @param string $itemId
     * @return JsonResponse
     */
    public function removeItem(Request $request, string $itemId): JsonResponse
    {
        $user = $request->user();
        
        $cartItem = CartItem::whereHas('cart', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($itemId);

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear entire cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Apply coupon to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string'
        ]);

        // TODO: Implement coupon validation and application logic
        // - Check if coupon exists and is valid
        // - Check usage limits
        // - Calculate discount
        // - Update cart with coupon

        return response()->json([
            'success' => false,
            'message' => 'Coupon functionality will be implemented in next sprint'
        ], 501);
    }

    /**
     * Get cart summary for checkout
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $cart = Cart::with([
            'items.product.vendor',
            'items.variant'
        ])->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 422);
        }

        // Group items by vendor for multi-vendor checkout
        $itemsByVendor = $cart->items->groupBy('product.vendor_id');

        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Calculate tax (example: 10%)
        $tax = $subtotal * 0.10;

        // Shipping will be calculated based on selected method
        $shipping = 0;

        $total = $subtotal + $tax + $shipping;

        return response()->json([
            'success' => true,
            'data' => [
                'items_by_vendor' => $itemsByVendor->map(function($items, $vendorId) {
                    return [
                        'vendor_id' => $vendorId,
                        'vendor_name' => $items->first()->product->vendor->business_name,
                        'items' => $items,
                        'subtotal' => $items->sum(function($item) {
                            return $item->price * $item->quantity;
                        })
                    ];
                })->values(),
                'summary' => [
                    'items_count' => $cart->items->count(),
                    'total_quantity' => $cart->items->sum('quantity'),
                    'subtotal' => round($subtotal, 2),
                    'tax' => round($tax, 2),
                    'shipping' => round($shipping, 2),
                    'discount' => 0,
                    'total' => round($total, 2)
                ]
            ]
        ]);
    }
}
