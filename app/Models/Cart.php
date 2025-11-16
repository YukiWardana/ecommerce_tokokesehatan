<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Cart Model
 * 
 * Represents shopping cart items for customers
 * Each user can have multiple products in their cart
 * Cart is temporary storage before checkout
 * 
 * Database Table: carts
 */
class Cart extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',    // ID of the user who owns this cart item (foreign key)
        'product_id', // ID of the product in the cart (foreign key)
        'quantity',   // Number of units in cart
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * User Relationship
     * 
     * Get the user who owns this cart item
     * Many cart items belong to one user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product Relationship
     * 
     * Get the product details for this cart item
     * Many cart items can reference one product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
