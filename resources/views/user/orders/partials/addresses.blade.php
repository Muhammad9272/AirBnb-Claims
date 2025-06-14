<!-- Shipping Address -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">Shipping Address</h2>
    <div class="text-gray-600 dark:text-gray-400">
        <p>{{ $order->shipping_name }}</p>
        <p>{{ $order->shipping_address }}</p>
        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}</p>
        <p>{{ $order->shipping_country }}</p>
        <p class="mt-2">{{ $order->shipping_phone }}</p>
        <p>{{ $order->shipping_email }}</p>
    </div>
</div>

<!-- Billing Address -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">Billing Address</h2>
    <div class="text-gray-600 dark:text-gray-400">
        <p>{{ $order->billing_name }}</p>
        <p>{{ $order->billing_address }}</p>
        <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zipcode }}</p>
        <p>{{ $order->billing_country }}</p>
        <p class="mt-2">{{ $order->billing_phone }}</p>
        <p>{{ $order->billing_email }}</p>
    </div>
</div>
