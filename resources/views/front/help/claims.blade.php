@extends('front.help.layout')
@section('meta_title', 'Claim Process Guide')

@section('help_content')
<h1 class="text-3xl font-bold text-gray-900 mb-6">The AirBnb Claims Process</h1>

<p class="text-lg text-gray-700 mb-8">
    Understanding the AirBnb claims process is essential for maximizing your chances of getting fair compensation. Our platform streamlines this process, making it easier and more successful for hosts.
</p>

<!-- Process Timeline -->
<div class="relative py-8">
    <!-- Timeline Bar -->
    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gray-200 z-0"></div>
    
    <!-- Step 1 -->
    <div class="relative z-10 mb-12">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:text-right md:pr-8 order-2 md:order-1">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Incident Discovery & Documentation</h3>
                <p class="text-gray-600">
                    Immediately document all damages after guest checkout with detailed photos, videos, and written descriptions. The more thorough your documentation, the stronger your claim will be.
                </p>
            </div>
            <div class="md:mx-4 my-4 md:my-0 flex-shrink-0 order-1 md:order-2">
                <div class="bg-accent text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">1</span>
                </div>
            </div>
            <div class="flex-1 md:pl-8 order-3">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h4 class="text-gray-700 font-medium mb-2">Pro Tips:</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-accent mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Take photos from multiple angles with good lighting
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-accent mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Include "before" photos if available to show the contrast
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-accent mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Document within 14 days of checkout (or before next guest)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Step 2 -->
    <div class="relative z-10 mb-12">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:pl-8 order-3">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Creating Your Claim</h3>
                <p class="text-gray-600">
                    Register on our platform and provide detailed information about the incident, guest details, and your Airbnb listing. Upload all your documentation and specify the compensation amount.
                </p>
            </div>
            <div class="md:mx-4 my-4 md:my-0 flex-shrink-0 order-1 md:order-2">
                <div class="bg-accent text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">2</span>
                </div>
            </div>
            <div class="flex-1 md:text-right md:pr-8 order-2 md:order-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h4 class="text-gray-700 font-medium mb-2">Required Information:</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Airbnb reservation code and dates</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Guest information</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Detailed damage description and evidence</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Repair/replacement cost estimates or receipts</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Step 3 -->
    <div class="relative z-10 mb-12">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:text-right md:pr-8 order-2 md:order-1">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Expert Review & Optimization</h3>
                <p class="text-gray-600">
                    Our claims specialists review your submission, identify potential issues, and optimize your claim for maximum success. We may request additional information or documentation to strengthen your case.
                </p>
            </div>
            <div class="md:mx-4 my-4 md:my-0 flex-shrink-0 order-1 md:order-2">
                <div class="bg-accent text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">3</span>
                </div>
            </div>
            <div class="flex-1 md:pl-8 order-3">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h4 class="text-gray-700 font-medium mb-2">Our Optimization Process:</h4>
                    <ol class="space-y-2 text-sm text-gray-600 list-decimal list-inside">
                        <li>Professional review of all documentation</li>
                        <li>Assessment of claim validity under Airbnb's policies</li>
                        <li>Verification of damage value calculation</li>
                        <li>Formatting claim for Airbnb's specific requirements</li>
                        <li>Recommendation of additional evidence if needed</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Step 4 -->
    <div class="relative z-10 mb-12">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:pl-8 order-3">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Submission & Advocacy</h3>
                <p class="text-gray-600">
                    We submit your optimized claim through Airbnb's Resolution Center and act as your advocate throughout the process. Our team handles all communication with Airbnb, addressing any questions or concerns on your behalf.
                </p>
            </div>
            <div class="md:mx-4 my-4 md:my-0 flex-shrink-0 order-1 md:order-2">
                <div class="bg-accent text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">4</span>
                </div>
            </div>
            <div class="flex-1 md:text-right md:pr-8 order-2 md:order-1">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h4 class="text-gray-700 font-medium mb-2">Our Advocacy Includes:</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Professional communication with Airbnb</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Responding to inquiries and requests</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Negotiation for fair compensation</span>
                        </li>
                        <li class="flex items-start md:justify-end">
                            <svg class="h-5 w-5 text-accent mr-1 md:ml-1 md:mr-0 md:order-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="md:order-1">Appeals for rejected claims when appropriate</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Step 5 -->
    <div class="relative z-10">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:text-right md:pr-8 order-2 md:order-1">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Resolution & Payment</h3>
                <p class="text-gray-600">
                    Once Airbnb makes a decision, we notify you immediately. For approved claims, we help ensure you receive the payment promptly. If a claim is denied, we provide guidance on next steps and potential appeals.
                </p>
            </div>
            <div class="md:mx-4 my-4 md:my-0 flex-shrink-0 order-1 md:order-2">
                <div class="bg-accent text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold">5</span>
                </div>
            </div>
            <div class="flex-1 md:pl-8 order-3">
                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                    <h4 class="text-green-800 font-medium mb-2">Success Metrics:</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-green-700 font-medium">Approval Rate</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                            </div>
                            <p class="text-xs text-green-700 mt-1">85% of properly documented claims are approved</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-700 font-medium">Average Payout</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 78%"></div>
                            </div>
                            <p class="text-xs text-green-700 mt-1">78% of requested amount on average</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-700 font-medium">Average Processing Time</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 70%"></div>
                            </div>
                            <p class="text-xs text-green-700 mt-1">2-3 weeks for most claims</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ready to Start Banner -->
