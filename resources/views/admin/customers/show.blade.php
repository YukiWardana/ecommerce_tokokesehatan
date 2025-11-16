@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Details</h2>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Customers
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p>{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p>{{ $user->address ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Registered:</strong>
                        <p>{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Role:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($user->role) }}</span></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Orders:</strong>
                        <p class="h4">{{ $user->orders->count() }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Total Spent:</strong>
                        <p class="h4">{{ formatRupiah($user->orders->sum('total_amount')) }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Feedbacks Given:</strong>
                        <p class="h4">{{ $user->feedbacks->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order History</h5>
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($order->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->status === 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status === 'shipped')
                                                <span class="badge bg-primary">Shipped</span>
                                            @elseif($order->status === 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->orderItems->count() }} items</td>
                                        <td>{{ formatRupiah($order->total_amount) }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No orders yet.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Feedbacks</h5>
                </div>
                <div class="card-body">
                    @if($user->feedbacks->count() > 0)
                        <div class="list-group">
                            @foreach($user->feedbacks->take(5) as $feedback)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $feedback->product->name ?? 'Product' }}</h6>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $feedback->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mb-1">{{ $feedback->comment }}</p>
                                        <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No feedbacks yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
