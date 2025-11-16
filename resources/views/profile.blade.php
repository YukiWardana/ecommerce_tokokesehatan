@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .profile-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        font-weight: 600;
    }
    .form-control {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .btn-modern {
        border-radius: 12px;
        padding: 12px 30px;
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
    <div class="page-header">
        <h2 class="mb-0" style="font-weight: 700; color: #2c3e50;">
            <i class="bi bi-person-circle"></i> My Profile
        </h2>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="profile-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Account Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="bi bi-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>

            @if(!auth()->user()->isAdmin())
            <div class="profile-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shop"></i> Request Shop Creation</h5>
                </div>
                <div class="card-body">
                    <p>Want to become a seller? Submit a shop creation request.</p>
                    <form action="{{ route('shop-requests.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="shop_name" class="form-label">Shop Name</label>
                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                   id="shop_name" name="shop_name" required>
                            @error('shop_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success btn-modern">
                            <i class="bi bi-shop"></i> Submit Request
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
