@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h2 class="mb-4">My Shop Orders</h2>

    @forelse($orders as $order)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Order #{{ $order->order_number }}</strong>
                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Customer: {{ $order->user->name }}</h6>
                    <p class="mb-2"><strong>Items from your shop:</strong></p>
                    @foreach($order->orderItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span>{{ formatRupiah($item->price * $item->quantity) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <p class="mb-2"><strong>Shipping Address:</strong></p>
                    <p class="text-muted small">{{ $order->shipping_address }}</p>
                    <p class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</p>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Your Items Total: 
                    <span class="text-primary">
                        {{ formatRupiah($order->orderItems->sum(function($item) {
                            return $item->price * $item->quantity;
                        })) }}
                    </span>
                </h5>
                <div>
                    @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                Update Status
                            </button>
                            <ul class="dropdown-menu">
                                @if($order->status === 'pending')
                                    <li>
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-arrow-right-circle"></i> Mark as Processing
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($order->status === 'processing')
                                    <li>
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-truck"></i> Mark as Shipped
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($order->status === 'shipped')
                                    <li>
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-check-circle"></i> Mark as Delivered
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        No orders yet. Your products haven't been purchased.
    </div>
    @endforelse

    {{ $orders->links() }}
</div>
@endsection
