@extends('front.layouts.app')

@section('meta_title') Checkout - {{ $plan->name }} @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-800">Complete Your Subscription</h1>
                        <a href="{{ route('user.subscription.plans') }}" class="text-accent hover:text-accent-dark">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Plans
                        </a>
                    </div>
                </div>
                
                @include('includes.alerts')
                
                <div class="p-6">
                    <!-- Plan Details -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Plan Summary</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700 font-medium">{{ $plan->name }}</span>
                            <span class="text-lg font-bold" id="original-price">{{ Helpers::setCurrency($plan->price) }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Billing: {{ ucfirst(Helpers::setInterval($plan->interval)) }}</p>
                            <p>Claims limit: {{ $plan->claims_limit ? $plan->claims_limit : 'Unlimited' }}</p>
                        </div>
                    </div>

                    <!-- Discount Code Section -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200 mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1"></i> Have a discount code?
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="discount_code_input" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                   placeholder="Enter code (e.g., WELCOME20)"
                                   maxlength="50">
                            <button type="button" 
                                    id="apply_discount_btn"
                                    class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark font-medium transition">
                                Apply
                            </button>
                        </div>
                        <div id="discount_message" class="mt-2 text-sm hidden"></div>
                    </div>

                    <!-- Wallet Balance Section -->
                    @if(auth()->user()->wallet_balance > 0)
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200 mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="use_wallet_checkbox" 
                                       class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-wallet mr-1"></i> Use wallet balance
                                </span>
                            </label>
                            <div class="text-right">
                                <div class="text-sm font-bold text-green-700">
                                    Available: <span id="wallet-available">{{ Helpers::setCurrency(auth()->user()->wallet_balance) }}</span>
                                </div>
                                <div class="text-xs text-gray-600 hidden" id="wallet-remaining-container">
                                    Remaining: <span id="wallet-remaining" class="font-medium">{{ Helpers::setCurrency(0) }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600">Check this box to apply your wallet credit to this purchase</p>
                    </div>
                    @endif

                    <!-- Price Breakdown -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Price Breakdown</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subscription Price:</span>
                                <span class="font-medium" id="breakdown-original">{{ Helpers::setCurrency($plan->price) }}</span>
                            </div>
                            
                            <div class="flex justify-between hidden" id="breakdown-discount-row">
                                <span class="text-gray-600">
                                    Discount (<span id="breakdown-discount-label"></span>):
                                </span>
                                <span class="font-medium text-green-600" id="breakdown-discount">-{{ Helpers::setCurrency(0) }}</span>
                            </div>
                            
                            @if(auth()->user()->wallet_balance > 0)
                            <div class="flex justify-between hidden" id="breakdown-wallet-row">
                                <span class="text-gray-600">Wallet Credit:</span>
                                <span class="font-medium text-green-600" id="breakdown-wallet">-{{ Helpers::setCurrency(0) }}</span>
                            </div>
                            @endif
                            
                            <div class="border-t border-gray-300 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-800">Total Due Today:</span>
                                    <span class="text-2xl font-bold text-accent" id="final-price">{{ Helpers::setCurrency($plan->price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Agreement Section -->
                    @include('user.subscription.service_agreement')

                    <!-- Payment Form -->
                    <form action="{{ route('subscription.process.payment') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="payment_method" value="stripe">
                        <input type="hidden" name="discount_code" id="discount_code_hidden" value="">
                        <input type="hidden" name="use_wallet" id="use_wallet_hidden" value="0">
                        <input type="hidden" name="agreement_accepted" id="agreement_accepted_hidden" value="0">
                        
                        <div class="border-t border-gray-200 mt-6 pt-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800" id="payment-info-text">
                                            You will be redirected to Stripe's secure payment page to complete your subscription.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" id="payment-button" class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-medium text-center flex items-center justify-center cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> <span id="button-text">Proceed to Payment</span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>By subscribing, you agree to our <a href="{{ route('front.help.terms') }}" class="text-accent hover:underline">Terms of Service</a> and <a href="{{ route('front.help.privacy') }}" class="text-accent hover:underline">Privacy Policy</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const originalPrice = {{ $plan->price }};
    const walletBalance = {{ auth()->user()->wallet_balance ?? 0 }};
    const currencySymbol = '{{ Helpers::getCurrencySymbol() }}';
    
    let discountAmount = 0;
    let discountPercentage = 0;
    let discountCodeApplied = '';
    let walletUsed = 0;
    
    const agreementCheckbox = document.getElementById('agreement_checkbox');
    const paymentButton = document.getElementById('payment-button');
    const hiddenAgreementInput = document.getElementById('agreement_accepted_hidden');
    const discountInput = document.getElementById('discount_code_input');
    const applyDiscountBtn = document.getElementById('apply_discount_btn');
    const discountMessage = document.getElementById('discount_message');
    const useWalletCheckbox = document.getElementById('use_wallet_checkbox');
    
    // Format price with currency symbol
    function formatPrice(amount) {
        return currencySymbol + amount.toFixed(2);
    }
    
    // Calculate and update pricing
    function updatePricing() {
        let priceAfterDiscount = originalPrice - discountAmount;
        
        // Calculate wallet usage
        if (useWalletCheckbox && useWalletCheckbox.checked) {
            walletUsed = Math.min(walletBalance, priceAfterDiscount);
            document.getElementById('use_wallet_hidden').value = '1';
            
            // Show remaining wallet balance
            const walletRemaining = walletBalance - walletUsed;
            const walletRemainingContainer = document.getElementById('wallet-remaining-container');
            const walletRemainingSpan = document.getElementById('wallet-remaining');
            
            if (walletRemainingContainer && walletRemainingSpan) {
                walletRemainingContainer.classList.remove('hidden');
                walletRemainingSpan.textContent = formatPrice(walletRemaining);
            }
        } else {
            walletUsed = 0;
            if (document.getElementById('use_wallet_hidden')) {
                document.getElementById('use_wallet_hidden').value = '0';
            }
            
            // Hide remaining wallet balance
            const walletRemainingContainer = document.getElementById('wallet-remaining-container');
            if (walletRemainingContainer) {
                walletRemainingContainer.classList.add('hidden');
            }
        }
        
        let finalAmount = Math.max(0, priceAfterDiscount - walletUsed);
        
        // Update display
        document.getElementById('breakdown-original').textContent = formatPrice(originalPrice);
        
        // Discount row
        if (discountAmount > 0) {
            document.getElementById('breakdown-discount-row').classList.remove('hidden');
            document.getElementById('breakdown-discount-label').textContent = discountCodeApplied + ' ' + discountPercentage.toFixed(0) + '%';
            document.getElementById('breakdown-discount').textContent = '-' + formatPrice(discountAmount);
        } else {
            document.getElementById('breakdown-discount-row').classList.add('hidden');
        }
        
        // Wallet row
        const walletRow = document.getElementById('breakdown-wallet-row');
        if (walletRow && walletUsed > 0) {
            walletRow.classList.remove('hidden');
            document.getElementById('breakdown-wallet').textContent = '-' + formatPrice(walletUsed);
        } else if (walletRow) {
            walletRow.classList.add('hidden');
        }
        
        // Final price
        document.getElementById('final-price').textContent = formatPrice(finalAmount);
        
        // Update button text
        const buttonText = document.getElementById('button-text');
        const paymentInfo = document.getElementById('payment-info-text');
        
        if (finalAmount <= 0) {
            buttonText.textContent = 'Activate Subscription';
            paymentInfo.textContent = 'Your subscription will be activated immediately using your available credits.';
        } else {
            buttonText.textContent = 'Proceed to Payment';
            paymentInfo.textContent = 'You will be redirected to Stripe\'s secure payment page to complete your subscription.';
        }
    }
    
    // Apply discount code
    applyDiscountBtn.addEventListener('click', function() {
        const code = discountInput.value.trim().toUpperCase();
        
        if (!code) {
            showMessage('Please enter a discount code', 'error');
            return;
        }
        
        // Show loading
        applyDiscountBtn.disabled = true;
        applyDiscountBtn.textContent = 'Checking...';
        
        // Validate discount code via AJAX
        fetch('{{ route("subscription.validate.discount") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                discount_code: code,
                plan_id: {{ $plan->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                discountAmount = parseFloat(data.discount_amount);
                discountPercentage = parseFloat(data.discount_percentage);
                discountCodeApplied = code;
                
                document.getElementById('discount_code_hidden').value = code;
                
                showMessage(`Discount applied! You save ${formatPrice(discountAmount)} (${discountPercentage}%)`, 'success');
                
                discountInput.disabled = true;
                applyDiscountBtn.textContent = 'Applied ✓';
                applyDiscountBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                applyDiscountBtn.classList.remove('bg-accent', 'hover:bg-accent-dark');
                
                // Update pricing after discount applied
                updatePricing();
            } else {
                showMessage(data.message || 'Invalid discount code', 'error');
                applyDiscountBtn.disabled = false;
                applyDiscountBtn.textContent = 'Apply';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error validating discount code', 'error');
            applyDiscountBtn.disabled = false;
            applyDiscountBtn.textContent = 'Apply';
        });
    });
    
    // Wallet checkbox change
    if (useWalletCheckbox) {
        useWalletCheckbox.addEventListener('change', function() {
            updatePricing();
        });
    }
    
    // Show message helper
    function showMessage(message, type) {
        discountMessage.textContent = message;
        discountMessage.classList.remove('hidden', 'text-green-600', 'text-red-600');
        discountMessage.classList.add(type === 'success' ? 'text-green-600' : 'text-red-600');
    }
    
    // Agreement checkbox
    if (agreementCheckbox) {
        agreementCheckbox.addEventListener('change', function() {
            if (this.checked) {
                paymentButton.disabled = false;
                paymentButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                paymentButton.classList.add('bg-accent', 'hover:bg-accent-dark');
                hiddenAgreementInput.value = '1';
            } else {
                paymentButton.disabled = true;
                paymentButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                paymentButton.classList.remove('bg-accent', 'hover:bg-accent-dark');
                hiddenAgreementInput.value = '0';
            }
        });
        
        // Prevent form submission if agreement is not accepted
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            if (!agreementCheckbox.checked) {
                e.preventDefault();
                alert('Please accept the Service Agreement to proceed with your subscription.');
            }
        });
    }
});
</script>
@endsection