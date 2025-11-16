<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Session Timeout Middleware
 * 
 * Automatically logs out users after a period of inactivity
 * Enhances security by preventing unauthorized access to idle sessions
 * 
 * Current timeout: 30 seconds (for testing - increase for production)
 * Recommended production timeout: 1800 seconds (30 minutes)
 * 
 * Usage: Apply globally or to specific route groups
 */
class SessionTimeout
{
    /**
     * Handle an incoming request
     * 
     * Tracks user activity and logs out inactive users
     * 
     * Process:
     * 1. Check if user is authenticated
     * 2. Get last activity timestamp from session
     * 3. If inactive for too long, logout and redirect
     * 4. Otherwise, update last activity time
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check timeout for authenticated users
        if (Auth::check()) {
            // Get the last activity timestamp from session
            $lastActivity = session('last_activity_time');
            
            // Set timeout duration in seconds
            // 30 seconds for testing - change to 1800 (30 min) for production
            $timeout = 30;

            // Check if last activity exists and timeout has been exceeded
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                // Timeout exceeded - logout user
                Auth::logout();
                
                // Invalidate the session (clear all session data)
                $request->session()->invalidate();
                
                // Regenerate CSRF token for security
                $request->session()->regenerateToken();
                
                // Redirect to login page with timeout message
                return redirect()->route('login')
                    ->with('error', 'Your session has expired due to inactivity. Please login again.');
            }

            // Update last activity time to current timestamp
            // This resets the timeout counter on each request
            session(['last_activity_time' => time()]);
        }

        // User is active or not logged in, proceed with request
        return $next($request);
    }
}
