<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Feedback Model
 * 
 * Represents customer reviews and ratings for products
 * Customers can rate products from 1-5 stars and leave comments
 * Helps other customers make informed purchase decisions
 * 
 * Database Table: feedbacks
 */
class Feedback extends Model
{
    use HasFactory;

    /**
     * Table Name
     * 
     * Explicitly specify table name (plural form)
     * 
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',    // ID of the customer who wrote the review (foreign key)
        'product_id', // ID of the product being reviewed (foreign key)
        'rating',     // Star rating: 1-5 (integer)
        'comment',    // Written review/comment (optional)
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * User Relationship
     * 
     * Get the customer who wrote this feedback
     * Many feedbacks belong to one user
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
     * Get the product this feedback is about
     * Many feedbacks can be for one product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
