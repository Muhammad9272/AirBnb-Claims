<div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
    <!-- Order Items -->
    <div class="space-y-4 mb-6">
        @foreach($order->orderItems as $item)
            <div class="flex justify-between">
                <div class="flex-grow">
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600 dark:text-gray-400">{{ $item->quantity }}x</span>
                        <div>
                            <span class="dark:text-white">{{ $item->product_name }}</span>
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
                    </div>
                </div>
                <span class="dark:text-white">{{ Helpers::formatPrice($item->price * $item->quantity) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Order Totals -->
    <div class="space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->subtotal) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Tax</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->tax) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Shipping</span>
            <span class="dark:text-white">{{ Helpers::formatPrice($order->shipping) }}</span>
        </div>
        @if($order->discount > 0)
            <div class="flex justify-between text-sm text-green-600">
                <span>Discount</span>
                <span>-{{ Helpers::formatPrice($order->discount) }}</span>
            </div>
        @endif
        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
            <span class="font-bold dark:text-white">Total</span>
            <span class="font-bold text-purple-600">{{ Helpers::formatPrice($order->total) }}</span>
        </div>
    </div>
</div>
