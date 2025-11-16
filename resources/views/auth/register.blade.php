@extends('layouts.app')

@section('title', 'Register')

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        padding: 40px 0;
    }
    .auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        border: none;
        overflow: hidden;
    }
    .auth-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
    }
    .auth-card-header h4 {
        font-weight: 700;
        font-size: 2rem;
        margin: 0;
    }
    .auth-card-body {
        padding: 40px;
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
    .btn-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    .auth-link {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <div class="auth-card-header">
                        <h4><i class="bi bi-person-plus"></i> Create New Account</h4>
                    </div>
                    <div class="auth-card-body">
                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-auth text-white mb-4">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </form>

                        <hr>
                        <p class="text-center mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="auth-link">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
