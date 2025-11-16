<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Shop Model
 * 
 * Represents seller shops in the multi-vendor system
 * Each seller (user with role='seller') owns one shop
 * Shops can have multiple products
 * Created when admin approves a shop request
 * 
 * Database Table: shops
 */
class Shop extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',     // ID of the seller who owns this shop (foreign key)
        'shop_name',   // Name of the shop/store
        'description', // Shop description/about
        'logo',        // Path to shop logo image
        'location',    // Shop physical location/address
        'phone',       // Shop contact phone number
        'is_active',   // Whether shop is active and visible (true/false)
    ];

    /**
     * Attribute Casting
     * 
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean', // Cast to boolean for easy checking
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * User Relationship
     * 
     * Get the seller (user) who owns this shop
     * One shop belongs to one user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Products Relationship
     * 
     * Get all products listed in this shop
     * One shop can have many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Orders Relationship (Through Products)
     * 
     * Get all orders containing products from this shop
     * Uses hasManyThrough to access orders via products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class);
    }
}
