<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sticky top-8">
                    <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cart->items as $item)
                            <div class="flex items-center space-x-4">
                                <img src="{{ Helpers::image($item->product->main_image, 'products/') }}" 
                                     alt="{{ $item->product->name }}"
                                     class="w-16 h-16 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</h3>
                                    @if($item->options)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @foreach(json_decode($item->variations, true) as $variation)
                                                @if(isset($item->options[$variation['option_type_id']]))
                                                    <div>{{ $variation['option_type_name'] }}: {{ $item->options[$variation['option_type_id']] }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="text-sm font-medium text-purple-600">{{ Helpers::setCurrency($item->price) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>


                     <!-- Bit Redemption Section -->
                    @auth
                        @if($userBitBalance > 0)
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="use_bits" class="text-sm font-medium text-gray-700 dark:text-gray-300">Use Bits ({{ $userBitBalance }} available)</label>
                                    <span id="bits-value" class="text-sm font-medium text-purple-600">{{ Helpers::setCurrency(0) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="range" id="use_bits" name="use_bits" min="0" max="{{ $userBitBalance }}" value="0"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                                    <span id="bits-display" class="text-sm font-medium text-gray-700 dark:text-gray-300 min-w-[40px] text-right">0</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Each bit is worth {{ Helpers::setCurrency($gs->bit_value) }} in discount</p>
                            </div>
                            
                            <input type="hidden" name="use_bits_hidden" id="use_bits_hidden" value="0">
                        @endif
                    @endauth

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                            <span id="summary-subtotal" class="font-medium text-gray-900 dark:text-white">{{ Helpers::setCurrency($cart->subtotal) }}</span>
                        </div>
                        
                        <!-- Bits discount will appear here -->
                        <div id="bits-discount-row" class="flex justify-between text-sm {{ isset($userBitBalance) && $userBitBalance > 0 ? 'hidden' : 'hidden' }}">
                            <span class="text-gray-600 dark:text-gray-400">Bits Discount</span>
                            <span id="bits-discount" class="font-medium text-green-600">-{{ Helpers::setCurrency(0) }}</span>
                        </div>
                        
                        @if($cart->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Coupon Discount</span>
                                <span class="font-medium text-green-600">-{{ Helpers::setCurrency($cart->discount) }}</span>
                            </div>
                        @endif
                        
                       {{--  <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                            <span id="summary-shipping" class="font-medium text-gray-900 dark:text-white">{{ Helpers::setCurrency($cart->shipping) }}</span>
                        </div> --}}
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Tax</span>
                            <span id="summary-tax" class="font-medium text-gray-900 dark:text-white">{{ Helpers::setCurrency($cart->tax) }}</span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-700 pt-4">
                            <span class="text-gray-900 dark:text-white">Total</span>
                            <span id="summary-total" class="text-purple-600">{{ Helpers::setCurrency($cart->total) }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" form="checkout-form"
                            class="w-full mt-6 bg-primary-gradient text-white px-6 py-3 rounded-lg flex items-center justify-center gap-2 
                                   hover:shadow-lg transition-all duration-300 group">
                        <span>Place Order</span>
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 transform group-hover:translate-x-1 transition-transform" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>

                    <!-- Security Notice -->
                    <div class="mt-6 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-lock mr-2"></i>
                        <span>Secure Checkout</span>
                    </div>
                </div>

@if(isset($userBitBalance) && $userBitBalance > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all required DOM elements
    const bitsSlider = document.getElementById('use_bits');
    const bitsDisplay = document.getElementById('bits-display');
    const bitsValue = document.getElementById('bits-value');
    const bitsDiscount = document.getElementById('bits-discount');
    const bitsDiscountRow = document.getElementById('bits-discount-row');
    const summaryTotal = document.querySelector('.text-purple-600'); // The total price element
    const useBitsHidden = document.getElementById('use_bits_hidden');
    
    // Store original values for calculations
    const subtotal = {{ $cart->subtotal }};
    const shipping = {{ $cart->shipping ?? 0 }};
    const tax = {{ $cart->tax  ?? 0 }};
    const discount = {{ $cart->discount  ?? 0 }};
    const total = {{ $cart->total }};
    const bitValue = {{ $gs->bit_value  ?? 0 }};
    const currencySymbol = '{{ Helpers::getCurrencySymbol() }}';
    
    // Calculate the maximum bits that can be used
    const maxAllowableDiscount = Math.max(0, subtotal - discount); // Don't let total discounts exceed subtotal
    const maxUsableBits = Math.min(
        {{ $userBitBalance }}, 
        Math.floor(maxAllowableDiscount / bitValue)
    );
    
    // Update the slider's max value to prevent over-discounting
    if (bitsSlider) {
        bitsSlider.max = maxUsableBits;
        bitsSlider.value = 0; // Reset to 0
        
        // Add input event listener to slider
        bitsSlider.addEventListener('input', function() {
            // Get the number of bits used from slider
            const bitsUsed = parseInt(this.value);
            
            // Calculate discount amount (limit to maxAllowableDiscount)
            const maxDiscount = maxAllowableDiscount;
            const discountAmount = Math.min(bitsUsed * bitValue, maxDiscount).toFixed(2);
            
            // Update bits display
            if (bitsDisplay) {
                bitsDisplay.textContent = bitsUsed;
            }
            
            // Update bits value display
            if (bitsValue) {
                bitsValue.textContent = currencySymbol + discountAmount;
            }
            
            // Update discount text
            if (bitsDiscount) {
                bitsDiscount.textContent = '-' + currencySymbol + discountAmount;
            }
            
            // Show/hide discount row
            if (bitsDiscountRow) {
                if (bitsUsed > 0) {
                    bitsDiscountRow.classList.remove('hidden');
                } else {
                    bitsDiscountRow.classList.add('hidden');
                }
            }
            
            // Update hidden field
            if (useBitsHidden) {
                useBitsHidden.value = bitsUsed;
            }
            
            // Calculate new total and update display
            if (summaryTotal) {
                // Ensure final total isn't negative
                const newTotal = Math.max(0, total - parseFloat(discountAmount)).toFixed(2);
                summaryTotal.textContent = currencySymbol + newTotal;
            }
            
            console.log('Bits used:', bitsUsed);
            console.log('Discount amount:', discountAmount);
            console.log('Max discount allowed:', maxAllowableDiscount);
            console.log('New total:', Math.max(0, total - parseFloat(discountAmount)).toFixed(2));
        });
    }
});
</script>
@endif
