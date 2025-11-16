<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product Model
 * 
 * Represents products in the multi-vendor e-commerce system
 * Each product belongs to a shop (seller) and a category
 * Products can be added to cart, ordered, and reviewed by customers
 * 
 * Database Table: products
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * Fields that can be filled using create() or update() methods
     * 
     * @var array
     */
    protected $fillable = [
        'shop_id',      // ID of the shop that owns this product (foreign key)
        'category_id',  // ID of the product category (foreign key)
        'name',         // Product name/title
        'description',  // Detailed product description
        'price',        // Product price in Rupiah
        'stock',        // Available quantity in inventory
        'image',        // Path to product image file
        'is_active',    // Whether product is visible to customers (true/false)
    ];

    /**
     * Attribute Casting
     * 
     * Automatically convert attributes to specific data types
     * 
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',    // Cast price to decimal with 2 decimal places
        'is_active' => 'boolean',  // Cast is_active to boolean (true/false)
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Category Relationship
     * 
     * Get the category this product belongs to
     * Many products belong to one category
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Carts Relationship
     * 
     * Get all cart items containing this product
     * One product can be in many users' carts
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Order Items Relationship
     * 
     * Get all order items containing this product
     * One product can be in many orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Feedbacks Relationship
     * 
     * Get all customer reviews/ratings for this product
     * One product can have many feedbacks
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Shop Relationship
     * 
     * Get the shop (seller) that owns this product
     * Many products belong to one shop
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
