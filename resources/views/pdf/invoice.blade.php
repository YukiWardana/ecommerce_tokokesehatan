<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0d6efd;
        }
        .header h1 {
            color: #0d6efd;
            margin: 0;
            font-size: 32px;
        }
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 20px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-info .label {
            font-weight: bold;
            width: 150px;
        }
        .section-title {
            background-color: #0d6efd;
            color: white;
            padding: 10px;
            margin: 20px 0 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #0d6efd;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:last-child td {
            border-bottom: 2px solid #0d6efd;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 16px;
        }
        .total-row td {
            padding: 15px 10px !important;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #0d6efd;
            color: white;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .note-box {
            background-color: #d1ecf1;
            border-left: 4px solid #0c5460;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 MEDICAL TOOLS SHOP</h1>
        <h2>INVOICE</h2>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td class="label">Invoice Number:</td>
                <td><strong>{{ $order->order_number }}</strong></td>
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
                <td><strong>{{ $order->created_at->format('H:i:s') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="section-title">CUSTOMER INFORMATION</div>
    <div class="customer-info">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>Customer Name:</strong><br>
                    {{ $order->user->name }}
                </td>
                <td style="width: 50%;">
                    <strong>Email:</strong><br>
                    {{ $order->user->email }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <strong>Phone Number:</strong><br>
                    {{ $order->phone }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <strong>Shipping Address:</strong><br>
                    {{ $order->shipping_address }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">ORDER ITEMS</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 40%;">Product Name</th>
                <th style="width: 20%;">Category</th>
                <th style="width: 10%;" class="text-right">Price</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 10%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->product->name }}</strong></td>
                <td>{{ $item->product->category->name }}</td>
                <td class="text-right">{{ formatRupiah($item->price) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ formatRupiah($item->price) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL AMOUNT:</td>
                <td class="text-right">{{ formatRupiah($item->price) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="note-box">
        <strong>📦 Order Status: {{ ucfirst($order->status) }}</strong><br>
        @if($order->status === 'processing')
            Your order is currently being processed. We will notify you once it has been shipped.
        @elseif($order->status === 'shipped')
            Your order has been shipped and is on its way to you.
        @elseif($order->status === 'delivered')
            Your order has been successfully delivered.
        @else
            Thank you for your order!
        @endif
    </div>

    <div class="footer">
        <p><strong>Thank you for shopping with Medical Tools Shop!</strong></p>
        <p>For any inquiries, please contact our customer support.</p>
        <p style="margin-top: 10px;">
            This is a computer-generated invoice and does not require a signature.
        </p>
        <p style="margin-top: 5px; font-size: 10px;">
            Generated on: {{ now()->format('F d, Y H:i:s') }}
        </p>
    </div>
</body>
</html>
