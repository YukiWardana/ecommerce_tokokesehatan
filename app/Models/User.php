<?php

namespace App\Models;

// Import necessary Laravel classes
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 * 
 * Represents users in the system with three roles:
 * - admin: Can manage everything (products, categories, orders, users, shops)
 * - seller: Can manage their own shop and products
 * - customer: Can browse, purchase products, and leave feedback
 * 
 * Database Table: users
 */
class User extends Authenticatable
{
    // Use Laravel traits for API tokens, factories, and notifications
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass Assignable Attributes
     * 
     * These fields can be filled using create() or update() methods
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',        // User's full name
        'email',       // User's email address (unique, used for login)
        'password',    // Hashed password
        'role',        // User role: 'admin', 'seller', or 'customer'
        'phone',       // User's phone number (optional)
        'address',     // User's shipping/billing address (optional)
    ];

    /**
     * Hidden Attributes
     * 
     * These fields will not be included in JSON responses
     * for security purposes
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',       // Never expose password in API responses
        'remember_token', // Token for "remember me" functionality
    ];

    /**
     * Attribute Casting
     * 
     * Automatically convert attributes to specific data types
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Cast to Carbon datetime object
        'password' => 'hashed',            // Automatically hash password on save
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Cart Relationship
     * 
     * Get all cart items belonging to this user
     * One user can have many cart items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Orders Relationship
     * 
     * Get all orders placed by this user
     * One user can have many orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Feedbacks Relationship
     * 
     * Get all product reviews/feedbacks written by this user
     * One user can write many feedbacks
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Shop Requests Relationship
     * 
     * Get all shop registration requests submitted by this user
     * One user can submit multiple shop requests (if rejected)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopRequests()
    {
        return $this->hasMany(ShopRequest::class);
    }

    /**
     * Shop Relationship
     * 
     * Get the shop owned by this user (if they are a seller)
     * One user can own only one shop
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if user is an admin
     * 
     * Admins have full access to manage the entire system
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a customer
     * 
     * Customers can browse and purchase products
     * 
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is a seller
     * 
     * Sellers can manage their own shop and products
     * 
     * @return bool
     */
    public function isSeller()
    {
        return $this->role === 'seller';
    }
}
