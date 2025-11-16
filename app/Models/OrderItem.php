<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OrderItem Model
 * 
 * Represents individual products within an order
 * Each order can have multiple order items (products)
 * Stores the product snapshot at time of purchase (price, quantity)
 * 
 * Database Table: order_items
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'order_id',   // ID of the order this item belongs to (foreign key)
        'product_id', // ID of the product being ordered (foreign key)
        'quantity',   // Number of units ordered
        'price',      // Price per unit at time of purchase (snapshot)
    ];

    /**
     * Attribute Casting
     * 
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2', // Cast price to decimal with 2 decimal places
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Order Relationship
     * 
     * Get the order this item belongs to
     * Many order items belong to one order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Product Relationship
     * 
     * Get the product details for this order item
     * Many order items can reference one product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
