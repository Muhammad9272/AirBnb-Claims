@extends('front.layouts.app')

@section('meta_title', 'Orders')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold dark:text-white">My Orders</h1>
                <a href="{{ route('front.products.index') }}" class="text-purple-600 hover:text-purple-700">
                    <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
                </a>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('front.products.index') }}" 
                       class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                            <!-- Order Header -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-semibold dark:text-white">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Placed on {{ $order->created_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium inline-block
                                            @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <p class="mt-2 font-medium text-lg dark:text-white">{{ Helpers::setCurrency($order->total) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-start gap-4">
                                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                                                <img src="{{ Helpers::image($item->product->main_image ?? '', 'products/') }}" 
                                                     alt="{{ $item->product_name }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium dark:text-white">{{ $item->product_name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Qty: {{ $item->quantity }} × {{ Helpers::setCurrency($item->price) }}
                                                </p>
                                                @if($variations = $item->getFormattedVariations())
                                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        @foreach($variations as $variation)
                                                            <span class="inline-flex items-center gap-1">
                                                                {{ $variation['name'] }}: 
                                                                <span class="font-medium">{{ $variation['value'] }}</span>
                                                                @if(!$loop->last) <span class="mx-1">•</span> @endif
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <span class="font-medium dark:text-white">
                                                    {{ Helpers::setCurrency($item->price * $item->quantity) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex justify-end">
                                    <a href="{{ route('user.orders.show', $order->order_number) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                        <span>View Details</span>
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
