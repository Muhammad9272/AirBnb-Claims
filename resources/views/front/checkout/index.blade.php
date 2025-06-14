@extends('front.layouts.app')
@section('meta_title', "Checkout" )
@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12 transition-colors duration-300">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Checkout Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Checkout</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Complete your purchase securely</p>
        </div>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Please correct the following errors:
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column - Forms -->
            <div class="flex-1">
                <form id="checkout-form" method="POST" action="{{ route('front.checkout.process') }}" class="space-y-8">
                    @csrf
                    <!-- Hidden field for bit redemption -->
                    <input type="hidden" name="use_bits" id="use_bits_hidden" value="0">
                    
                    <!-- Shipping Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6" style="margin-top: 0px;">
                        <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                            <span class="w-8 h-8 inline-flex items-center justify-center bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full mr-2">1</span>
                            Shipping Information
                        </h2>
                        @include('front.checkout.partials.shipping-form')
                    </div>

                    <!-- Billing Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white flex items-center justify-between">
                            <span class="flex items-center">
                                <span class="w-8 h-8 inline-flex items-center justify-center bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full mr-2">2</span>
                                Billing Information
                            </span>
                            <label class="flex items-center text-sm font-normal">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600" id="same-as-shipping">
                                <span class="ml-2">Same as shipping</span>
                            </label>
                        </h2>
                        @include('front.checkout.partials.billing-form')
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                            <span class="w-8 h-8 inline-flex items-center justify-center bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full mr-2">3</span>
                            Payment Method
                        </h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Payment method options -->
                                <label class="relative border-2 border-purple-500 dark:border-purple-500 rounded-lg p-4 flex cursor-pointer">
                                    <input type="radio" name="payment_method" value="stripe" class="sr-only" checked>
                                    <div class="w-full flex items-center">
                                        <span class="h-4 w-4 rounded-full border border-purple-500 bg-purple-500 flex-shrink-0 mr-2"></span>
                                        <span class="flex items-center text-sm">
                                            <i class="fas fa-credit-card text-purple-600 mr-3"></i>
                                            Credit/Debit Card
                                        </span>
                                    </div>
                                </label>
                                <!-- Add other payment methods with unchecked state -->
                               <label class="relative border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4 flex cursor-pointer hover:border-purple-500 transition-colors">
                                    <input type="radio" name="payment_method" value="paypal" class="sr-only">
                                    <div class="w-full flex items-center">
                                        <span class="h-4 w-4 rounded-full border border-gray-300 flex-shrink-0 mr-2"></span>
                                        <span class="flex items-center text-sm">
                                            <i class="fab fa-paypal text-blue-600 mr-3"></i>
                                            PayPal
                                        </span>
                                    </div>
                                </label> 
                            </div>
                            
                            <!-- Payment Details Form -->
                            <div id="payment-details" class="mt-6">
                                <!-- Stripe/PayPal form fields -->
                            </div>
                        </div>
                    </div>

                    <!-- Add before the submit button -->
                    <div class="mb-6">
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex items-center mb-4">
                                <input type="checkbox" 
                                       id="warranty_confirmed" 
                                       name="warranty_confirmed" 
                                       required
                                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="warranty_confirmed" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    I have read and understand the 
                                    <a href="{{ route('front.help.warranty') }}" 
                                       target="_blank"
                                       class="text-purple-600 hover:text-purple-700 underline">
                                        warranty terms
                                    </a>
                                    and agree to them.
                                </label>
                            </div>
                            @error('warranty_confirmed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:w-1/3">
               @include('front.checkout.partials.order-summary')
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup for "Same as shipping" checkbox
    const sameAsShippingCheckbox = document.getElementById('same-as-shipping');
    const checkoutForm = document.getElementById('checkout-form');
    
    // Function to copy shipping fields to billing fields
    function copyShippingToBilling() {
        const fields = {
            'name': ['shipping_name', 'billing_name'],
            'email': ['shipping_email', 'billing_email'],
            'phone': ['shipping_phone', 'billing_phone'],
            'address': ['shipping_address', 'billing_address'],
            'city': ['shipping_city', 'billing_city'],
            'state': ['shipping_state', 'billing_state'],
            'zipcode': ['shipping_zipcode', 'billing_zipcode'],
            'country': ['shipping_country', 'billing_country']
        };

        Object.values(fields).forEach(pair => {
            const shippingField = document.getElementById(pair[0]);
            const billingField = document.getElementById(pair[1]);
            
            if (shippingField && billingField) {
                billingField.value = shippingField.value;
                // Important: Don't disable fields, just make them readonly if checked
                if (sameAsShippingCheckbox.checked) {
                    billingField.setAttribute('readonly', true);
                    billingField.classList.add('bg-gray-100', 'dark:bg-gray-700');
                } else {
                    billingField.removeAttribute('readonly');
                    billingField.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                }
            }
        });
    }

    // Initial run on page load for the checkbox
    if (sameAsShippingCheckbox && sameAsShippingCheckbox.checked) {
        copyShippingToBilling();
    }

    // Event listener for checkbox change
    sameAsShippingCheckbox.addEventListener('change', copyShippingToBilling);
    
    // Event listeners for shipping fields to update billing when checkbox is checked
    const shippingFields = [
        'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_state', 'shipping_zipcode', 'shipping_country'
    ];
    
    shippingFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                if (sameAsShippingCheckbox.checked) {
                    copyShippingToBilling();
                }
            });
        }
    });

    // Before submitting the form, ensure billing fields are filled
    checkoutForm.addEventListener('submit', function(e) {
        if (sameAsShippingCheckbox.checked) {
            // Copy shipping values to billing fields before submission
            copyShippingToBilling();
            
            // Remove readonly attribute for submission
            const billingFields = document.querySelectorAll('[id^="billing_"]');
            billingFields.forEach(field => {
                field.removeAttribute('readonly');
            });
        }
    });

    // Handle payment method radio buttons
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            paymentMethods.forEach(m => {
                const parent = m.closest('label');
                if (m.checked) {
                    parent.classList.remove('border-gray-200', 'dark:border-gray-700');
                    parent.classList.add('border-purple-500', 'dark:border-purple-500');
                    parent.querySelector('span:first-child').classList.add('bg-purple-500');
                } else {
                    parent.classList.remove('border-purple-500', 'dark:border-purple-500');
                    parent.classList.add('border-gray-200', 'dark:border-gray-700');
                    parent.querySelector('span:first-child').classList.remove('bg-purple-500');
                }
            });
        });
    });

    // Ensure first payment method is selected on page load
    if (paymentMethods.length > 0) {
        paymentMethods[0].checked = true;
        const event = new Event('change');
        paymentMethods[0].dispatchEvent(event);
    }
    
    // Add client-side validation
    checkoutForm.addEventListener('submit', function(e) {
        let hasError = false;
        
        // Validate shipping fields
        shippingFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorMessage = field.nextElementSibling;
            
            if (!field.value.trim()) {
                e.preventDefault();
                hasError = true;
                field.classList.add('border-red-500');
                errorMessage.classList.remove('hidden');
                errorMessage.textContent = `This field is required`;
            } else {
                field.classList.remove('border-red-500');
                errorMessage.classList.add('hidden');
            }
            
            // Email validation
            if (field.type === 'email' && field.value.trim()) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(field.value.trim())) {
                    e.preventDefault();
                    hasError = true;
                    field.classList.add('border-red-500');
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = `Please enter a valid email address`;
                }
            }
        });
        
        // If using "Same as shipping", skip billing validation
        if (!sameAsShippingCheckbox.checked) {
            // Validate billing fields
            const billingFields = [
                'billing_name', 'billing_email', 'billing_phone', 'billing_address',
                'billing_city', 'billing_state', 'billing_zipcode', 'billing_country'
            ];
            
            billingFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const errorMessage = field.nextElementSibling;
                
                if (!field.value.trim()) {
                    e.preventDefault();
                    hasError = true;
                    field.classList.add('border-red-500');
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = `This field is required`;
                } else {
                    field.classList.remove('border-red-500');
                    errorMessage.classList.add('hidden');
                }
                
                // Email validation
                if (field.type === 'email' && field.value.trim()) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(field.value.trim())) {
                        e.preventDefault();
                        hasError = true;
                        field.classList.add('border-red-500');
                        errorMessage.classList.remove('hidden');
                        errorMessage.textContent = `Please enter a valid email address`;
                    }
                }
            });
        }
        
        if (hasError) {
            // Scroll to the first error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Update hidden bit field when slider changes
    const bitsSlider = document.getElementById('use_bits');
    const bitsHidden = document.getElementById('use_bits_hidden');
    
    if (bitsSlider && bitsHidden) {
        bitsSlider.addEventListener('input', function() {
            bitsHidden.value = this.value;
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bitsSlider = document.getElementById('use_bits');
    const bitsDisplay = document.getElementById('bits-display');
    const bitsValue = document.getElementById('bits-value');
    const bitsDiscount = document.getElementById('bits-discount');
    const bitsDiscountRow = document.getElementById('bits-discount-row');
    const summaryTotal = document.getElementById('summary-total');
    const useBitsHidden = document.getElementById('use_bits_hidden');
    
    const subtotal = {{ $cart->subtotal }};
    const shipping = {{ $cart->shipping?? 0 }};
    const tax = {{ $cart->tax?? 0 }};
    const discount = {{ $cart->discount }};
    const total = {{ $cart->total }};
    const bitValue = {{ $gs->bit_value }};
    
    if (bitsSlider) {
        bitsSlider.addEventListener('input', function() {
            const bitsUsed = parseInt(this.value);
            const discountAmount = (bitsUsed * bitValue).toFixed(2);
            
            // Update display elements
            bitsDisplay.textContent = bitsUsed;
            bitsValue.textContent = '{{ Helpers::getCurrencySymbol() }}' + discountAmount;
            bitsDiscount.textContent = '-{{ Helpers::getCurrencySymbol() }}' + discountAmount;
            
            // Update hidden field for form submission
            useBitsHidden.value = bitsUsed;
            
            // Show/hide discount row
            if (bitsUsed > 0) {
                bitsDiscountRow.classList.remove('hidden');
            } else {
                bitsDiscountRow.classList.add('hidden');
            }
            
            // Update total (subtract bits discount from current total)
            const newTotal = (total - bitsUsed * bitValue).toFixed(2);
            summaryTotal.textContent = '{{ Helpers::getCurrencySymbol() }}' + newTotal;
        });
    }
});
</script>
@endsection
@endsection
