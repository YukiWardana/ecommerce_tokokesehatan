@extends('layouts.app')

@section('title', 'Manage Customers')

@section('content')
<div class="container">
    <h2 class="mb-4">Manage Customer Database</h2>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>{{ $customer->orders_count }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this customer?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
