@extends('email.emailbody')

@section('email-content')
<div style="padding: 30px; background-color: #f8fafc; border-radius: 10px; margin-bottom: 20px;">
    <h2 style="color: #1a1a1a; margin-bottom: 20px;">Order Confirmation</h2>
    <p style="color: #4b5563; margin-bottom: 20px;">
        Dear {{ $data['billing']['name'] }},<br><br>
        Thank you for your order! We're pleased to confirm that your order has been received and is being processed.
    </p>

    <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #4f46e5; margin-bottom: 15px;">Order Details #{{ $data['order']->order_number }}</h3>
        
        <!-- Order Items -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">
                    <th style="text-align: left; padding: 10px;">Item</th>
                    <th style="text-align: right; padding: 10px;">Quantity</th>
                    <th style="text-align: right; padding: 10px;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['items'] as $item)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 10px;">{{ $item->product_name }}</td>
                        <td style="text-align: right; padding: 10px;">{{ $item->quantity }}</td>
                        <td style="text-align: right; padding: 10px;">{{ Helpers::formatPrice($item->price * $item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 10px;">Subtotal:</td>
                    <td style="text-align: right; padding: 10px;">{{ Helpers::formatPrice($data['order']->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 10px;">Tax:</td>
                    <td style="text-align: right; padding: 10px;">{{ Helpers::formatPrice($data['order']->tax) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 10px;">Shipping:</td>
                    <td style="text-align: right; padding: 10px;">{{ Helpers::formatPrice($data['order']->shipping) }}</td>
                </tr>
                @if($data['order']->discount > 0)
                    <tr>
                        <td colspan="2" style="text-align: right; padding: 10px;">Discount:</td>
                        <td style="text-align: right; padding: 10px; color: #22c55e;">-{{ Helpers::formatPrice($data['order']->discount) }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" style="text-align: right; padding: 10px; font-weight: bold;">Total:</td>
                    <td style="text-align: right; padding: 10px; font-weight: bold;">{{ Helpers::formatPrice($data['order']->total) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Shipping Address -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #4f46e5; margin-bottom: 10px;">Shipping Address</h4>
            <p style="color: #4b5563; margin: 0;">
                {{ $data['shipping']['name'] }}<br>
                {{ $data['shipping']['address'] }}<br>
                {{ $data['shipping']['city'] }}, {{ $data['shipping']['state'] }} {{ $data['shipping']['zipcode'] }}<br>
                {{ $data['shipping']['country'] }}
            </p>
        </div>
    </div>

    <p style="color: #4b5563; margin-bottom: 20px;">
        You can track your order status by logging into your account.
    </p>

    <div style="text-align: center;">
        <a href="{{ route('front.orders.show', $data['order']->order_number) }}" 
           style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 12px 24px; 
                  text-decoration: none; border-radius: 6px; font-weight: bold;">
            View Order Details
        </a>
    </div>
</div>
@endsection
