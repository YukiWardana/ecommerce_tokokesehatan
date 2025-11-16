@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        transition: all 0.3s ease;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .stat-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .stat-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }
    .stat-card.info {
        background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%);
        color: white;
    }
    .stat-card.secondary {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }
    .stat-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .stat-card h5 {
        font-weight: 600;
        margin-bottom: 15px;
        opacity: 0.9;
    }
    .stat-card h2 {
        font-weight: 700;
        font-size: 2.5rem;
    }
    .action-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .action-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .action-list {
        border: none;
    }
    .action-list .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 20px;
        transition: all 0.3s ease;
    }
    .action-list .list-group-item:last-child {
        border-bottom: none;
    }
    .action-list .list-group-item:hover {
        background: #f8f9fa;
        padding-left: 25px;
    }
    .action-list .list-group-item a {
        color: #2c3e50;
        text-decoration: none;
        font-weight: 500;
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
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">
            <i class="bi bi-speedometer2"></i> Admin Dashboard
        </h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="stat-card primary">
                <h5><i class="bi bi-box"></i> Total Products</h5>
                <h2>{{ $stats['products'] }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card success">
                <h5><i class="bi bi-bag-check"></i> Total Orders</h5>
                <h2>{{ $stats['orders'] }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card info">
                <h5><i class="bi bi-people"></i> Total Customers</h5>
                <h2>{{ $stats['customers'] }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card secondary">
                <h5><i class="bi bi-shop"></i> Total Shops</h5>
                <h2>{{ $stats['shops'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2"><i class="bi bi-clock-history"></i> Pending Shop Requests</h5>
                        <h2>{{ $stats['pending_requests'] }}</h2>
                    </div>
                    <a href="{{ route('admin.shop-requests.index') }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-right"></i> Review Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="action-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="list-group list-group-flush action-list">
                    <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-box"></i> Manage Products
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Add New Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Add New Category
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people"></i> Manage Customers
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-box-seam"></i> Manage Orders
                    </a>
                    <a href="{{ route('admin.shop-requests.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-shop"></i> Shop Requests
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="action-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Recent Orders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><span class="badge bg-primary">{{ $order->status }}</span></td>
                                    <td><strong>{{ formatRupiah($order->total_amount) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
