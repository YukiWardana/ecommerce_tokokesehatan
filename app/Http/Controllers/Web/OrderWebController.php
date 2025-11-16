<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderWebController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show Checkout Page
     * 
     * Display checkout form with payment options
     * Enables Cash on Delivery (COD) if customer location matches shop location
     * 
     * COD Logic:
     * - Check if cart contains products from shops
     * - Compare customer's city/location with shop locations
     * - Enable COD if locations match
     * 
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        // Get cart items with product and shop relationships
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product.shop')
            ->get();
        
        // Redirect if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate total amount
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        // Check if COD is available based on location
        $codAvailable = $this->checkCodAvailability($cartItems);
        
        // Get shops in cart for location display
        $shopsInCart = $cartItems->map(fn($item) => $item->product->shop)
            ->filter()
            ->unique('id');
        
        return view('checkout', compact('cartItems', 'total', 'codAvailable', 'shopsInCart'));
    }

    /**
     * Check COD Availability
     * 
     * Determines if Cash on Delivery is available based on location proximity
     * 
     * Logic:
     * - Get customer's location from address
     * - Get shop locations from cart products
     * - Compare locations (case-insensitive, partial match)
     * - COD available if any shop is in same city/area
     * 
     * @param  \Illuminate\Support\Collection  $cartItems
     * @return bool
     */
    private function checkCodAvailability($cartItems)
    {
        // Get customer's address
        $customerAddress = strtolower(auth()->user()->address ?? '');
        
        // If customer has no address, COD not available
        if (empty($customerAddress)) {
            return false;
        }

        // Extract city/location from customer address
        // Common patterns: "Jakarta", "Bandung", "Surabaya", etc.
        $customerLocation = $this->extractLocation($customerAddress);
        
        // Check each product's shop location
        foreach ($cartItems as $item) {
            $shop = $item->product->shop;
            
            // Skip if product has no shop (admin products)
            if (!$shop || !$shop->location) {
                continue;
            }
            
            // Extract shop location
            $shopLocation = $this->extractLocation(strtolower($shop->location));
            
            // Check if locations match (same city/area)
            if ($this->locationsMatch($customerLocation, $shopLocation)) {
                return true; // COD available if at least one shop is nearby
            }
        }
        
        return false; // No nearby shops found
    }

    /**
     * Extract Location from Address
     * 
     * Extracts city/area name from full address string
     * 
     * @param  string  $address
     * @return string
     */
    private function extractLocation($address)
    {
        // Common Indonesian cities
        $cities = [
            'jakarta', 'bandung', 'surabaya', 'medan', 'semarang',
            'makassar', 'palembang', 'tangerang', 'depok', 'bekasi',
            'bogor', 'yogyakarta', 'malang', 'solo', 'denpasar'
        ];
        
        // Check if any city name is in the address
        foreach ($cities as $city) {
            if (strpos($address, $city) !== false) {
                return $city;
            }
        }
        
        // If no city found, return first word (might be area/district)
        $words = explode(' ', trim($address));
        return $words[0] ?? '';
    }

    /**
     * Check if Locations Match
     * 
     * Compare two location strings for proximity
     * 
     * @param  string  $location1
     * @param  string  $location2
     * @return bool
     */
    private function locationsMatch($location1, $location2)
    {
        // Exact match
        if ($location1 === $location2) {
            return true;
        }
        
        // Partial match (one contains the other)
        if (strpos($location1, $location2) !== false || strpos($location2, $location1) !== false) {
            return true;
        }
        
        return false;
    }

    /**
     * Store Order
     * 
     * Process checkout and create order
     * Validates payment method (including COD if available)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate order data
        $validated = $request->validate([
            'payment_method' => 'required|in:debit_card,credit_card,digital_wallet,cod',
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Get cart items with product and shop relationships
        $carts = Cart::where('user_id', auth()->id())->with('product.shop')->get();
        
        // Check if cart is empty
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        // Validate COD availability if COD selected
        if ($validated['payment_method'] === 'cod') {
            $codAvailable = $this->checkCodAvailability($carts);
            
            if (!$codAvailable) {
                return back()->with('error', 'Cash on Delivery is not available for your location. Please choose another payment method.');
            }
        }

        // Check stock availability
        foreach ($carts as $cart) {
            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Insufficient stock for {$cart->product->name}. Only {$cart->product->stock} available.");
            }
        }

        // Calculate total amount
        $totalAmount = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'phone' => $validated['phone'],
        ]);

        foreach ($carts as $cart) {
            // Create order item
            $order->orderItems()->create([
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);

            // Reduce product stock
            $cart->product->decrement('stock', $cart->quantity);
        }

        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load(['orderItems.product.category', 'user']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $order]);
        
        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (in_array($order->status, ['shipped', 'delivered'])) {
            return back()->with('error', 'Cannot cancel shipped or delivered orders');
        }

        // Restore product stock when order is cancelled
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Order cancelled successfully and stock restored');
    }
}
