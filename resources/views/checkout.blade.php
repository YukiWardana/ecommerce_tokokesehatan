@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .checkout-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .checkout-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .form-check-input {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid #e0e0e0;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    .form-check-label {
        margin-left: 10px;
        cursor: pointer;
        font-weight: 500;
    }
    .payment-option {
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    .payment-option:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }
    .form-check-input:checked ~ .payment-option {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
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
    .btn-place-order {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 20px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">
            <i class="bi bi-credit-card"></i> Checkout
        </h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="checkout-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            
                            @if($codAvailable)
                            <div class="alert alert-success alert-modern mb-3">
                                <i class="bi bi-check-circle-fill"></i> 
                                <strong>Cash on Delivery Available!</strong>
                                <p class="mb-0 small mt-2">You're close to the shop. Pay when you receive your order.</p>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <div class="payment-option">
                                        <i class="bi bi-cash-coin"></i> <strong>Cash on Delivery (COD)</strong>
                                        <br>
                                        <small class="text-muted">Pay with cash when your order arrives</small>
                                    </div>
                                </label>
                            </div>
                            <hr>
                            @endif
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="debit_card" value="debit_card" {{ !$codAvailable ? 'checked' : '' }}>
                                <label class="form-check-label" for="debit_card">
                                    <div class="payment-option">
                                        <i class="bi bi-credit-card"></i> Debit Card
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="credit_card" value="credit_card">
                                <label class="form-check-label" for="credit_card">
                                    <div class="payment-option">
                                        <i class="bi bi-credit-card-2-front"></i> Credit Card
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="digital_wallet" value="digital_wallet">
                                <label class="form-check-label" for="digital_wallet">
                                    <div class="payment-option">
                                        <i class="bi bi-wallet2"></i> Digital Wallet
                                    </div>
                                </label>
                            </div>
                            
                            @if(!$codAvailable)
                            <div class="alert alert-info alert-modern mt-3">
                                <i class="bi bi-info-circle"></i> 
                                <small>
                                    <strong>Cash on Delivery not available</strong><br>
                                    COD is only available if you're in the same city as the shop.
                                    @if($shopsInCart->isNotEmpty())
                                        <br>Shop locations: 
                                        @foreach($shopsInCart as $shop)
                                            <span class="badge bg-secondary">{{ $shop->location ?? 'Not specified' }}</span>
                                        @endforeach
                                    @endif
                                </small>
                            </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-place-order text-white">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($cartItems as $item)
                    <div class="summary-item">
                        <div class="d-flex justify-content-between">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span><strong>{{ formatRupiah($item->product->price * $item->quantity) }}</strong></span>
                        </div>
                    </div>
                    @endforeach
                    <hr class="my-0">
                    <div class="summary-item">
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <span class="summary-total">{{ formatRupiah($total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
