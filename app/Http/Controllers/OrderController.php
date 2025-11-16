<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Mail\OrderInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:debit_card,credit_card,digital_wallet',
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $carts = Cart::where('user_id', $request->user()->id)->with('product')->get();
        
        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Check stock availability
        foreach ($carts as $cart) {
            if ($cart->product->stock < $cart->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for {$cart->product->name}",
                    'available_stock' => $cart->product->stock,
                    'requested_quantity' => $cart->quantity
                ], 400);
            }
        }

        $totalAmount = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
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

        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json($order->load('orderItems.product'), 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('orderItems.product'));
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'shipped' || $order->status === 'delivered') {
            return response()->json(['message' => 'Cannot cancel shipped or delivered orders'], 400);
        }

        // Restore product stock when order is cancelled
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Send invoice email when status changes to "processing"
        if ($validated['status'] === 'processing' && $oldStatus !== 'processing') {
            try {
                $order->load(['orderItems.product.category', 'user']);
                Mail::to($order->user->email)->send(new OrderInvoice($order));
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to send invoice email: ' . $e->getMessage());
            }
        }

        return response()->json($order);
    }
}
