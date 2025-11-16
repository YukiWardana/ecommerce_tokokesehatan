<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartWebController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product.category')->get();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Check if product has enough stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', "Only {$product->stock} items available in stock");
        }

        // Check if item already in cart
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', "Cannot add more. Only {$product->stock} items available in stock");
            }
            $existingCart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    public function update(Request $request, Cart $cart)
    {
        $quantity = $request->quantity;

        // Check if product has enough stock
        if ($cart->product->stock < $quantity) {
            return back()->with('error', "Only {$cart->product->stock} items available in stock");
        }

        $cart->update(['quantity' => $quantity]);
        return back()->with('success', 'Cart updated');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Item removed from cart');
    }
}
