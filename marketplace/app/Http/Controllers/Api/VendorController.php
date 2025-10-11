<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vendor::with(['user', 'products']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->has('is_approved') && $request->boolean('is_approved')) {
            $query->where('status', 'approved');
        }

        // Search by business name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $vendors = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $vendors
        ]);
    }

    /**
     * Register a new vendor
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:vendors,user_id',
            'business_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:vendors,slug',
            'description' => 'nullable|string',
            'business_email' => 'required|email|unique:vendors,business_email',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'required|string',
            'business_city' => 'required|string|max:100',
            'business_state' => 'nullable|string|max:100',
            'business_country' => 'required|string|max:100',
            'business_postal_code' => 'required|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'logo' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['business_name']);
        }

        DB::beginTransaction();
        try {
            $vendor = Vendor::create([
                ...$validated,
                'status' => 'pending', // Requires admin approval
            ]);

            // Assign vendor role to user
            $user = User::findOrFail($validated['user_id']);
            $user->assignRole('vendor');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor registration submitted successfully. Awaiting admin approval.',
                'data' => $vendor->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to register vendor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vendor
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $vendor = Vendor::with([
            'user',
            'products' => function($query) {
                $query->active()->latest()->limit(12);
            },
            'products.images'
        ])->findOrFail($id);

        // Get vendor statistics
        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->active()->count(),
            'total_orders' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'total_revenue' => $vendor->orders()->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'vendor' => $vendor,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * Update vendor profile
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:vendors,slug,' . $id,
            'description' => 'nullable|string',
            'business_email' => 'sometimes|email|unique:vendors,business_email,' . $id,
            'business_phone' => 'sometimes|string|max:20',
            'business_address' => 'sometimes|string',
            'business_city' => 'sometimes|string|max:100',
            'business_state' => 'nullable|string|max:100',
            'business_country' => 'sometimes|string|max:100',
            'business_postal_code' => 'sometimes|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'logo' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:255',
        ]);

        $vendor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vendor profile updated successfully',
            'data' => $vendor->fresh()->load('user')
        ]);
    }

    /**
     * Approve vendor (admin only)
     *
     * @param string $id
     * @return JsonResponse
     */
    public function approve(string $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        if ($vendor->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Vendor is already approved'
            ], 422);
        }

        $vendor->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        // TODO: Send approval notification email to vendor

        return response()->json([
            'success' => true,
            'message' => 'Vendor approved successfully',
            'data' => $vendor
        ]);
    }

    /**
     * Reject vendor application (admin only)
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function reject(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'status' => 'rejected',
        ]);

        // TODO: Send rejection notification email with reason

        return response()->json([
            'success' => true,
            'message' => 'Vendor application rejected',
            'data' => $vendor
        ]);
    }

    /**
     * Suspend vendor (admin only)
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function suspend(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'status' => 'suspended',
        ]);

        // TODO: Send suspension notification email

        return response()->json([
            'success' => true,
            'message' => 'Vendor suspended successfully',
            'data' => $vendor
        ]);
    }

    /**
     * Get vendor dashboard statistics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        // Get date range for stats
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->active()->count(),
            'out_of_stock' => $vendor->products()->where('stock_quantity', 0)->count(),
            'total_orders' => $vendor->orders()->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'processing_orders' => $vendor->orders()->where('status', 'processing')->count(),
            'completed_orders' => $vendor->orders()->where('status', 'delivered')->count(),
            'total_revenue' => $vendor->orders()
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
            'pending_payouts' => $vendor->payouts()->where('status', 'pending')->sum('amount'),
        ];

        // Recent orders
        $recentOrders = $vendor->orders()
            ->with(['user', 'items.product'])
            ->latest()
            ->limit(10)
            ->get();

        // Top selling products
        $topProducts = $vendor->products()
            ->withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'vendor' => $vendor,
                'stats' => $stats,
                'recent_orders' => $recentOrders,
                'top_products' => $topProducts,
            ]
        ]);
    }

    /**
     * Get vendor's products
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function products(Request $request, string $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        
        $query = $vendor->products()->with(['images', 'category']);

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by stock status
        if ($request->has('in_stock') && $request->boolean('in_stock')) {
            $query->inStock();
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'vendor' => $vendor,
                'products' => $products
            ]
        ]);
    }

    /**
     * Soft delete vendor
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        // Check if vendor has active orders
        $activeOrders = $vendor->orders()
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();

        if ($activeOrders > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete vendor with active orders. Please complete or cancel all orders first.'
            ], 422);
        }

        $vendor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vendor deleted successfully'
        ]);
    }
}
