@extends('front.layouts.app')
@section('title')
    Help Center
@endsection
@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-purple-900/50 to-indigo-900/50 backdrop-blur-xl py-16">
        <div class="product-particles absolute inset-0"></div>
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-bold mb-3 bg-gradient-to-r from-purple-400 to-purple-600 text-transparent bg-clip-text">
                Help Center
            </h1>
            <div class="flex items-center text-sm text-gray-400">
                <a href="{{route('user.dashboard')}}" class="hover:text-purple-400">Home</a>
                <span class="mx-2">/</span>
                <span>Help</span>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- General Questions -->
            <div class="card-glow rounded-xl p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-purple-500/10 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">General Questions</h2>
                </div>

                <div class="space-y-4">
                    <!-- FAQ Items -->
                    <div class="border border-purple-400/20 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none" onclick="toggleFaq('faq1')">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium">What payment methods are available?</h3>
                                <svg class="w-5 h-5 transform transition-transform" id="faq1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden p-4 pt-0 text-gray-400 text-sm" id="faq1-content">
                            We accept one-time payments, recurring subscriptions, and crypto payments. For more details, please refer to the payment documentation provided after registration.
                        </div>
                    </div>
                    <div class="border border-purple-400/20 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none" onclick="toggleFaq('faq2')">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium">How do I manage my subscription?</h3>
                                <svg class="w-5 h-5 transform transition-transform" id="faq2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden p-4 pt-0 text-gray-400 text-sm" id="faq2-content">
                            You can manage your subscription under the "Account Settings" section in your dashboard. Here, you can upgrade or downgrade your plan, view billing details, and update payment methods.
                        </div>
                    </div>
                    <div class="border border-purple-400/20 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none" onclick="toggleFaq('faq3')">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium">What is the refund policy?</h3>
                                <svg class="w-5 h-5 transform transition-transform" id="faq3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden p-4 pt-0 text-gray-400 text-sm" id="faq3-content">
                            We offer a refund within 14 days of purchase if the product or service hasn't been used. Please check our full refund policy for more details.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="card-glow rounded-xl p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-purple-500/10 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Technical Support</h2>
                </div>

                <div class="space-y-4">
                    <div class="border border-purple-400/20 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none" onclick="toggleFaq('faq4')">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium">How can I contact support?</h3>
                                <svg class="w-5 h-5 transform transition-transform" id="faq4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden p-4 pt-0 text-gray-400 text-sm" id="faq4-content">
                            You can contact support via the "Support" tab on your dashboard. Our team is available to assist with any technical issues, payment concerns, or other inquiries.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & Billing -->
            <div class="card-glow rounded-xl p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-purple-500/10 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Account & Billing</h2>
                </div>

                <div class="space-y-4">
                    <div class="border border-purple-400/20 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none" onclick="toggleFaq('faq5')">
                            <div class="flex justify-between items-center">
                                <h3 class="font-medium">How do I cancel my subscription?</h3>
                                <svg class="w-5 h-5 transform transition-transform" id="faq5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden p-4 pt-0 text-gray-400 text-sm" id="faq5-content">
                            You can cancel your subscription anytime from the "Account Settings" section. Once canceled, you will retain access to your benefits until the end of the current billing cycle.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function toggleFaq(id) {
        const content = document.getElementById(`${id}-content`);
        const icon = document.getElementById(`${id}-icon`);
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
@endsection
