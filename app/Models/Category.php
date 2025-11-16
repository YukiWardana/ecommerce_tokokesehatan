<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Category Model
 * 
 * Represents product categories in the e-commerce system
 * Used to organize products into groups (e.g., Electronics, Clothing, Food)
 * Managed by admin users
 * 
 * Database Table: categories
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'name',        // Category name (e.g., "Electronics", "Clothing")
        'description', // Category description (optional)
        'image',       // Path to category image/icon
        'is_active',   // Whether category is visible (true/false)
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
     * Products Relationship
     * 
     * Get all products in this category
     * One category can have many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
