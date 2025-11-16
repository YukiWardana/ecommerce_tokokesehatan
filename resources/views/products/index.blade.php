@extends('layouts.app')

@section('title', 'Products')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .filter-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .filter-list {
        border: none;
    }
    .filter-list .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 20px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .filter-list .list-group-item:last-child {
        border-bottom: none;
    }
    .filter-list .list-group-item:hover {
        background: #f8f9fa;
        padding-left: 25px;
    }
    .filter-list .list-group-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .product-image {
        height: 220px;
        object-fit: cover;
        width: 100%;
    }
    .product-image-placeholder {
        height: 220px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-card .card-body {
        padding: 20px;
    }
    .product-card h5 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 10px 0;
    }
    .btn-modern {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 4px;
        border: none;
        color: #667eea;
        padding: 10px 16px;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">Products</h2>
                <p class="text-muted mb-0 mt-2">Discover our wide range of medical equipment</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-modern">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter by Category</h5>
                </div>
                <div class="list-group list-group-flush filter-list">
                    <a href="{{ route('products.index') }}" 
                       class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i> All Products
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" 
                       class="list-group-item list-group-item-action {{ request('category') == $cat->id ? 'active' : '' }}">
                        <i class="bi bi-tag"></i> {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="product-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image-placeholder">
                                <i class="bi bi-box display-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-light text-dark mb-2" style="width: fit-content;">{{ $product->category->name }}</span>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                            <div class="mt-auto">
                                <div class="product-price">{{ formatRupiah($product->price) }}</div>
                                @if($product->shop)
                                    <p class="text-muted mb-2 small">
                                        <i class="bi bi-shop"></i> {{ $product->shop->shop_name }}
                                    </p>
                                @endif
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm btn-modern flex-fill">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                    @auth
                                        @if(!auth()->user()->isAdmin() && $product->stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm btn-modern w-100">
                                                    <i class="bi bi-cart-plus"></i> Add
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info" style="border-radius: 16px; border: none;">
                        <i class="bi bi-info-circle"></i> No products found.
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->appends(['category' => request('category')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
