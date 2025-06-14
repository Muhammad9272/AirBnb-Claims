<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .header:after {
            content: "";
            display: table;
            clear: both;
        }
        .header-left {
            float: left;
            width: 50%;
        }
        .header-right {
            float: right;
            width: 50%;
            text-align: right;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 30px;
        }
        .info:after {
            content: "";
            display: table;
            clear: both;
        }
        .info-block {
            float: left;
            width: 50%;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 30px;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .success-text {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1 class="title">{{ $gs->title }}</h1>
                <p>{{ $gs->office_address ?? 'No address specified' }}</p>
                <p>{{ $gs->email }}</p>
                <p>{{ $gs->phone }}</p>
            </div>
            <div class="header-right">
                <h2>INVOICE</h2>
                <p>Invoice #{{ $order->order_number }}</p>
                <p>Status: {{ ucfirst($order->status) }}</p>
                <p>Date: {{ $order->created_at->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="info">
            <div class="info-block">
                <h3>Customer Information:</h3>
                <p>{{ $order->user->name ?? 'N/A' }}</p>
                <p>{{ $order->user->email ?? 'N/A' }}</p>
                <p>{{ $order->user->phone ?? 'N/A' }}</p>
            </div>
            <div class="info-block">
                <h3>Payment Details:</h3>
                <p>Payment Method: {{ ucfirst($order->payment_method) }}</p>
                <p>Payment Status: {{ ucfirst($order->payment_status) }}</p>
                @if($order->transaction)
                    <p>Transaction ID: {{ $order->transaction->transaction_id ?? 'N/A' }}</p>
                @endif
            </div>
        </div>

        <!-- Addresses -->
        <div class="info">
            <div class="info-block">
                <h3>Billing Address:</h3>
                <p>{{ $order->billing_name }}</p>
                <p>{{ $order->billing_email }}</p>
                <p>{{ $order->billing_phone }}</p>
                <p>{{ $order->billing_address }}</p>
                <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zipcode }}</p>
                <p>{{ $order->billing_country }}</p>
            </div>
            <div class="info-block">
                <h3>Shipping Address:</h3>
                <p>{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_email }}</p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}</p>
                <p>{{ $order->shipping_country }}</p>
            </div>
        </div>

        <!-- Item Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name ?? 'Unknown Product' }}</strong>
                            @if($variations = $item->getFormattedVariations())
                                <br>
                                @foreach($variations as $variation)
                                    <small>{{ $variation['name'] }}: {{ $variation['value'] }}</small><br>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-right">{{ Helpers::formatPrice($item->price) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ Helpers::formatPrice($item->price * $item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Subtotal</td>
                    <td class="text-right">{{ Helpers::formatPrice($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Tax</td>
                    <td class="text-right">{{ Helpers::formatPrice($order->tax) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Shipping</td>
                    <td class="text-right">{{ Helpers::formatPrice($order->shipping) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td colspan="3" class="text-right">Discount (Coupon)</td>
                    <td class="text-right success-text">-{{ Helpers::formatPrice($order->discount) }}</td>
                </tr>
                @endif
                @if($order->bits_discount > 0)
                <tr>
                    <td colspan="3" class="text-right">Bits Discount ({{ $order->bits_used }} Bits)</td>
                    <td class="text-right success-text">-{{ Helpers::formatPrice($order->bits_discount) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">{{ Helpers::formatPrice($order->total) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your order!</p>
            <p>{{ $gs->title }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
