@extends('layouts.app')

@section('title', 'My Orders')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .order-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .order-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
    }
    .order-status {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .btn-modern {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0 5px;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">
            <i class="bi bi-bag-check"></i> My Orders
        </h2>
    </div>

    @forelse($orders as $order)
    <div class="order-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong style="font-size: 1.2rem;">Order #{{ $order->order_number }}</strong>
                <span class="order-status badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }} ms-2">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <small>{{ $order->created_at->format('M d, Y') }}</small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-3" style="font-weight: 600; color: #2c3e50;"><i class="bi bi-box"></i> Items:</h6>
                    @foreach($order->orderItems as $item)
                    <div class="d-flex justify-content-between mb-2 p-2" style="background: #f8f9fa; border-radius: 8px;">
                        <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span><strong>{{ formatRupiah($item->price * $item->quantity) }}</strong></span>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3" style="font-weight: 600; color: #2c3e50;"><i class="bi bi-geo-alt"></i> Shipping Info:</h6>
                    <p class="mb-2"><strong>Address:</strong><br>{{ $order->shipping_address }}</p>
                    <p class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p class="mb-2"><strong>Payment:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Total: <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700;">{{ formatRupiah($order->total_amount) }}</span>
                </h5>
                <div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm btn-modern">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                    <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-success btn-sm btn-modern">
                        <i class="bi bi-file-pdf"></i> Invoice
                    </a>
                    @if($order->status == 'pending')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm btn-modern" 
                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="order-card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bag-x display-1 text-muted mb-3"></i>
            <h4 style="color: #2c3e50; margin-bottom: 15px;">No orders yet</h4>
            <p class="text-muted mb-4">Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-modern">
                <i class="bi bi-shop"></i> Start Shopping
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection
