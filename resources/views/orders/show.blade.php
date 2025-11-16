@extends('layouts.app')

@section('title', 'Order Details')

@push('styles')
<style>
    .breadcrumb {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    .order-detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 25px;
    }
    .order-detail-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
    }
    .table-modern thead {
        background: #f8f9fa;
    }
    .table-modern th {
        border: none;
        padding: 15px;
        font-weight: 600;
        color: #2c3e50;
    }
    .table-modern td {
        border: none;
        padding: 15px;
    }
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-modern {
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
            <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 700; color: #2c3e50;">Order Details</h2>
        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}" 
              style="padding: 10px 20px; font-size: 1rem; border-radius: 20px;">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-2"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y H:i') }}</p>
                            <p class="mb-2"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-2"><strong>Payment Status:</strong> 
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}" style="padding: 6px 12px;">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                            <p class="mb-2"><strong>Order Status:</strong> 
                                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}" style="padding: 6px 12px;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-box"></i> Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
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
                                    <td><strong>{{ formatRupiah($item->price) }}</strong></td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong style="color: #667eea;">{{ formatRupiah($item->price * $item->quantity) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.2rem;">{{ formatRupiah($order->total_amount) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Customer:</strong> {{ $order->user->name }}</p>
                    <p class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p class="mb-2"><strong>Address:</strong></p>
                    <p class="text-muted">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span><strong>{{ formatRupiah($order->total_amount) }}</strong></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.3rem;">{{ formatRupiah($order->total_amount) }}</strong>
                    </div>
                </div>
            </div>

            @if($order->status == 'pending' && $order->user_id == auth()->id())
            <div class="order-detail-card border-danger">
                <div class="card-body">
                    <h6 class="card-title" style="font-weight: 600; color: #dc3545;"><i class="bi bi-x-circle"></i> Cancel Order</h6>
                    <p class="card-text small text-muted">You can cancel this order before it's shipped.</p>
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-modern w-100" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($order->status == 'cancelled')
            <div class="alert alert-danger" style="border-radius: 12px; border: none;">
                <i class="bi bi-x-circle"></i> This order has been cancelled.
            </div>
            @endif

            @if($order->status == 'delivered')
            <div class="alert alert-success" style="border-radius: 12px; border: none;">
                <i class="bi bi-check-circle"></i> This order has been delivered.
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary btn-modern">
            <i class="bi bi-file-pdf"></i> Download Invoice PDF
        </a>
    </div>
</div>
@endsection
