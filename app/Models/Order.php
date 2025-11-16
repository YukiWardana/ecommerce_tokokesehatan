<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order Model
 * 
 * Represents customer orders in the system
 * Each order contains multiple order items (products)
 * Orders can be from multiple sellers (multi-vendor)
 * 
 * Order Status Flow:
 * pending → processing → shipped → delivered
 * (can also be cancelled at any stage)
 * 
 * Database Table: orders
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',          // ID of customer who placed the order (foreign key)
        'order_number',     // Unique order number (e.g., ORD-20250109-ABC123)
        'total_amount',     // Total order amount in Rupiah
        'status',           // Order status: pending, processing, shipped, delivered, cancelled
        'payment_method',   // Payment method: cod, bank_transfer, e_wallet
        'payment_status',   // Payment status: pending, paid, failed
        'shipping_address', // Customer's shipping address
        'phone',            // Customer's contact phone number
    ];

    /**
     * Attribute Casting
     * 
     * @var array
     */
    protected $casts = [
        'total_amount' => 'decimal:2', // Cast to decimal with 2 decimal places
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * User Relationship
     * 
     * Get the customer who placed this order
     * Many orders belong to one user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order Items Relationship
     * 
     * Get all items (products) in this order
     * One order can have many order items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
