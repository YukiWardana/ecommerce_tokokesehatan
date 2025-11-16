<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ShopRequest Model
 * 
 * Represents shop registration requests from customers who want to become sellers
 * Customers submit requests, admins review and approve/reject them
 * When approved, a Shop is created and user role changes to 'seller'
 * 
 * Status values: pending, approved, rejected
 * 
 * Database Table: shop_requests
 */
class ShopRequest extends Model
{
    use HasFactory;

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',     // ID of the user requesting to become a seller (foreign key)
        'shop_name',   // Proposed shop name
        'description', // Shop description/business plan
        'status',      // Request status: pending, approved, rejected
        'admin_notes', // Admin's notes/reason for rejection (optional)
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * User Relationship
     * 
     * Get the user who submitted this shop request
     * Many shop requests can belong to one user (if rejected and resubmitted)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
