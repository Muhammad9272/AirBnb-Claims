@extends('front.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Order Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('F d, Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Items -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Order Items</h2>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($order->orderItems as $item)
                            <div class="py-4 flex items-center">
                                <div class="flex-1">
                                    <h3 class="font-medium dark:text-white">{{ $item->product_name }}</h3>
                                    @if($item->variations && $item->options)
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            @foreach(json_decode($item->variations, true) as $variation)
                                                @if(isset($item->options[$variation['option_type_id']]))
                                                    <div>{{ $variation['option_type_name'] }}: {{ $item->options[$variation['option_type_id']] }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-medium dark:text-white">{{ Helpers::setCurrency($item->price) }} × {{ $item->quantity }}</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ Helpers::setCurrency($item->price * $item->quantity) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Addresses -->
                <div class="grid md:grid-cols-2 gap-6">
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
                </div>
            </div>

            <!-- Order Summary -->
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sticky top-8">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Order Summary</h2>
                    @include('front.orders.partials.summary', ['order' => $order])
                    
                    <!-- Payment Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                                <span class="dark:text-white">{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Payment Status</span>
                                <span class="font-medium 
                                    @if($order->payment_status === 'completed') text-green-600
                                    @elseif($order->payment_status === 'pending') text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
