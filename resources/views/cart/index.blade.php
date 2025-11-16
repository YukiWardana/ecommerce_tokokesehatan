@extends('layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .cart-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .cart-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .cart-table {
        margin: 0;
    }
    .cart-table thead {
        background: #f8f9fa;
    }
    .cart-table th {
        border: none;
        padding: 20px;
        font-weight: 600;
        color: #2c3e50;
    }
    .cart-table td {
        border: none;
        padding: 20px;
        vertical-align: middle;
    }
    .cart-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }
    .cart-table tbody tr:last-child {
        border-bottom: none;
    }
    .product-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .quantity-input {
        width: 80px;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 8px;
        text-align: center;
    }
    .summary-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .summary-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .summary-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-total {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .btn-checkout {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    .btn-danger-modern {
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    .btn-danger-modern:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">
            <i class="bi bi-cart"></i> Shopping Cart
        </h2>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="cart-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bag"></i> Cart Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset($item->product->image) }}" class="product-img me-3" alt="{{ $item->product->name }}">
                                            @endif
                                            <div>
                                                <strong style="color: #2c3e50;">{{ $item->product->name }}</strong><br>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color: #667eea;">{{ formatRupiah($item->product->price) }}</strong>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                   min="1" max="{{ $item->product->stock }}" 
                                                   class="quantity-input"
                                                   onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td>
                                        <strong style="color: #2c3e50;">{{ formatRupiah($item->product->price * $item->quantity) }}</strong>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-danger-modern">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="summary-item">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <strong>{{ formatRupiah($total) }}</strong>
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="summary-item">
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <span class="summary-total">{{ formatRupiah($total) }}</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <a href="{{ route('checkout') }}" class="btn btn-checkout text-white">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="cart-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                <h4 style="color: #2c3e50; margin-bottom: 15px;">Your cart is empty</h4>
                <p class="text-muted mb-4">Start adding products to your cart!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius: 12px; padding: 12px 30px;">
                    <i class="bi bi-shop"></i> Continue Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
