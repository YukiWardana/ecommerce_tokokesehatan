@extends('layouts.app')

@section('title', 'Guestbook')

@push('styles')
<style>
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .entry-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }
    .entry-card:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    .form-card .card-header {
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
        padding: 12px;
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
            <i class="bi bi-book"></i> Guestbook
        </h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h4 class="mb-4" style="font-weight: 600; color: #2c3e50;">Recent Entries</h4>
            @forelse($entries as $entry)
            <div class="entry-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1" style="font-weight: 600; color: #2c3e50;">{{ $entry->name }}</h5>
                        <p class="text-muted small mb-0"><i class="bi bi-envelope"></i> {{ $entry->email }}</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted"><i class="bi bi-clock"></i> {{ $entry->created_at->diffForHumans() }}</small>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('admin.guestbook.destroy', $entry) }}" method="POST" class="d-inline mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-modern"
                                            onclick="return confirm('Delete this entry?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
                <p class="mb-0" style="color: #555; line-height: 1.6;">{{ $entry->message }}</p>
            </div>
            @empty
            <div class="entry-card text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <h5 style="color: #2c3e50;">No entries yet</h5>
                <p class="text-muted">Be the first to leave a message!</p>
            </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $entries->links() }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> Leave a Message</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('guestbook.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
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
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-modern w-100">
                            <i class="bi bi-send"></i> Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
