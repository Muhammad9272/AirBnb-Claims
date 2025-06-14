@extends('front.layouts.app')
@section('meta_title') Subscription Plans @endsection
@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            @auth
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>
            <div class="lg:w-3/4">
            @else
            <div class="w-full">
            @endauth

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <!-- Header Section -->
                <div class="p-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="text-center max-w-3xl mx-auto">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">Choose Your Subscription Plan</h1>
                        <p class="text-lg text-gray-600">Select a plan that fits your needs to manage your Airbnb claims effectively</p>
                    </div>
                </div>

                @include('includes.alerts')

                <div class="p-8">
                    @include('front.subscription.partials.pricing-card')
                </div>
            </div>

            @push('styles')
            <style>
            /* Enhanced animations and interactions */
            .plan-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .plan-card:hover {
                transform: translateY(-8px);
            }

            .feature-item {
                transition: all 0.2s ease-in-out;
            }

            .feature-item:hover {
                transform: translateX(4px);
            }

            /* Ensure proper z-index layering */
            .relative {
                position: relative;
                z-index: 1;
            }

            /* Popular badge styles */
            .absolute {
                z-index: 10;
            }

            /* Custom gradient animations */
            @keyframes gradient-shift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .animated-gradient {
                background-size: 200% 200%;
                animation: gradient-shift 3s ease infinite;
            }

            /* Responsive adjustments */
            @media (max-width: 1024px) {
                .lg\:grid-cols-2 > div:first-child {
                    margin-bottom: 2rem;
                }
            }

            @media (max-width: 640px) {
                .text-5xl {
                    font-size: 2.5rem;
                }
                
                .py-4 {
                    padding-top: 1rem;
                    padding-bottom: 1rem;
                }
                
                .absolute.top-4.right-4 {
                    top: 1rem;
                    right: 1rem;
                }
            }
            </style>
            @endpush

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