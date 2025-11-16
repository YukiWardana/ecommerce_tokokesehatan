<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopRequest;
use App\Mail\OrderInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Admin Controller
 * 
 * Handles all admin-related functionality for system management
 * Protected by AdminMiddleware - only accessible to users with role='admin'
 * 
 * Features:
 * - Dashboard with system statistics
 * - Customer management (view, delete)
 * - Order management and status updates
 * - Shop request approval/rejection
 * - Email notifications to customers
 * 
 * Routes: /admin/*
 */
class AdminController extends Controller
{
    /**
     * Admin Dashboard
     * 
     * Display system overview with statistics and recent orders
     * 
     * Statistics shown:
     * - Total products in system
     * - Total orders placed
     * - Total customers registered
     * - Total active shops
     * - Pending shop requests
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Calculate system-wide statistics
        $stats = [
            'products' => Product::count(),                              // Total products
            'orders' => Order::count(),                                  // Total orders
            'customers' => User::where('role', 'customer')->count(),     // Total customers
            'shops' => Shop::count(),                                    // Total shops
            'pending_requests' => ShopRequest::where('status', 'pending')->count(), // Pending shop requests
        ];

        // Get 5 most recent orders with customer information
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Return dashboard view with data
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * List Customers
     * 
     * Display all customers with their order count
     * Paginated list with 15 customers per page
     * 
     * @return \Illuminate\View\View
     */
    public function customers()
    {
        // Get customers with order count, paginated
        $customers = User::where('role', 'customer')->withCount('orders')->paginate(15);
        
        // Return customers list view
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show Customer Details
     * 
     * Display detailed information about a specific customer
     * Includes orders and feedbacks
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function showCustomer(User $user)
    {
        // Eager load customer's orders and feedbacks
        $user->load(['orders', 'feedbacks']);
        
        // Return customer detail view
        return view('admin.customers.show', compact('user'));
    }

    /**
     * Delete Customer
     * 
     * Remove customer from system
     * Security: Prevents deletion of admin users
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyCustomer(User $user)
    {
        // Security check: Prevent deletion of admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users');
        }

        // Delete user from database
        $user->delete();
        
        // Redirect with success message
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted');
    }

    /**
     * List Products
     * 
     * Display all products in the system (from all shops)
     * Paginated list with 15 products per page
     * Shows product details, shop, and category
     * 
     * @return \Illuminate\View\View
     */
    public function products()
    {
        // Get all products with relationships, paginated
        $products = Product::with(['category', 'shop'])->orderBy('created_at', 'desc')->paginate(15);
        
        // Return products list view
        return view('admin.products.index', compact('products'));
    }

    /**
     * List Orders
     * 
     * Display all orders in the system
     * Paginated list with 15 orders per page
     * Ordered by newest first
     * 
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        // Get all orders with customer information, paginated
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(15);
        
        // Return orders list view
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update Order Status
     * 
     * Allow admin to update any order status
     * Automatically sends invoice email when status changes to "processing"
     * 
     * Status flow: pending → processing → shipped → delivered
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        // Validate new status
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // Store old status before updating
        $oldStatus = $order->status;
        
        // Update order status in database
        $order->update($validated);

        // Send invoice email when status changes to "processing"
        if ($validated['status'] === 'processing' && $oldStatus !== 'processing') {
            try {
                // Load order relationships needed for invoice
                $order->load(['orderItems.product.category', 'user']);
                
                // Send invoice email with PDF attachment
                Mail::to($order->user->email)->send(new OrderInvoice($order));
                
                // Return with email success message
                return back()->with('success', 'Order status updated and invoice email sent to customer');
            } catch (\Exception $e) {
                // Email failed but status was updated
                return back()->with('success', 'Order status updated (Email sending failed: ' . $e->getMessage() . ')');
            }
        }

        // Status updated without email
        return back()->with('success', 'Order status updated');
    }

    /**
     * List Shop Requests
     * 
     * Display all shop registration requests from customers
     * Shows pending, approved, and rejected requests
     * Ordered by newest first
     * 
     * @return \Illuminate\View\View
     */
    public function shopRequests()
    {
        // Get all shop requests with user information
        $requests = ShopRequest::with('user')->orderBy('created_at', 'desc')->get();
        
        // Return shop requests list view
        return view('admin.shop-requests.index', compact('requests'));
    }

    /**
     * Approve Shop Request
     * 
     * Approve customer's request to become a seller
     * 
     * Process:
     * 1. Create new shop with request details
     * 2. Change user role from 'customer' to 'seller'
     * 3. Update request status to 'approved'
     * 
     * After approval, user can access seller dashboard and manage products
     * 
     * @param  \App\Models\ShopRequest  $shopRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveShopRequest(ShopRequest $shopRequest)
    {
        // Step 1: Create the shop with request details
        $shop = Shop::create([
            'user_id' => $shopRequest->user_id,           // Link shop to user
            'shop_name' => $shopRequest->shop_name,       // Use requested shop name
            'description' => $shopRequest->description,   // Use requested description
            'is_active' => true,                          // Activate shop immediately
        ]);

        // Step 2: Change user role from 'customer' to 'seller'
        $shopRequest->user->update(['role' => 'seller']);

        // Step 3: Update request status to 'approved'
        $shopRequest->update(['status' => 'approved']);

        // Redirect with success message
        return back()->with('success', 'Shop approved! User is now a seller and can manage their shop.');
    }

    /**
     * Reject Shop Request
     * 
     * Reject customer's request to become a seller
     * Admin can provide notes explaining rejection reason
     * 
     * User remains as 'customer' and can submit new request
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopRequest  $shopRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectShopRequest(Request $request, ShopRequest $shopRequest)
    {
        // Update request status to 'rejected' with admin notes
        $shopRequest->update([
            'status' => 'rejected',                // Mark as rejected
            'admin_notes' => $request->admin_notes, // Store rejection reason
        ]);
        
        // Redirect with success message
        return back()->with('success', 'Shop request rejected');
    }
}
