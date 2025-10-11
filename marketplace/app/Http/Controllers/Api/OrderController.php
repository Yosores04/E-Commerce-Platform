<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Order::with([
            'items.product.images',
            'payments',
            'shippingAddress',
            'billingAddress'
        ])->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Create a new order from cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,bank_transfer,cash_on_delivery',
            'shipping_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();

        // Get user's cart
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 422);
        }

        // Validate addresses belong to user
        $shippingAddress = Address::where('id', $validated['shipping_address_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $billingAddress = Address::where('id', $validated['billing_address_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Check stock availability for all items
            foreach ($cart->items as $item) {
                if ($item->product->stock_quantity < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$item->product->name}"
                    ], 422);
                }
            }

            // Calculate totals
            $subtotal = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            $tax = $subtotal * 0.10; // 10% tax
            $shippingCost = 10.00; // Flat rate for now
            $total = $subtotal + $tax + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_cost' => $shippingCost,
                'discount_amount' => 0,
                'total_amount' => $total,
                'shipping_address_id' => $validated['shipping_address_id'],
                'billing_address_id' => $validated['billing_address_id'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items and update product stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'vendor_id' => $item->product->vendor_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Decrease product stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load(['items.product', 'shippingAddress', 'billingAddress'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        
        $order = Order::with([
            'items.product.images',
            'items.product.vendor',
            'items.variant',
            'payments',
            'shippingAddress',
            'billingAddress',
            'refunds'
        ])->where('user_id', $user->id)
          ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Cancel an order
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel order with status: ' . $order->status
            ], 422);
        }

        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Restore product stock
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status (for vendors/admins)
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'status' => $validated['status'],
            'shipped_at' => $validated['status'] === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $validated['status'] === 'delivered' ? now() : $order->delivered_at,
            'cancelled_at' => $validated['status'] === 'cancelled' ? now() : $order->cancelled_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order->fresh()
        ]);
    }

    /**
     * Get order tracking information
     *
     * @param string $orderNumber
     * @return JsonResponse
     */
    public function track(string $orderNumber): JsonResponse
    {
        $order = Order::with([
            'items.product',
            'shippingAddress'
        ])->where('order_number', $orderNumber)->firstOrFail();

        $timeline = [
            [
                'status' => 'pending',
                'label' => 'Order Placed',
                'completed' => true,
                'timestamp' => $order->created_at,
            ],
            [
                'status' => 'processing',
                'label' => 'Processing',
                'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                'timestamp' => null,
            ],
            [
                'status' => 'shipped',
                'label' => 'Shipped',
                'completed' => in_array($order->status, ['shipped', 'delivered']),
                'timestamp' => $order->shipped_at,
            ],
            [
                'status' => 'delivered',
                'label' => 'Delivered',
                'completed' => $order->status === 'delivered',
                'timestamp' => $order->delivered_at,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'timeline' => $timeline
            ]
        ]);
    }

    /**
     * Request refund for an order
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function requestRefund(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'items' => 'nullable|array',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        // Check if order is eligible for refund
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only delivered orders can be refunded'
            ], 422);
        }

        // TODO: Implement full refund logic
        // - Create refund request
        // - Notify vendor/admin
        // - Handle partial refunds

        return response()->json([
            'success' => false,
            'message' => 'Refund functionality will be implemented in next sprint'
        ], 501);
    }
}
