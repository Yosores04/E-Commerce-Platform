<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'compare_at_price',
        'cost',
        'quantity',
        'low_stock_threshold',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'views',
        'rating_avg',
        'rating_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories_pivot');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // TODO: Create ProductTag model and migration
    // public function tags()
    // {
    //     return $this->belongsToMany(ProductTag::class, 'product_tag_pivot');
    // }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Searchable configuration
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => $this->price,
        ];
    }

    /**
     * Helpers
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}
