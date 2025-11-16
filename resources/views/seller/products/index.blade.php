@extends('layouts.app')

@section('title', 'My Products')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Products</h2>
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
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
                                    <img src="{{ asset($product->image) }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-box"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ formatRupiah($product->price) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No products yet. Add your first product!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
