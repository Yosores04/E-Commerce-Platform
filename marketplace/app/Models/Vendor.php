<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'business_name',
        'business_email',
        'business_phone',
        'tax_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'commission_rate',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bank_routing_number',
        'payout_schedule',
        'documents',
        'metadata',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class);
    }

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class);
    }

    /**
     * Helpers
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }
}
