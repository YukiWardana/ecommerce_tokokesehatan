@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Shop</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>
                                @if($product->shop)
                                    <span class="badge bg-info">{{ $product->shop->shop_name }}</span>
                                @else
                                    <span class="badge bg-secondary">Admin Product</span>
                                @endif
                            </td>
                            <td>{{ formatRupiah($product->price) }}</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No products found</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add First Product
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Products</h6>
                    <h3>{{ $products->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Active Products</h6>
                    <h3>{{ $products->where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Low Stock</h6>
                    <h3>{{ $products->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Out of Stock</h6>
                    <h3>{{ $products->where('stock', 0)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
