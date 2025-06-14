@extends('front.layouts.app')
@section('meta_title', 'Order Details')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <!-- Order Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold dark:text-white">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($order->status === 'completed') bg-green-100 text-green-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <!-- Order Details & Items -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4 dark:text-white">Order Items</h2>
                        @include('user.orders.partials.summary', ['order' => $order])
                    </div>

                    <!-- Order Summary Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4 dark:text-white">Order Summary</h2>
                        
                        <!-- Order Totals -->
                        <div class="space-y-2 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="dark:text-white">{{ Helpers::setCurrency($order->subtotal) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <span class="dark:text-white">{{ Helpers::setCurrency($order->shipping) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                <span class="dark:text-white">{{ Helpers::setCurrency($order->tax) }}</span>
                            </div>
                            
                            @if($order->discount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                                        </svg>
                                        Coupon Discount
                                    </span>
                                    <span>-{{ Helpers::setCurrency($order->discount) }}</span>
                                </div>
                            @endif
                            
                            @if($order->bits_discount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd" />
                                        </svg>
                                        Bits Discount
                                        <span class="ml-1 text-xs">({{ $order->bits_used }} bits)</span>
                                    </span>
                                    <span>-{{ Helpers::setCurrency($order->bits_discount) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="font-bold dark:text-white">Total</span>
                                <span class="font-bold text-purple-600">{{ Helpers::setCurrency($order->total) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses -->
                    <div class="grid md:grid-cols-2 gap-6">
                        @include('user.orders.partials.addresses', ['order' => $order])
                    </div>
                </div>

                <!-- Payment Info -->
                <div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sticky top-8">
                        <h2 class="text-lg font-semibold mb-4 dark:text-white">Payment Information</h2>
                        <div class="space-y-4">
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
