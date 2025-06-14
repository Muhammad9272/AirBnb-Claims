@extends('front.layouts.app')
@section('meta_title') Subscription Plans @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800">Choose Your Subscription Plan</h1>
                        <p class="text-gray-600 mt-2">Select a plan that fits your needs to manage your Airbnb claims effectively</p>
                    </div>

                    @include('includes.alerts')

                    <div class="p-6">
                        @php
                            $activeSubscription = auth()->user()->getActiveSubscription;
                        @endphp

                        @if ($activeSubscription)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">Active Subscription</h3>
                                        <div class="mt-2 text-sm text-green-700">
                                            <p>You're currently subscribed to the <strong>{{ $activeSubscription->plan->name }}</strong> plan.</p>
                                            <p class="mt-1">Next billing date: {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('F j, Y') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Subscription Plans -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($plans as $plan)
                                <div class="border rounded-lg overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-1 {{ $plan->is_featured ? 'border-accent-light' : '' }} relative">
                                    @if ($plan->is_featured)
                                        <div class="absolute top-0 right-0 bg-accent text-white px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-bl-lg">Most Popular</div>
                                    @endif

                                    <div class="p-6 border-b bg-gray-50">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                                        @if ($plan->display_label)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-light bg-opacity-10 text-accent-dark mt-1">
                                                {{ $plan->display_label }}
                                            </span>
                                        @endif
                                        <div class="mt-4 flex items-baseline">
                                            <span class="text-3xl font-bold">${{ number_format($plan->price, 2) }}</span>
                                            <span class="text-sm text-gray-500 ml-2">/{{ Helpers::setInterval($plan->interval) }}</span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <div class="mt-6 space-y-4">
                                            @php
                                                $features = explode("\n", $plan->features);
                                            @endphp
                                            @foreach ($features as $feature)
                                                @if (trim($feature))
                                                    <div class="flex items-start">
                                                        <svg class="flex-shrink-0 h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="ml-3 text-sm text-gray-700">{{ trim($feature) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <div class="flex items-start">
                                                <svg class="flex-shrink-0 h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-3 text-sm text-gray-700">
                                                    {{ $plan->claims_limit ? $plan->claims_limit . ' claims per period' : 'Unlimited claims' }}
                                                </span>
                                            </div>

                                            <div class="flex items-start">
                                                <svg class="flex-shrink-0 h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-3 text-sm text-gray-700">
                                                    {{ $plan->commission_percentage }}% commission on successful claims
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-8">
                                            <a href="{{ route('subscription.checkout.show', ['slug' => $plan->slug]) }}"
                                               class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                                {{ $activeSubscription && $activeSubscription->plan->id == $plan->id ? 'Current Plan' : 'Subscribe Now' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Frequently Asked Questions</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">What happens when I subscribe?</h3>
                            <p class="mt-2 text-gray-600">When you subscribe, you get immediate access to create and manage your Airbnb claims. Our team will assist you through the entire process from submission to resolution.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Can I cancel my subscription?</h3>
                            <p class="mt-2 text-gray-600">Yes, you can cancel your subscription at any time. Your subscription will remain active until the end of the current billing period.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How does the commission work?</h3>
                            <p class="mt-2 text-gray-600">The commission is only applied to successful claims. For example, with a 20% commission on a $1,000 approved claim, the service fee would be $200, and you would receive $800.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection