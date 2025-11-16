@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .breadcrumb {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    .product-detail-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .product-image-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .product-image-container img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }
    .product-image-placeholder {
        height: 500px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 2.5rem;
    }
    .product-category {
        display: inline-block;
        padding: 8px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        text-decoration: none;
    }
    .product-price {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 20px 0;
    }
    .shop-info-card {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border: 2px solid #667eea;
        border-radius: 16px;
        padding: 20px;
        margin: 20px 0;
    }
    .review-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
    }
    .review-form-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
    }
    .btn-modern {
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .input-group-modern .form-control {
        border-radius: 12px 0 0 12px;
        border: 2px solid #e0e0e0;
        padding: 12px 20px;
    }
    .input-group-modern .btn {
        border-radius: 0 12px 12px 0;
    }
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 10px;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 2.5rem;
        color: #ddd;
        transition: all 0.2s;
    }
    .rating-input input[type="radio"]:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: #ffc107;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="product-detail-card">
        <div class="row">
            <div class="col-md-5">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="product-image-placeholder">
                            <i class="bi bi-box display-1 text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-7">
                <h1 class="product-title">{{ $product->name }}</h1>
                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="product-category">
                    <i class="bi bi-tag"></i> {{ $product->category->name }}
                </a>
                
                <div class="product-price">{{ formatRupiah($product->price) }}</div>
                
                @if($product->shop)
                    <div class="shop-info-card">
                        <h6 class="mb-2"><i class="bi bi-shop"></i> <strong>Sold by:</strong> {{ $product->shop->shop_name }}</h6>
                        @if($product->shop->location)
                            <p class="mb-0"><i class="bi bi-geo-alt"></i> {{ $product->shop->location }}</p>
                        @endif
                    </div>
                @endif
                
                <p class="lead mb-4" style="color: #555;">{{ $product->description }}</p>

                <div class="mb-4">
                    <strong style="color: #2c3e50;">Availability:</strong> 
                    @if($product->stock > 0)
                        <span class="badge bg-success" style="padding: 10px 20px; font-size: 1rem;">
                            <i class="bi bi-check-circle"></i> In Stock ({{ $product->stock }} available)
                        </span>
                    @else
                        <span class="badge bg-danger" style="padding: 10px 20px; font-size: 1rem;">
                            <i class="bi bi-x-circle"></i> Out of Stock
                        </span>
                    @endif
                </div>

                @auth
                    @if(!auth()->user()->isAdmin())
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="input-group input-group-modern" style="max-width: 300px;">
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="submit" class="btn btn-primary btn-modern">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-modern">
                            <i class="bi bi-pencil"></i> Edit Product
                        </a>
                    @endif
                @else
                    <div class="alert alert-info" style="border-radius: 12px; border: none;">
                        <i class="bi bi-info-circle"></i> <a href="{{ route('login') }}" style="color: #667eea; font-weight: 600;">Login</a> to add this product to your cart.
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="product-detail-card">
        <h4 style="font-weight: 700; color: #2c3e50; margin-bottom: 25px;">
            <i class="bi bi-star"></i> Customer Reviews ({{ $product->feedbacks->count() }})
        </h4>
        
        @auth
            @if(!auth()->user()->isAdmin())
                <div class="review-form-card">
                    <h5 class="mb-3" style="font-weight: 600;">Leave a Review</h5>
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <div class="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                                    <label for="star{{ $i }}">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold">Your Review (Optional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Share your experience with this product..." 
                                      style="border-radius: 12px; border: 2px solid #e0e0e0;"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="bi bi-send"></i> Submit Review
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="alert alert-info" style="border-radius: 12px; border: none;">
                <i class="bi bi-info-circle"></i> <a href="{{ route('login') }}" style="color: #667eea; font-weight: 600;">Login</a> to leave a review for this product.
            </div>
        @endauth

        @forelse($product->feedbacks as $feedback)
            <div class="review-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color: #2c3e50;">{{ $feedback->user->name }}</strong>
                        <small class="text-muted d-block">{{ $feedback->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="text-warning" style="font-size: 1.2rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $feedback->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                </div>
                @if($feedback->comment)
                    <p class="mb-0" style="color: #555;">{{ $feedback->comment }}</p>
                @endif
            </div>
        @empty
            <div class="alert alert-light" style="border-radius: 12px; border: 2px dashed #ddd;">
                <i class="bi bi-chat-dots"></i> No reviews yet. Be the first to review this product!
            </div>
        @endforelse
    </div>
</div>
@endsection
