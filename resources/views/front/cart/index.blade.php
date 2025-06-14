@extends('front.layouts.app')
@section('meta_title', "Cart" )
@section('content')
<div class="container mx-auto px-4 py-8">
     @if($cart->items->count() <1)
    <h1 class="text-2xl mb-6 font-bold dark:text-white">Shopping Cart</h1>
    @endif
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
            @if($cart->items->count() > 0)
            <div class="flex justify-between items-center mb-6">
                
                
                <h1 class="text-2xl font-bold dark:text-white">Shopping Cart</h1>
                    <button type="button" onclick="clearCart()"
                            class="text-red-600 hover:text-red-700 flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Clear Cart</span>
                    </button>
               
            </div>
             @endif
            
            @if($cart->items->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    @foreach($cart->items as $item)
                        <div class="flex items-center p-6 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                            <div class="w-20 h-20">
                                <img src="{{ Helpers::image($item->product->main_image, 'products/') }}" 
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div class="flex-1 ml-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name }}</h3>
                                @if($item->options)
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        @foreach(json_decode($item->variations, true) as $variation)
                                            @if(isset($item->options[$variation['option_type_id']]))
                                                <div>{{ $variation['option_type_name'] }}: {{ $item->options[$variation['option_type_id']] }}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-2 text-purple-600 dark:text-purple-400">{{ Helpers::setCurrency($item->price) }}</div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <button type="button" 
                                            class="p-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                            onclick="updateQuantity({{ $item->id }}, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           value="{{ $item->quantity }}" 
                                           class="w-16 text-center border-0 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
                                           data-item-id="{{ $item->id }}"
                                           min="1">
                                    <button type="button" 
                                            class="p-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                            onclick="updateQuantity({{ $item->id }}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button type="button" 
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 transition-colors"
                                        onclick="removeItem({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">Your cart is empty</p>
                    <a href="{{ route('front.products.index') }}" 
                       class="inline-block mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4 dark:text-white">Order Summary</h2>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between" data-summary="subtotal-container">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="font-medium dark:text-white" data-summary="subtotal">{{ Helpers::setCurrency($cart->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between" data-summary="tax-container">
                        <span class="text-gray-600 dark:text-gray-400">Tax</span>
                        <span class="font-medium dark:text-white" data-summary="tax">{{ Helpers::setCurrency($cart->tax) }}</span>
                    </div>
                    @if($cart->discount > 0)
                        <div class="flex justify-between text-green-600" data-summary="discount-container">
                            <span>Discount</span>
                            <span data-summary="discount">-{{ Helpers::setCurrency($cart->discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-3">
                        <span class="text-lg font-bold dark:text-white">Total</span>
                        <span class="text-lg font-bold text-purple-600" data-summary="total">{{ Helpers::setCurrency($cart->total) }}</span>
                    </div>
                </div>

                @if($cart->items->count() > 0)
                    <a href="{{ route('front.checkout.index') }}" 
                       class="block w-full py-3 px-4 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700">
                        Proceed to Checkout
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
function updateQuantity(itemId, change) {
    const input = document.querySelector(`input[data-item-id="${itemId}"]`);
    const newQuantity = parseInt(input.value) + change;
    
    if (newQuantity < 1) return;
    
    fetch(`{{ route('front.cart.update', ':id') }}`.replace(':id', itemId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = newQuantity;
            updateOrderSummary(data.cart);
        }
    });
}

function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) return;
    
    fetch(`{{ route('front.cart.remove', '') }}/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error removing item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing item');
    });
}

function clearCart() {
    if (!confirm('Are you sure you want to remove all items from your cart?')) return;
    
    fetch(`{{ route('front.cart.clear') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error clearing cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error clearing cart');
    });
}

function updateOrderSummary(cart) {
    document.querySelector('[data-summary="subtotal"]').textContent = cart.subtotal;
    document.querySelector('[data-summary="tax"]').textContent = cart.tax;
    document.querySelector('[data-summary="total"]').textContent = cart.total;
    if (cart.discount) {
        document.querySelector('[data-summary="discount"]').textContent = `-${cart.discount}`;
    }
}
</script>
@endpush
@endsection
