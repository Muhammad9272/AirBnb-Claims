@extends('front.help.layout')

@section('meta_title', 'AirBnb Claims Guides')

@section('help_content')
<h1 class="text-3xl font-bold text-gray-900 mb-6">AirBnb Claims Guides</h1>

<p class="text-lg text-gray-700 mb-8">
    Learn how to maximize your success with Airbnb damage claims through our comprehensive guides. Whether you're a first-time claimant or a seasoned host, these resources will help you navigate the claims process effectively.
</p>

<!-- Guide Categories -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <a href="#getting-started" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Getting Started</h3>
        <p class="text-gray-600">Learn the basics of filing an Airbnb claim and essential first steps after discovering damage.</p>
    </a>
    
    <a href="#documentation" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Documentation Guide</h3>
        <p class="text-gray-600">Understand what evidence to collect and how to document damage effectively for your claim.</p>
    </a>
    
    <a href="#photography" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Photography Tips</h3>
        <p class="text-gray-600">Master the art of taking effective damage photos that support your claim.</p>
    </a>
    
    <a href="#communication" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Guest Communication</h3>
        <p class="text-gray-600">Learn effective approaches for discussing damages with guests and securing cooperation.</p>
    </a>
    
    <a href="#valuation" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Damage Valuation</h3>
        <p class="text-gray-600">Understand how to properly value damages and calculate appropriate claim amounts.</p>
    </a>
    
    <a href="#advanced" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="text-accent mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Advanced Strategies</h3>
        <p class="text-gray-600">Expert techniques for handling complex claims and difficult scenarios.</p>
    </a>
</div>

<!-- Getting Started Guide Section -->
<div id="getting-started" class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Getting Started with AirBnb Claims</h2>
    </div>
    <div class="p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Essential First Steps</h3>
        
        <p class="text-gray-700 mb-6">
            Discovering damage to your property after a guest checkout can be stressful, but following these steps will help ensure your claim has the best chance of success:
        </p>
        
        <div class="space-y-6">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-accent text-white font-bold mr-4">1</div>
                <div>
                    <h4 class="text-lg font-medium text-gray-800">Act Quickly</h4>
                    <p class="text-gray-600 mt-1">
                        Document damage immediately upon discovery. Airbnb has strict timeframes for filing claims: within 14 days of checkout or before the next guest checks in, whichever comes first.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-accent text-white font-bold mr-4">2</div>
                <div>
                    <h4 class="text-lg font-medium text-gray-800">Document Everything</h4>
                    <p class="text-gray-600 mt-1">
                        Take clear, well-lit photos of all damage from multiple angles. Include close-ups of specific damage as well as wider shots showing the context and location within your property.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-accent text-white font-bold mr-4">3</div>
                <div>
                    <h4 class="text-lg font-medium text-gray-800">Contact the Guest</h4>
                    <p class="text-gray-600 mt-1">
                        Communicate with your guest through the Airbnb messaging system about the damage. This creates a record within Airbnb's system and may resolve the issue if the guest acknowledges responsibility.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-accent text-white font-bold mr-4">4</div>
                <div>
                    <h4 class="text-lg font-medium text-gray-800">Gather Evidence</h4>
                    <p class="text-gray-600 mt-1">
                        Collect all relevant documentation, including before/after photos, cleaning reports, repair estimates, replacement costs, and communications with the guest.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-accent text-white font-bold mr-4">5</div>
                <div>
                    <h4 class="text-lg font-medium text-gray-800">Create Your Claim</h4>
                    <p class="text-gray-600 mt-1">
                        Log into your AirBnb Claims account and create a new claim with all the collected evidence and information. Our system will guide you through providing all necessary details.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-8">
            <a onclick="openEvidenceModal()" class="inline-flex items-center px-6 py-3 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Your First Claim
            </a>
        </div>
    </div>
</div>

<!-- Documentation Guide Section -->
<div id="documentation" class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Documentation Guide</h2>
    </div>
    <div class="p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Effective Evidence Collection</h3>
        
        <p class="text-gray-700 mb-6">
            Proper documentation is the foundation of a successful claim. Here's what you need to collect and how to organize your evidence effectively:
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Essential Documentation
                </h4>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Before and after photos of damaged areas</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Airbnb reservation details and guest information</span>
                    </li>
                    <!-- More list items -->
                </ul>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Financial Documentation
                </h4>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Original purchase receipts for damaged items</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Repair quotes or invoices from service providers</span>
                    </li>
                    <!-- More list items -->
                </ul>
            </div>
        </div>
        
        <!-- Rest of documentation guide content -->
    </div>
</div>

<!-- More guide sections with relevant content -->

<div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Need additional guidance?</h3>
    <p class="text-gray-600 mb-4">Our team is available to provide personalized assistance with your specific claim situation.</p>
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            Contact a Claim Specialist
        </a>
        <a href="{{ route('front.help.faqs') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Read FAQs
        </a>
    </div>
</div>
@include('user.partials.evidence-requirements-modal')
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@endpush
