<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::with(['parent', 'children']);

        // Filter by parent (get root categories or children of specific parent)
        if ($request->has('parent_id')) {
            if ($request->parent_id === 'null' || $request->parent_id === '') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('status', $request->boolean('is_active') ? 'active' : 'inactive');
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Get tree structure (all categories with nested children)
        if ($request->has('tree') && $request->boolean('tree')) {
            $categories = Category::whereNull('parent_id')
                ->with('children')
                ->where('status', 'active')
                ->orderBy('order')
                ->get();
        } else {
            // Regular paginated list
            $perPage = $request->get('per_page', 20);
            $categories = $query->orderBy('order')->paginate($perPage);
        }

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category->load('parent', 'children')
        ], 201);
    }

    /**
     * Display the specified category
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::with([
            'parent',
            'children',
            'products' => function($query) {
                $query->active()->limit(12);
            }
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified category
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Prevent category from being its own parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
            return response()->json([
                'success' => false,
                'message' => 'A category cannot be its own parent'
            ], 422);
        }

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category->fresh()->load('parent', 'children')
        ]);
    }

    /**
     * Remove the specified category (soft delete)
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with products. Please reassign or delete products first.'
            ], 422);
        }

        // Check if category has children
        if ($category->children()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with subcategories. Please delete subcategories first.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Get products in a category
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function products(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        
        $query = $category->products()
            ->with(['images', 'vendor'])
            ->active();

        // Apply filters
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->has('in_stock') && $request->boolean('in_stock')) {
            $query->inStock();
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'products' => $products
            ]
        ]);
    }
}
