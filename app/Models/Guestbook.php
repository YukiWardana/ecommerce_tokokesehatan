<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Guestbook Model
 * 
 * Represents messages from visitors (guests or customers)
 * Allows anyone to leave messages, suggestions, or feedback about the website
 * No authentication required - public feature
 * 
 * Database Table: guestbook
 */
class Guestbook extends Model
{
    use HasFactory;

    /**
     * Table Name
     * 
     * Explicitly specify table name (singular form)
     * 
     * @var string
     */
    protected $table = 'guestbook';

    /**
     * Mass Assignable Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'name',    // Name of the person leaving the message
        'email',   // Email address (optional)
        'message', // The message/feedback content
    ];

    // No relationships - standalone feature
}
