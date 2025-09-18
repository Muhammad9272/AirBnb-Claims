@if(auth()->check())
    @php
        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
    @endphp
    
    
@endif

<!-- Plans Grid - Optimized for 2 plans -->
<div class="max-w-5xl mx-auto">
    @if(isset($activeSubscription))
        <!-- Active Subscription Banner -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-10 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-green-800">Active Subscription</h3>
                    <div class="mt-1 text-green-700">
                        <p class="text-base">You're currently subscribed to the <strong>{{ $activeSubscription->plan->name }}</strong> plan.</p>
                        <p class="text-sm mt-1 opacity-90">Next billing date: {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('F j, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-12 items-stretch">
        @foreach ($plans as $plan)
            <div class="relative bg-white border-2 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 {{ $plan->is_featured ? 'border-blue-500 shadow-xl' : 'border-gray-200 hover:border-blue-300' }}">
                

               {{-- <div class="relative bg-white border rounded-lg p-6"> --}}
                    {{-- Display label at top-left --}}
                    @if ($plan->display_label)
                        <div class="absolute top-4 left-4">
                            <span
                                class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold 
                                {{ $plan->is_featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $plan->display_label }}
                            </span>
                        </div>
                    @endif

                    {{-- Popular badge at top-right --}}
                    @if ($plan->is_featured)
                        <div class="absolute top-4 right-4">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">
                                Popular
                            </div>
                        </div>
                    @endif

                    {{-- …rest of your plan content goes here… --}}
                {{-- </div> --}}

                <!-- Plan Header -->
                <div class="p-8 {{ $plan->is_featured ? 'bg-gradient-to-br from-blue-50 to-indigo-50' : 'bg-gray-50' }} border-b border-gray-200">
                    <div class="text-center">
                        <!-- Plan Icon -->
                        <div class="mx-auto h-16 w-16 {{ $plan->is_featured ? 'bg-gradient-to-br from-blue-500 to-purple-600' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($plan->is_featured)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                @endif
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        
                        

                        <!-- Pricing -->
                        <div class="mt-6">
                            <div class="flex items-center justify-center">
                                <span class="text-5xl font-bold text-gray-900">{{ Helpers::setCurrency($plan->price) }}</span>
                                <span class="text-xl text-gray-500 ml-2">/{{ Helpers::setInterval($plan->interval) }}</span>
                            </div>
                            @if($plan->price > 0)
                                <p class="text-sm text-gray-500 mt-2">Billed {{ Helpers::setInterval($plan->interval) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Plan Features -->
                <div class="p-8">
                    <!-- CTA Button - Moved to top for better UX -->
                    <div class="mb-8">
                        @auth
                            @php
                                $isCurrentPlan = isset($activeSubscription) && $activeSubscription && $activeSubscription->plan->id == $plan->id;
                            @endphp
                            @if($isCurrentPlan)
                                <button disabled class="w-full py-4 px-6 rounded-xl text-lg font-semibold bg-gray-100 text-gray-500 cursor-not-allowed border-2 border-gray-200">
                                    <svg class="h-5 w-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Current Plan
                                </button>
                            @else
                                <a href="{{ route('subscription.checkout.show', ['slug' => $plan->slug]) }}"
                                   class="group w-full py-4 px-6 rounded-xl text-lg font-semibold text-white {{ $plan->is_featured ? 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 shadow-lg' : 'bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800' }} transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-200 flex items-center justify-center">
                                    Subscribe Now
                                    <svg class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('user.login') }}"
                               class="group w-full py-4 px-6 rounded-xl text-lg font-semibold text-white {{ $plan->is_featured ? 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 shadow-lg' : 'bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800' }} transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-200 flex items-center justify-center">
                                Log in to Subscribe
                                <svg class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @endauth
                    </div>

                    <!-- Features List -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">What's included:</h4>
                        
                        @php
                            $features = explode("\n", $plan->features);
                        @endphp
                        @foreach ($features as $feature)
                            @if (trim($feature))
                                <div class="flex items-start group">
                                    <div class="flex-shrink-0">
                                        <div class="h-6 w-6 {{ $plan->is_featured ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                            <svg class="h-4 w-4 {{ $plan->is_featured ? 'text-blue-600' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-base text-gray-700 group-hover:text-gray-900 transition-colors duration-200">{{ trim($feature) }}</span>
                                </div>
                            @endif
                        @endforeach

                        <!-- Claims Limit -->
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="h-6 w-6 {{ $plan->is_featured ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 {{ $plan->is_featured ? 'text-blue-600' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <span class="ml-3 text-base text-gray-700 group-hover:text-gray-900 transition-colors duration-200">
                                <strong>{{ $plan->claims_limit ? $plan->claims_limit . ' claims' : 'Unlimited claims' }}</strong> per period
                            </span>
                        </div>

                        <!-- Commission -->
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="h-6 w-6 {{ $plan->is_featured ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 {{ $plan->is_featured ? 'text-blue-600' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <span class="ml-3 text-base text-gray-700 group-hover:text-gray-900 transition-colors duration-200">
                                <strong>{{ $plan->commission_percentage }}% commission</strong> on successful claims
                            </span>
                        </div>
                    </div>

                    <!-- Value Proposition -->
                    @if($plan->is_featured)
                        <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-sm font-semibold text-blue-800">Recommended for most users</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Additional Information -->
<div class="mt-16 text-center max-w-3xl mx-auto">
    <div class="bg-gray-50 rounded-2xl p-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Need Help Choosing?</h3>
        <p class="text-gray-600 mb-6">Not sure which plan is right for you? Our team is here to help you find the perfect solution for your Airbnb claim management needs.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Contact Support
            </a>
            <a href="{{ route('front.help.guides') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                View Documentation
            </a>
        </div>
    </div>
</div>