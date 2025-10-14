<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        // Total Revenue
        $totalRevenue = Order::whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount');

        // Total Orders
        $totalOrders = Order::count();

        // Total Products
        $totalProducts = Product::count();

        // Total Users
        $totalUsers = User::count();

        // Order Status Breakdown
        $orderStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Revenue by Month (last 12 months)
        $revenueByMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Order::whereIn('status', ['delivered', 'completed'])
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
            $revenueByMonth[] = [
                'month' => $month->format('M'),
                'year' => $month->format('Y'),
                'revenue' => (float) $revenue
            ];
        }

        // Recent Orders (last 10)
        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer' => $order->user->name ?? 'Guest',
                    'total' => (float) $order->total_amount,
                    'status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d H:i:s'),
                    'items_count' => $order->items->count()
                ];
            });

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'stock' => $product->stock,
                    'price' => (float) $product->price
                ];
            });

        // Top Selling Products (by order count)
        $topProducts = Product::withCount('orderItems')
            ->having('order_items_count', '>', 0)
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sales' => $product->order_items_count,
                    'revenue' => (float) $product->orderItems()->sum(DB::raw('quantity * price'))
                ];
            });

        // New Users (last 7 days)
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Pending Vendors
        $pendingVendors = Vendor::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalRevenue' => (float) $totalRevenue,
                'totalOrders' => $totalOrders,
                'totalProducts' => $totalProducts,
                'totalUsers' => $totalUsers,
                'orderStatus' => $orderStatus,
                'revenueByMonth' => $revenueByMonth,
                'recentOrders' => $recentOrders,
                'lowStockProducts' => $lowStockProducts,
                'topProducts' => $topProducts,
                'newUsers' => $newUsers,
                'pendingVendors' => $pendingVendors,
                'stats' => [
                    'revenue' => [
                        'current' => (float) $totalRevenue,
                        'previousMonth' => $this->getPreviousMonthRevenue(),
                        'percentageChange' => $this->getRevenuePercentageChange()
                    ],
                    'orders' => [
                        'current' => $totalOrders,
                        'previousMonth' => $this->getPreviousMonthOrders(),
                        'percentageChange' => $this->getOrdersPercentageChange()
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get previous month revenue
     */
    private function getPreviousMonthRevenue(): float
    {
        return (float) Order::whereIn('status', ['delivered', 'completed'])
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
    }

    /**
     * Get revenue percentage change
     */
    private function getRevenuePercentageChange(): float
    {
        $current = Order::whereIn('status', ['delivered', 'completed'])
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        $previous = $this->getPreviousMonthRevenue();

        if ($previous == 0) {
            return 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get previous month orders
     */
    private function getPreviousMonthOrders(): int
    {
        return Order::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
    }

    /**
     * Get orders percentage change
     */
    private function getOrdersPercentageChange(): float
    {
        $current = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $previous = $this->getPreviousMonthOrders();

        if ($previous == 0) {
            return 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
