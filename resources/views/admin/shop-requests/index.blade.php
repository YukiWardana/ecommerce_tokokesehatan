@extends('layouts.app')

@section('title', 'Shop Creation Requests')

@section('content')
<div class="container">
    <h2 class="mb-4">Shop Creation Requests</h2>

    <div class="card">
        <div class="card-body">
            @forelse($requests as $request)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $request->shop_name }}</strong>
                    <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <p><strong>Requested by:</strong> {{ $request->user->name }} ({{ $request->user->email }})</p>
                    <p><strong>Description:</strong> {{ $request->description }}</p>
                    <p><strong>Submitted:</strong> {{ $request->created_at->format('M d, Y H:i') }}</p>
                    
                    @if($request->admin_notes)
                        <p><strong>Admin Notes:</strong> {{ $request->admin_notes }}</p>
                    @endif

                    @if($request->status == 'pending')
                        <form action="{{ route('admin.shop-requests.approve', $request) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#rejectModal{{ $request->id }}">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.shop-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="admin_notes" class="form-label">Reason for rejection</label>
                                                <textarea class="form-control" name="admin_notes" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="alert alert-info">No shop creation requests.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
