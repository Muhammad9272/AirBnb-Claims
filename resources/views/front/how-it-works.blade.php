@extends('front.layouts.app')
@section('meta_title', 'How It Works - ClaimPilot+')
@section('meta_description', 'Learn how our ClaimPilot+ claims process works and how we help hosts get fair compensation for property damages.')

@section('content')
<!-- Hero Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-4xl font-bold mb-4">How It Works</h1>
            <p class="text-xl mb-0">
                Our streamlined process makes it easy to submit, track, and resolve your OTA damage claims.
            </p>
        </div>
    </div>
</div>


<!-- Process Overview -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">The OTA's Claim Filing Process</h2>
            
            <p class="text-lg text-gray-700 mb-12 text-center">
                We've simplified the complex claim filing process into a streamlined system that maximizes your chances of approval.
            </p>
            
            @php
                $steps = [
                    [
                        'number' => 1,
                        'title'  => 'Sign Up & Subscribe',
                        'desc'   => 'Choose a subscription plan that fits your hosting needs. Our plans are designed for occasional hosts to property management companies.',
                        'bullets'=> [
                            'Basic account information (name, email, phone)',
                            'Payment method for subscription',
                            'Information about your property portfolio',
                        ],
                    ],
                    [
                        'number' => 2,
                        'title'  => 'Submit Claim Details',
                        'desc'   => 'When damage occurs, submit all relevant information through our user-friendly dashboard. Our guided form makes it easy to provide all necessary details.',
                        'bullets'=> [
                            'Reservation details',
                            'Description of damages',
                            'Photos documenting the damage',
                            'Repair estimates or receipts (if applicable)',
                            'Guest communications about the damage',
                        ],
                    ],
                    [
                        'number' => 3,
                        'title'  => 'Claim Submission & Advocacy',
                        'desc'   => 'We submit your optimized claim through the OTA\'s Resolution Center and act as your advocate throughout the process, handling all submission & communication.',
                        'bullets'=> [
                            'Strategic claim timing and submission',
                            'Professional communication with resolution center',
                            'Response to OTA\'s inquiries and objections',
                            'Regular status updates to you',
                        ],
                    ],
                    [
                        'number' => 4,
                        'title'  => 'Resolution & Payment',
                        'desc'   => 'Once the OTA\'s makes a decision, we notify you immediately. For approved claims, we help ensure you receive payment promptly. Our success fee is only charged on successfully approved claims.',
                        'metrics'=> [
                            ['label' => 'Approval Rate',            'width' => '90%', 'text' => '90% approval rate'],
                            ['label' => 'Average Time to Resolution','width' => '70%', 'text' => '2-4 weeks average'],
                        ],
                    ],
                ];
            @endphp

            <div class="relative pb-12">
                <!-- Timeline Line -->
                <div class="absolute left-8 top-3 bottom-3 w-1 bg-gray-200 hidden sm:block"></div>
                
                @foreach($steps as $step)
                    <div class="relative flex flex-col sm:flex-row items-start mb-12">
                        <div class="flex-shrink-0 bg-accent text-white rounded-full h-16 w-16 flex items-center justify-center text-2xl font-bold z-10 mb-4 sm:mb-0">
                            {{ $step['number'] }}
                        </div>
                        <div class="sm:ml-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                            <p class="text-gray-600 mb-4">{{ $step['desc'] }}</p>

                            @if(isset($step['bullets']))
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">What you need:</h4>
                                    <ul class="space-y-1 text-sm text-gray-600">
                                        @foreach($step['bullets'] as $bullet)
                                            <li class="flex items-center">
                                                <svg class="h-4 w-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $bullet }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif(isset($step['metrics']))
                                <div class="bg-green-50 rounded-lg border border-green-100 p-4">
                                    <h4 class="text-sm font-semibold text-green-800 mb-2">Success metrics:</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach($step['metrics'] as $metric)
                                            <div>
                                                <p class="text-sm text-green-700 font-medium">{{ $metric['label'] }}</p>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $metric['width'] }}"></div>
                                                </div>
                                                <p class="text-xs text-green-700 mt-1">{{ $metric['text'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- Trust-Focused How It Works Section -->
<div class="py-20 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    🛠️ How <span class="bg-gradient-to-r from-accent to-accent-light bg-clip-text text-transparent">ClaimPilotPlus</span> Works
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our simple, secure process gets you maximum payouts with minimal effort. You stay in control while we handle the complexities.
                </p>
            </div>

            <!-- 3-Step Process -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Step 1: Add Co-host -->
                <div class="relative">
                    <!-- Step Number -->
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg z-10">
                        1
                    </div>
                    
                    <!-- Card -->
                    <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-l-4 border-green-500">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Add us as a co-host</h3>
                            <p class="text-gray-600 mb-4">It takes just 30 seconds to add our team to your Airbnb account. This gives us the access we need to file claims on your behalf.</p>
                        </div>
                        
                        <!-- Trust Indicators -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Your Security:
                            </h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Only access listings you add us to</li>
                                <li>• Cannot change pricing or availability</li>
                                <li>• All actions logged in your account</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 2: We Handle Everything -->
                <div class="relative">
                    <!-- Step Number -->
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-accent to-accent-light rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg z-10">
                        2
                    </div>
                    
                    <!-- Card -->
                    <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-l-4 border-accent">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">We gather evidence & handle follow-ups</h3>
                            <p class="text-gray-600 mb-4">Our experts collect all necessary documentation, submit your claim strategically, and manage all communication with Airbnb.</p>
                        </div>
                        
                        <!-- What We Do -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                We Handle:
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Professional damage documentation</li>
                                <li>• Strategic claim submission</li>
                                <li>• All Airbnb communication</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 3: You Get Paid -->
                <div class="relative">
                    <!-- Step Number -->
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg z-10">
                        3
                    </div>
                    
                    <!-- Card -->
                    <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-l-4 border-purple-500">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">You get paid</h3>
                            <p class="text-gray-600 mb-4">Receive your maximum payout and remove our access anytime. Your property, your control, our expertise.</p>
                        </div>
                        
                        <!-- Your Control -->
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Your Control:
                            </h4>
                            <ul class="text-sm text-purple-700 space-y-1">
                                <li>• Remove access anytime</li>
                                <li>• Success-based fees only</li>
                                <li>• Complete transparency</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Banner -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-8 text-center">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-3xl font-bold text-white mb-4">
                        🔒 Your Security & Control is Our Priority
                    </h3>
                    <p class="text-gray-300 text-lg mb-8">
                        We understand trust is earned. That's why we've built our entire process around giving you complete control and transparency.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h4 class="text-white font-semibold mb-2">Limited Access</h4>
                            <p class="text-gray-300 text-sm">We can only access the specific listings you add us to, and only for claim-related activities.</p>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-white font-semibold mb-2">Full Transparency</h4>
                            <p class="text-gray-300 text-sm">All our actions are logged in your Airbnb account, so you can see exactly what we do.</p>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <h4 class="text-white font-semibold mb-2">Remove Anytime</h4>
                            <p class="text-gray-300 text-sm">You can revoke our access instantly through your Airbnb settings whenever you choose.</p>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8">
                        <a href="{{ route('user.register') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Get Started - It Takes 30 Seconds
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Platform Features</h2>
        
        @php
            $features = [
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 00-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                               </svg>',
                    'title'       => 'Guided Claim Submission',
                    'description' => 'Our step-by-step form walks you through exactly what information and documentation is needed for a successful claim.',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                               </svg>',
                    'title'       => 'Real-Time Claim Tracking',
                    'description' => 'Monitor the progress of your claims through our dashboard, with notifications at every step of the process.',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                               </svg>',
                    'title'       => 'Claims History',
                    'description' => 'Access your complete history of claims, including outcomes, amounts, and all associated documentation.',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                               </svg>',
                    'title'       => 'Expert Support',
                    'description' => 'Direct access to our claims specialists through the platform\'s messaging system for questions and guidance.',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                               </svg>',
                    'title'       => 'Educational Resources',
                    'description' => 'Access to guides, tips, and best practices for documenting damages and preventing future issues.',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                               </svg>',
                    'title'       => 'Secure Document Storage',
                    'description' => 'All your property documentation, before/after photos, and claim evidence stored securely in one place.',
                ],
            ];
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($features as $feature)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="bg-accent/10 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        {!! $feature['icon'] !!}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Section (with Unsplash background + transparent overlay) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Semi‐transparent dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h2 class="text-3xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl mb-8">
                Join the hosts who've successfully recovered over $85,000 in damages with our proven system and expertise.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.register') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-white text-accent font-medium rounded-lg hover:bg-gray-100 transition duration-150">
                    Create an Account
                </a>
                <a href="{{ route('front.pricing') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white/10 transition duration-150">
                    View Pricing
                </a>
            </div>
        </div>
    </div>
</div>
@endsection