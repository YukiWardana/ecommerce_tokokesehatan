@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-4">🏪 {{ $shop->shop_name }} - Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">My Products</h5>
                    <h2>{{ $stats['products'] }}</h2>
                    <small>Total products in shop</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2>{{ $stats['orders'] }}</h2>
                    <small>Products sold</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <h2>{{ formatRupiah($stats['revenue']) }}</h2>
                    <small>Total earnings</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('seller.products.create') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Add New Product
                    </a>
                    <a href="{{ route('seller.products') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-box-seam"></i> Manage Products
                    </a>
                    <a href="{{ route('seller.orders') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-receipt"></i> View Orders
                    </a>
                    <a href="{{ route('seller.settings') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear"></i> Shop Settings
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @forelse($recentOrders as $order)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>Order #{{ $order->order_number }}</strong>
                                <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                            </div>
                            <small class="text-muted">{{ $order->user->name }}</small><br>
                            @foreach($order->orderItems as $item)
                                <small>• {{ $item->product->name }} x{{ $item->quantity }}</small><br>
                            @endforeach
                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No orders yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
