@extends('layouts.app')

@section('title', 'Shop Settings')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h2 class="mb-4">Shop Settings</h2>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="shop_name" class="form-label">Shop Name</label>
                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                   id="shop_name" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}" required>
                            @error('shop_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $shop->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $shop->location) }}" 
                                   placeholder="e.g., Jakarta, Indonesia">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Shop Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $shop->phone) }}" 
                                   placeholder="+62 812 3456 7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Shop Logo</label>
                            @if($shop->logo)
                                <div class="mb-2">
                                    <label class="form-label">Current Logo:</label>
                                    <div>
                                        <img src="{{ asset($shop->logo) }}" alt="{{ $shop->shop_name }}" 
                                             style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            <small class="text-muted">Leave empty to keep current logo</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                        <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
