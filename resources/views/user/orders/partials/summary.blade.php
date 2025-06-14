<div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
    <!-- Order Items -->
    <div class="space-y-4">
        @foreach($order->orderItems as $item)
            <div class="flex items-start gap-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                    <img src="{{ Helpers::image($item->product->main_image ?? '', 'products/') }}" 
                         alt="{{ $item->product_name }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h4 class="font-medium dark:text-white">{{ $item->product_name }}</h4>
                    @if($variations = $item->getFormattedVariations())
                        <div class="mt-1 space-y-1">
                            @foreach($variations as $variation)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">{{ $variation['name'] }}:</span>
                                    <span class="ml-2">{{ $variation['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ Helpers::formatPrice($item->price) }} × {{ $item->quantity }}
                    </div>
                </div>
                <div class="text-right">
                    <span class="font-medium text-gray-900 dark:text-white">
                        {{ Helpers::formatPrice($item->price * $item->quantity) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Order Totals -->
    <div class="space-y-2 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->subtotal) }}</span>
        </div>
        
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Shipping</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->shipping) }}</span>
        </div>
        
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Tax</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->tax) }}</span>
        </div>
        
        @if($order->discount > 0)
            <div class="flex justify-between text-sm text-green-600">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                    </svg>
                    Coupon Discount
                </span>
                <span>-{{ Helpers::formatPrice($order->discount) }}</span>
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
                <span>-{{ Helpers::formatPrice($order->bits_discount) }}</span>
            </div>
        @endif
        
        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
            <span class="font-bold dark:text-white">Total</span>
            <span class="font-bold text-purple-600">{{ Helpers::formatPrice($order->grand_total) }}</span>
        </div>
    </div>
</div>
