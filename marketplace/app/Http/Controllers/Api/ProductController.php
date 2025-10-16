<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with([
            'vendor',
            'category',
            'images',
            // 'variants'
            // 'tags' // TODO: Add back when ProductTag model is created
        ])->active();

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by vendor
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by stock status
        if ($request->has('in_stock') && $request->in_stock) {
            $query->inStock();
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|json',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        DB::beginTransaction();
        try {
            $product = Product::create($validated);

            // Handle product images if provided
            if ($request->has('images')) {
                foreach ($request->images as $index => $imageUrl) {
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'is_primary' => $index === 0,
                        'sort_order' => $index
                    ]);
                }
            }

            // Handle product variants if provided
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    $product->variants()->create($variant);
                }
            }

            // Handle tags if provided
            // TODO: Add back when ProductTag model is created
            // if ($request->has('tags')) {
            //     $product->tags()->sync($request->tags);
            // }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['images', 'variants', 'category', 'vendor'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with([
            'vendor.user',
            'category',
            'images',
            'variants',
            // 'tags', // TODO: Add back when ProductTag model is created
            'reviews.user'
        ])->findOrFail($id);

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified product
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $id,
            'description' => 'sometimes|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'price' => 'sometimes|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|json',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product->update($validated);

            // Update images if provided
            if ($request->has('images')) {
                $product->images()->delete();
                foreach ($request->images as $index => $imageUrl) {
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'is_primary' => $index === 0,
                        'sort_order' => $index
                    ]);
                }
            }

            // Update variants if provided
            if ($request->has('variants')) {
                $product->variants()->delete();
                foreach ($request->variants as $variant) {
                    $product->variants()->create($variant);
                }
            }

            // Update tags if provided
            // TODO: Add back when ProductTag model is created
            // if ($request->has('tags')) {
            //     $product->tags()->sync($request->tags);
            // }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->fresh()->load(['images', 'variants', 'category', 'vendor'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product (soft delete)
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Restore a soft-deleted product
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully',
            'data' => $product
        ]);
    }

    /**
     * Get related products
     *
     * @param string $id
     * @return JsonResponse
     */
    public function related(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
        $relatedProducts = Product::with(['images', 'vendor'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedProducts
        ]);
    }
}
