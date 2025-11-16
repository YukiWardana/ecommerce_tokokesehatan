<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin Middleware
 * 
 * Protects admin-only routes from unauthorized access
 * Only users with role='admin' can access protected routes
 * 
 * Usage: Apply to routes that should only be accessible by admins
 * Example: Route::middleware('admin')->group(...)
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request
     * 
     * Checks if the authenticated user has admin role
     * Returns 403 Forbidden if not admin or not authenticated
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is not logged in OR user role is not 'admin'
        if (!$request->user() || $request->user()->role !== 'admin') {
            // Return 403 Forbidden response with JSON message
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // User is admin, allow request to proceed
        return $next($request);
    }
}
