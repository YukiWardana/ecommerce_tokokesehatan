@extends('layouts.app')

@section('title', 'Login')

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
    .form-check-input {
        border-radius: 6px;
        border: 2px solid #e0e0e0;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
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
            <div class="col-md-5">
                <div class="auth-card">
                    <div class="auth-card-header">
                        <h4><i class="bi bi-box-arrow-in-right"></i> Login</h4>
                    </div>
                    <div class="auth-card-body">
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-auth text-white mb-4">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>

                        <hr>
                        <p class="text-center mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="auth-link">Create New Account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
