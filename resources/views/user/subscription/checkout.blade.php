@extends('front.layouts.app')
@section('meta_title') Checkout - {{ $plan->name }} @endsection

@section('css')
<style>
    .checkout-container {
        @apply max-w-3xl mx-auto;
    }
    .plan-details {
        @apply bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6;
    }
    .payment-section {
        @apply mt-6 space-y-4;
    }
    .card-element {
        @apply p-4 border border-gray-300 rounded-lg bg-white shadow-sm;
    }
    .checkout-summary {
        @apply border-t border-gray-200 mt-6 pt-4;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="checkout-container">
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
                    <div class="plan-details">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Plan Summary</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700 font-medium">{{ $plan->name }}</span>
                            <span class="text-lg font-bold">${{ number_format($plan->price, 2) }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Billing: {{ ucfirst(Helpers::setInterval($plan->interval)) }}</p>
                            <p>Claims limit: {{ $plan->claims_limit ? $plan->claims_limit : 'Unlimited' }}</p>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('subscription.process.payment') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="payment_method" value="stripe">
                        
                        <div class="checkout-summary">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-medium">Total Due Today:</span>
                                <span class="text-2xl font-bold">${{ number_format($plan->price, 2) }}</span>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">
                                            You will be redirected to Stripe's secure payment page to complete your subscription.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white py-3 px-6 rounded-lg font-medium text-center flex items-center justify-center">
                                <i class="fas fa-lock mr-2"></i> Proceed to Payment
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
@endsection
