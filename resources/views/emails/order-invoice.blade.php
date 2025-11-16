<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .header h2 {
            margin: 10px 0 0 0;
            font-size: 24px;
            font-weight: normal;
        }
        .invoice-box {
            border: 3px solid #0d6efd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            background-color: #f8f9ff;
        }
        .invoice-title {
            background-color: #0d6efd;
            color: white;
            padding: 12px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 5px 5px 0 0;
            font-size: 18px;
            font-weight: bold;
        }
        .invoice-details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 8px 5px;
        }
        .invoice-details .label {
            font-weight: bold;
            color: #0d6efd;
        }
        .section-header {
            background-color: #0d6efd;
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .customer-info {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .customer-info p {
            margin: 8px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .items-table th {
            background-color: #0d6efd;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
        .items-table tr:hover td {
            background-color: #f8f9fa;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.3em;
            background-color: #0d6efd !important;
            color: white !important;
        }
        .total-row td {
            padding: 15px 12px !important;
            border-bottom: none !important;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #0d6efd;
            color: white;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .alert-box {
            background-color: #d1ecf1;
            border-left: 5px solid #0c5460;
            padding: 20px;
            border-radius: 5px;
            margin: 25px 0;
        }
        .alert-box p {
            margin: 5px 0;
        }
        .attachment-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border-left: 5px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .attachment-notice strong {
            color: #856404;
        }
        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 3px solid #0d6efd;
            text-align: center;
            color: #666;
        }
        .footer p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🏥 Medical Tools Shop</h1>
            <h2>Order Invoice</h2>
        </div>

        <div class="invoice-box">
            <div class="invoice-title">📋 INVOICE DETAILS</div>
            
            <div class="invoice-details">
                <table>
                    <tr>
                        <td class="label">Invoice Number:</td>
                        <td><strong style="font-size: 1.1em;">{{ $order->order_number }}</strong></td>
                        <td class="label">Invoice Date:</td>
                        <td><strong>{{ $order->created_at->format('F d, Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Order Status:</td>
                        <td><span class="status-badge">{{ strtoupper($order->status) }}</span></td>
                        <td class="label">Payment Method:</td>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Payment Status:</td>
                        <td><strong>{{ ucfirst($order->payment_status) }}</strong></td>
                        <td class="label">Order Time:</td>
                        <td><strong>{{ $order->created_at->format('h:i A') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section-header">👤 CUSTOMER INFORMATION</div>
        <div class="customer-info">
            <p><strong style="color: #0d6efd;">Customer Name:</strong> {{ $order->user->name }}</p>
            <p><strong style="color: #0d6efd;">Email Address:</strong> {{ $order->user->email }}</p>
            <p><strong style="color: #0d6efd;">Phone Number:</strong> {{ $order->phone }}</p>
            <p><strong style="color: #0d6efd;">Shipping Address:</strong><br>{{ $order->shipping_address }}</p>
        </div>

        <div class="section-header">📦 ORDER ITEMS</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product</th>
                    <th style="width: 15%; text-align: right;">Price</th>
                    <th style="width: 15%; text-align: center;">Quantity</th>
                    <th style="width: 20%; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <strong style="font-size: 1.05em;">{{ $item->product->name }}</strong><br>
                        <small style="color: #666;">{{ $item->product->category->name }}</small>
                    </td>
                    <td style="text-align: right;">{{ formatRupiah($item->price) }}</td>
                    <td style="text-align: center;"><strong>{{ $item->quantity }}</strong></td>
                    <td style="text-align: right;"><strong>{{ formatRupiah($item->price) }}</strong></td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right; font-size: 1.2em;">💰 TOTAL AMOUNT:</td>
                    <td style="text-align: right; font-size: 1.3em;">{{ formatRupiah($item->price) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="alert-box">
            <p style="margin: 0;"><strong style="font-size: 1.1em;">📦 Your order is now being processed!</strong></p>
            <p style="margin: 10px 0 0 0;">We will notify you once your order has been shipped. You can track your order status in your account.</p>
        </div>

        <div class="attachment-notice">
            <p style="margin: 0;"><strong style="font-size: 1.1em;">📎 PDF Invoice Attached</strong></p>
            <p style="margin: 10px 0 0 0; color: #856404;">A printable PDF version of this invoice has been attached to this email for your records and accounting purposes.</p>
        </div>

        <div class="footer">
            <p style="font-size: 1.1em;"><strong>Thank you for shopping with Medical Tools Shop!</strong></p>
            <p>If you have any questions about your order, please don't hesitate to contact our customer support.</p>
            <p style="font-size: 0.9em; color: #999; margin-top: 20px;">
                This is an automated email. Please do not reply to this message.<br>
                Generated on: {{ now()->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
    </div>
</body>
</html>
