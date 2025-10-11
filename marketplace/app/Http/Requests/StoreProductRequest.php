<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by route middleware
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gte:price',
            'cost_price' => 'nullable|numeric|min:0|lte:price',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|json',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            
            // Product images
            'images' => 'nullable|array|max:10',
            'images.*' => 'string|url',
            
            // Product variants
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            
            // Product tags
            'tags' => 'nullable|array',
            'tags.*' => 'exists:product_tags,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Vendor is required',
            'vendor_id.exists' => 'Selected vendor does not exist',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'name.required' => 'Product name is required',
            'description.required' => 'Product description is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'This SKU already exists',
            'price.required' => 'Price is required',
            'price.min' => 'Price must be at least 0',
            'compare_price.gte' => 'Compare price must be greater than or equal to price',
            'cost_price.lte' => 'Cost price must be less than or equal to price',
            'stock_quantity.required' => 'Stock quantity is required',
            'images.max' => 'Maximum 10 images allowed',
        ];
    }
}
