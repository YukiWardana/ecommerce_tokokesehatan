<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;

/**
 * Seller Controller
 * 
 * Handles all seller-related functionality in the multi-vendor system
 * Protected by SellerMiddleware - only accessible to users with role='seller'
 * 
 * Features:
 * - Dashboard with statistics
 * - Product management (CRUD)
 * - Order management and status updates
 * - Shop settings management
 * - Email notifications to customers
 * 
 * Routes: /seller/*
 */
class SellerController extends Controller
{
    /**
     * Seller Dashboard
     * 
     * Display seller's shop overview with statistics and recent orders
     * 
     * Statistics shown:
     * - Total products in shop
     * - Total orders containing shop products
     * - Total revenue from all orders
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get authenticated seller's shop
        $shop = auth()->user()->shop;
        
        // Safety check - redirect if no shop (shouldn't happen with middleware)
        if (!$shop) {
            return redirect()->route('home')->with('error', 'You do not have a shop yet.');
        }

        // Calculate shop statistics
        $stats = [
            // Count total products in this shop
            'products' => $shop->products()->count(),
            
            // Count total order items containing shop products
            'orders' => $shop->products()->withCount('orderItems')->get()->sum('order_items_count'),
            
            // Calculate total revenue (price × quantity for all order items)
            'revenue' => $shop->products()
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->sum(\DB::raw('order_items.price * order_items.quantity')),
        ];

        // Get 10 most recent orders containing shop products
        $recentOrders = Order::whereHas('orderItems.product', function($query) use ($shop) {
            // Only orders with products from this shop
            $query->where('shop_id', $shop->id);
        })->with(['orderItems' => function($query) use ($shop) {
            // Eager load only order items from this shop
            $query->whereHas('product', function($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            });
        }, 'orderItems.product', 'user']) // Also load product details and customer
        ->latest() // Order by newest first
        ->take(10) // Limit to 10 orders
        ->get();

        // Return dashboard view with data
        return view('seller.dashboard', compact('shop', 'stats', 'recentOrders'));
    }

    /**
     * List Products
     * 
     * Display all products belonging to seller's shop
     * Paginated list with 15 products per page
     * 
     * @return \Illuminate\View\View
     */
    public function products()
    {
        // Get seller's shop
        $shop = auth()->user()->shop;
        
        // Get shop products with category relationship, paginated
        $products = $shop->products()->with('category')->paginate(15);
        
        // Return products list view
        return view('seller.products.index', compact('products', 'shop'));
    }

    /**
     * Show Create Product Form
     * 
     * Display form for adding new product to shop
     * Loads active categories for selection
     * 
     * @return \Illuminate\View\View
     */
    public function createProduct()
    {
        // Get only active categories for dropdown
        $categories = Category::where('is_active', true)->get();
        
        // Return create product form
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Store New Product
     * 
     * Validate and save new product to database
     * Handles image upload and associates product with seller's shop
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProduct(Request $request)
    {
        // Get seller's shop
        $shop = auth()->user()->shop;

        // Validate product data
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',  // Must be valid category
            'name' => 'required|string|max:255',               // Product name required
            'description' => 'nullable|string',                // Description optional
            'price' => 'required|numeric|min:0',               // Price must be positive number
            'stock' => 'required|integer|min:0',               // Stock must be positive integer
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image optional, max 2MB
            'is_active' => 'boolean',                          // Active status (true/false)
        ]);

        // Handle product image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Generate unique filename: timestamp_uniqueid.extension
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Move image to public/images/products directory
            $image->move(public_path('images/products'), $imageName);
            
