@extends('front.layouts.app')
@section('meta_title', 'Pricing - AirBnb Claims')
@section('meta_description', 'View our subscription plans and pricing for AirBnb claims assistance. Choose the plan that fits your hosting needs.')

@section('content')
<!-- Hero Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl mb-0">
                Choose the plan that fits your hosting needs and only pay when we successfully recover your money
            </p>
        </div>
    </div>
</div>

<!-- Pricing Plans -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Pricing Toggle -->
        @include('front.subscription.partials.pricing-card')
    </div>
</div>

<!-- FAQ Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
            
            <div class="space-y-4" x-data="{selected: null}">
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="selected !== 1 ? selected = 1 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                        <span class="font-medium text-gray-800">How are the success fees calculated?</span>
                        <svg :class="{'rotate-180': selected === 1}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 1" class="px-4 pb-4 bg-white" x-collapse>
                        <p class="text-gray-600">
                            Success fees are calculated as a percentage of the final payout amount approved by Airbnb. For example, if you're on our Plus plan with a 15% success fee and your claim is approved for $1,000, the success fee would be $150. This fee is only charged when your claim is successfully approved.
                        </p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="selected !== 2 ? selected = 2 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                        <span class="font-medium text-gray-800">What if I need more claims than my plan allows?</span>
                        <svg :class="{'rotate-180': selected === 2}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 2" class="px-4 pb-4 bg-white" x-collapse>
                        <p class="text-gray-600">
                            If you need additional claims beyond your monthly allowance, you have two options: You can either upgrade to a higher-tier plan, or purchase additional individual claims at $49 per claim. Additional claims purchased separately carry the same success fee as your current plan.
                        </p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="selected !== 3 ? selected = 3 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                        <span class="font-medium text-gray-800">Can I cancel my subscription at any time?</span>
                        <svg :class="{'rotate-180': selected === 3}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 3" class="px-4 pb-4 bg-white" x-collapse>
                        <p class="text-gray-600">
                            Yes, you can cancel your subscription at any time through your account settings. When you cancel, your subscription will remain active until the end of your current billing cycle. Any claims already submitted will continue to be processed even after your subscription ends.
                        </p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="selected !== 4 ? selected = 4 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                        <span class="font-medium text-gray-800">What happens if my claim is rejected?</span>
                        <svg :class="{'rotate-180': selected === 4}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 4" class="px-4 pb-4 bg-white" x-collapse>
                        <p class="text-gray-600">
                            If your claim is rejected, you won't be charged any success fee. We'll provide a detailed analysis of why the claim was rejected and offer guidance on potential next steps. This might include appealing the decision with additional evidence or exploring other resolution options. Each rejected claim still counts toward your monthly claim allowance.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h2 class="text-3xl font-bold mb-6">Ready to Stop Losing Money on Damages?</h2>
            <p class="text-xl mb-8">
                Join hundreds of hosts who have successfully recovered compensation with our expert help
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-accent font-medium rounded-lg hover:bg-gray-100 transition duration-150">
                    Create an Account
                </a>
                <a href="{{ route('front.contact') }}" class="inline-flex items-center justify-center px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white/10 transition duration-150">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