<div class="bg-gradient-to-r from-accent to-accent-light rounded-xl p-8 mt-12 text-white shadow-lg">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-2xl font-bold mb-4">Ready to Submit Your First Claim?</h2>
        <p class="mb-6">Let our expert team help you get the compensation you deserve for damages to your Airbnb property.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-accent font-medium rounded-lg hover:bg-gray-100 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Create an Account
            </a>
            <a href="{{ route('front.help.guides') }}" class="inline-flex items-center justify-center px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white/10 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Read Our Guides
            </a>
        </div>
    </div>
</div>

<!-- FAQ section related to claims -->
<div class="mt-16">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Frequently Asked Questions About Claims</h2>
    
    <div class="space-y-4" x-data="{selected: null}">
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <button @click="selected !== 1 ? selected = 1 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800">How long do I have to file a claim after a guest checks out?</span>
                <svg :class="{'rotate-180': selected === 1}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="selected === 1" class="px-4 pb-4 bg-white" x-collapse>
                <p class="text-gray-600">
                    Airbnb requires you to report damage within 14 days of guest checkout or before the next guest checks in, whichever is earlier. However, we strongly recommend documenting and initiating the claim process as soon as possible after discovering damage, as this increases the chances of approval.
                </p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <button @click="selected !== 2 ? selected = 2 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800">What types of damages are covered by Airbnb's AirCover program?</span>
                <svg :class="{'rotate-180': selected === 2}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="selected === 2" class="px-4 pb-4 bg-white" x-collapse>
                <p class="text-gray-600 mb-2">
                    Airbnb's AirCover for Hosts generally covers:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                    <li>Physical damage to your property and belongings by guests</li>
                    <li>Unexpected cleaning costs due to guest misconduct</li>
                    <li>Damage caused by a guest's pet</li>
                    <li>Theft of covered items</li>
                </ul>
                <p class="text-gray-600 mt-2">
                    Excluded items typically include wear and tear, mechanical breakdowns not caused by guests, damages not reported in time, and certain high-value items like artwork, collectibles, and jewelry that may have coverage limits.
                </p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <button @click="selected !== 3 ? selected = 3 : selected = null" class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800">What evidence is most important for a successful claim?</span>
                <svg :class="{'rotate-180': selected === 3}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="selected === 3" class="px-4 pb-4 bg-white" x-collapse>
                <p class="text-gray-600 mb-2">
                    The most compelling evidence includes:
                </p>
                <ol class="list-decimal pl-5 space-y-1 text-gray-600">
                    <li>Clear "before and after" photos showing the condition of the property/item</li>
                    <li>Timestamped photos taken immediately after guest checkout</li>
                    <li>Original purchase receipts or proof of value for damaged items</li>
                    <li>Professional repair or replacement quotes</li>
                    <li>Messages between you and the guest acknowledging the damage</li>
                    <li>Cleaning service reports documenting the damage</li>
                </ol>
                <p class="text-gray-600 mt-2">
                    Our platform guides you through collecting and organizing all necessary evidence for maximum claim success.
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
                    If your claim is rejected, our team will analyze the reason for rejection and advise on next steps. In many cases, we can help you appeal the decision by providing additional evidence or clarification. We may also recommend alternative approaches, such as pursuing the security deposit (if applicable) or exploring other resolution methods. Our goal is to exhaust all possible avenues to help you receive fair compensation.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mt-10 p-6 bg-gray-50 rounded-xl border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Have More Questions About the Claim Process?</h3>
    <p class="text-gray-600 mb-4">Our team of claim specialists is available to provide personalized guidance for your specific situation.</p>
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
            View FAQs
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@endpush