            // Store relative path in database
            $validated['image'] = 'images/products/' . $imageName;
        }

        // Associate product with seller's shop
        $validated['shop_id'] = $shop->id;

        // Create product in database
        Product::create($validated);

        // Redirect to products list with success message
        return redirect()->route('seller.products')->with('success', 'Product added successfully!');
    }

    /**
     * Show Edit Product Form
     * 
     * Display form for editing existing product
     * Security: Verifies product belongs to seller's shop
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function editProduct(Product $product)
    {
        // Security check: Ensure product belongs to this seller's shop
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.'); // Return 403 Forbidden
        }

        // Get active categories for dropdown
        $categories = Category::where('is_active', true)->get();
        
        // Return edit form with product data
        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Update Product
     * 
     * Validate and update existing product
     * Handles image replacement (deletes old, uploads new)
     * Security: Verifies product belongs to seller's shop
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProduct(Request $request, Product $product)
    {
        // Security check: Ensure product belongs to this seller's shop
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.'); // Return 403 Forbidden
        }

        // Validate updated product data
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle new image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image file from server
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        // Update product in database
        $product->update($validated);

        // Redirect with success message
        return redirect()->route('seller.products')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete Product
     * 
     * Remove product from database and delete image file
     * Security: Verifies product belongs to seller's shop
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyProduct(Product $product)
    {
        // Security check: Ensure product belongs to this seller's shop
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.'); // Return 403 Forbidden
        }

        // Delete product image file from server
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete product from database
        $product->delete();

        // Redirect with success message
        return redirect()->route('seller.products')->with('success', 'Product deleted successfully!');
    }

    /**
     * List Orders
     * 
     * Display all orders containing products from seller's shop
     * Shows only order items from this shop (multi-vendor support)
     * Paginated list with 15 orders per page
     * 
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        // Get seller's shop
        $shop = auth()->user()->shop;
        
        // Get orders containing shop products
        $orders = Order::whereHas('orderItems.product', function($query) use ($shop) {
            // Only orders with products from this shop
            $query->where('shop_id', $shop->id);
        })->with(['orderItems' => function($query) use ($shop) {
            // Eager load only order items from this shop
            $query->whereHas('product', function($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            });
        }, 'orderItems.product', 'user']) // Also load product details and customer
        ->orderBy('created_at', 'desc') // Newest orders first
        ->paginate(15); // 15 orders per page

        // Return orders list view
        return view('seller.orders.index', compact('orders', 'shop'));
    }

    /**
     * Show Shop Settings Form
     * 
     * Display form for editing shop information
     * (name, description, logo, location, phone)
     * 
     * @return \Illuminate\View\View
     */
    public function shopSettings()
    {
        // Get seller's shop
        $shop = auth()->user()->shop;
        
        // Return settings form
        return view('seller.settings', compact('shop'));
    }

    /**
     * Update Shop Settings
     * 
     * Validate and update shop information
     * Handles logo upload/replacement
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShopSettings(Request $request)
    {
        // Get seller's shop
        $shop = auth()->user()->shop;

        // Validate shop settings data
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',  // Shop name required
            'description' => 'nullable|string',        // Description optional
            'location' => 'nullable|string|max:255',   // Location optional
            'phone' => 'nullable|string|max:20',       // Phone optional
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Logo optional, max 2MB
        ]);

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo file from server
            if ($shop->logo && file_exists(public_path($shop->logo))) {
                unlink(public_path($shop->logo));
            }

            // Upload new logo
            $logo = $request->file('logo');
            $logoName = 'shop_' . time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/shops'), $logoName);
            $validated['logo'] = 'images/shops/' . $logoName;
        }

        // Update shop in database
        $shop->update($validated);

        // Redirect back with success message
        return back()->with('success', 'Shop settings updated successfully!');
    }

    /**
     * Update Order Status
     * 
     * Allow seller to update status of orders containing their products
     * Sends automatic email notification to customer with invoice PDF
     * 
     * Status flow: pending → processing → shipped → delivered
     * 
     * Security: Verifies order contains products from seller's shop
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        // Get seller's shop
        $shop = auth()->user()->shop;

        // Security check: Verify order contains products from this shop
        $hasShopProduct = $order->orderItems()->whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->exists();

        // If order doesn't contain shop products, deny access
        if (!$hasShopProduct) {
            abort(403, 'This order does not contain your products.');
        }

        // Validate new status
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // Store old status before updating (for email notification)
        $oldStatus = $order->status;

        // Update order status in database
        $order->update($validated);

        // Send email notification to customer
        try {
            // Send email with old and new status, includes PDF invoice
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $oldStatus));
        } catch (\Exception $e) {
            // Log error but don't fail the status update
            // Order status is still updated even if email fails
            \Log::error('Failed to send order status email: ' . $e->getMessage());
        }

        // Redirect back with success message
        return back()->with('success', 'Order status updated and customer notified via email!');
    }
}
