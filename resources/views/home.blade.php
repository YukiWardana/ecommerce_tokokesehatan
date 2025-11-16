@extends('layouts.app')

@section('title', 'Home - Medical Tools Shop')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 80px 40px;
        color: white;
        margin-bottom: 60px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    .hero-section .lead {
        font-size: 1.3rem;
        margin-bottom: 30px;
        opacity: 0.95;
    }
    .btn-hero {
        background: white;
        color: #667eea;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }
    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        color: #764ba2;
    }
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }
    .feature-card i {
        font-size: 4rem;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .feature-card h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    .category-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .category-card h5 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .section-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 40px;
        position: relative;
        padding-bottom: 15px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="hero-section">
        <div class="hero-content text-center">
            <h1>Welcome to Medical Tools Shop</h1>
            <p class="lead">Your trusted source for quality medical equipment and supplies</p>
            <p class="mb-4">Browse our extensive catalog of medical tools, diagnostic equipment, and healthcare supplies.</p>
            <a class="btn btn-hero" href="{{ route('products.index') }}" role="button">
                <i class="bi bi-shop"></i> Shop Now
            </a>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-box-seam"></i>
                <h5>Wide Selection</h5>
                <p class="text-muted">Browse through our extensive collection of medical tools and equipment.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-shield-check"></i>
                <h5>Quality Assured</h5>
                <p class="text-muted">All products are certified and meet international quality standards.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-truck"></i>
                <h5>Fast Delivery</h5>
                <p class="text-muted">Quick and reliable shipping to your doorstep.</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h2 class="section-title">Featured Categories</h2>
        </div>
        @foreach($categories as $category)
        <div class="col-md-3 mb-4">
            <div class="category-card">
                <h5>{{ $category->name }}</h5>
                <p class="text-muted small mb-3">{{ Str::limit($category->description, 60) }}</p>
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-sm btn-primary w-100">
                    View Products <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
