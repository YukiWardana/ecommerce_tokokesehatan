<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::where('user_id', $request->user()->id)
            ->with('product')
            ->get();
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($validated['product_id']);

        // Check if product has enough stock
        if ($product->stock < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock',
                'available_stock' => $product->stock
            ], 400);
        }

        // Check if item already in cart
        $existingCart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $validated['quantity'];
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'message' => 'Cannot add more. Insufficient stock',
                    'available_stock' => $product->stock,
                    'current_cart_quantity' => $existingCart->quantity
                ], 400);
            }
            $existingCart->update(['quantity' => $newQuantity]);
            return response()->json($existingCart->load('product'), 200);
        }

        $cart = Cart::create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity']
        ]);

        return response()->json($cart->load('product'), 201);
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);

        // Check if product has enough stock
        if ($cart->product->stock < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock',
                'available_stock' => $cart->product->stock
            ], 400);
        }

        $cart->update($validated);
        return response()->json($cart->load('product'));
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }
}
