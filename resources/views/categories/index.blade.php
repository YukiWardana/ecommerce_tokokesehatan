@extends('layouts.app')

@section('title', 'Browse Categories')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .category-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .category-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .category-image-placeholder {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .category-card .card-body {
        padding: 25px;
    }
    .category-card h5 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
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
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">Browse Categories</h2>
                <p class="text-muted mb-0 mt-2">Explore our product categories</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-modern">
                        <i class="bi bi-plus-circle"></i> Add Category
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="row">
        @forelse($categories as $category)
        <div class="col-md-4 mb-4">
            <div class="category-card">
                @if($category->image)
                    <img src="{{ $category->image }}" class="category-image" alt="{{ $category->name }}">
                @else
                    <div class="category-image-placeholder">
                        <i class="bi bi-image display-1 text-white"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="card-text text-muted">{{ $category->description }}</p>
                    <p class="text-muted mb-3">
                        <small><i class="bi bi-box"></i> {{ $category->products_count }} products</small>
                    </p>
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="btn btn-primary btn-modern w-100">
                        <i class="bi bi-arrow-right"></i> View Products
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="btn btn-sm btn-outline-secondary btn-modern flex-fill">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-modern w-100"
                                            onclick="return confirm('Delete this category?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info" style="border-radius: 16px; border: none;">
                <i class="bi bi-info-circle"></i> No categories available.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
