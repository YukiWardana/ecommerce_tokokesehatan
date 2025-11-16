<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Seller Middleware
 * 
 * Protects seller-only routes from unauthorized access
 * Only users with role='seller' AND an active shop can access protected routes
 * 
 * Usage: Apply to seller dashboard and shop management routes
 * Example: Route::middleware('seller')->group(...)
 */
class SellerMiddleware
{
    /**
     * Handle an incoming request
     * 
     * Performs two checks:
     * 1. User must be authenticated and have role='seller'
     * 2. Seller must have an active shop
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is not logged in OR user role is not 'seller'
        if (!$request->user() || $request->user()->role !== 'seller') {
            // Redirect to home with error message
            return redirect()->route('home')->with('error', 'You must be a seller to access this page.');
        }

        // Check if seller has a shop (shop request must be approved by admin)
        if (!$request->user()->shop) {
            // Redirect to home - seller account exists but no shop yet
            return redirect()->route('home')->with('error', 'You do not have a shop yet.');
        }

        // User is seller with active shop, allow request to proceed
        return $next($request);
    }
}
