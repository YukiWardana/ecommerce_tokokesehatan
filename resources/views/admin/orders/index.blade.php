@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container">
    <h2 class="mb-4">Manage Orders</h2>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ formatRupiah($order->total_amount) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(['pending', 'processing', 'shipped', 'delivered'] as $status)
                                            <li>
                                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" class="dropdown-item">
                                                        {{ ucfirst($status) }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
