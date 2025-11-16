<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-shipped { background-color: #e0e7ff; color: #3730a3; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .order-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Updated</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $order->user->name }},</p>
            
            <p>Your order status has been updated by the seller.</p>
            
            <div class="order-details">
                <h3>Order #{{ $order->order_number }}</h3>
                <p><strong>Previous Status:</strong> <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span></p>
                <p><strong>New Status:</strong> <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            </div>

            @if($order->status === 'processing')
                <p>Great news! Your order is now being processed and will be shipped soon.</p>
            @elseif($order->status === 'shipped')
                <p>Your order has been shipped and is on its way to you!</p>
            @elseif($order->status === 'delivered')
                <p>Your order has been delivered. We hope you enjoy your purchase!</p>
            @elseif($order->status === 'cancelled')
                <p>Your order has been cancelled. If you have any questions, please contact us.</p>
            @endif

            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                <p>Please find your invoice attached to this email.</p>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="button">View Order Details</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
