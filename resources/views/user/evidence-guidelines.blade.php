@extends('front.layouts.app')
@section('meta_title') Evidence Guidelines - How to File a Strong Claim @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <h1 class="text-4xl font-bold mb-4">Complete Evidence Guidelines</h1>
            <p class="text-xl text-blue-100">Everything you need to know to maximize your claim payout</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Ready to file your claim?</h2>
                <p class="text-gray-600 mt-1">Make sure you have all the required evidence before you start</p>
            </div>
            <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                File a Claim Now
                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        {{-- Table of Contents --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">On This Page:</h3>
            <ul class="space-y-2">
                <li><a href="#why-evidence-matters" class="text-blue-600 hover:text-blue-800 font-medium">→ Why Evidence Matters</a></li>
                <li><a href="#basic-requirements" class="text-blue-600 hover:text-blue-800 font-medium">→ Basic Requirements (All Claims)</a></li>
                <li><a href="#property-damage" class="text-blue-600 hover:text-blue-800 font-medium">→ Property Damage Claims</a></li>
                <li><a href="#missing-items" class="text-blue-600 hover:text-blue-800 font-medium">→ Missing Items Claims</a></li>
                <li><a href="#smoke-odor" class="text-blue-600 hover:text-blue-800 font-medium">→ Smoke/Odor/Cleaning Claims</a></li>
                <li><a href="#unauthorized-guests" class="text-blue-600 hover:text-blue-800 font-medium">→ Unauthorized Guests/Party Claims</a></li>
                <li><a href="#photo-tips" class="text-blue-600 hover:text-blue-800 font-medium">→ Photo & Video Best Practices</a></li>
                <li><a href="#common-mistakes" class="text-blue-600 hover:text-blue-800 font-medium">→ Common Mistakes to Avoid</a></li>
            </ul>
        </div>

        {{-- Section: Why Evidence Matters --}}
        <div id="why-evidence-matters" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Why Evidence Matters</h2>
            </div>
            <div class="p-8 space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-5">
                    <p class="text-lg font-bold text-gray-900 mb-3">The Hard Truth About Claims:</p>
                    <p class="text-gray-800">
                        <strong>Without proper evidence, your claim will likely be denied or reduced by 50-70%.</strong> Insurance adjusters and claims processors need proof that damage occurred, that it was caused by the guest, and that your requested amount is justified.
                    </p>
                </div>

                <div class="prose max-w-none">
                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">What Happens With Good Evidence:</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Faster processing:</strong> Claims with clear evidence are approved in 3-7 days</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Higher payouts:</strong> Well-documented claims receive 90-100% of the requested amount</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Less back-and-forth:</strong> No need to submit additional evidence later</span>
                        </li>
                    </ul>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">What Happens With Poor Evidence:</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Delayed processing:</strong> Claims require follow-up, taking 2-4 weeks or more</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Reduced payouts:</strong> Often 30-50% of requested amount (or complete denial)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>More stress:</strong> Multiple rounds of evidence requests and communication</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section: Basic Requirements --}}
        <div id="basic-requirements" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Basic Requirements (All Claims)</h2>
            </div>
            <div class="p-8">
                <p class="text-lg text-gray-700 mb-6">
                    No matter what type of claim you're filing, you must provide these foundational pieces of evidence:
                </p>

                <div class="space-y-6">
                    {{-- Clear Photos/Videos --}}
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">1. Clear Photos or Videos</h3>
                        <ul class="space-y-2 text-gray-700 ml-4">
                            <li>• Show the damage or issue from multiple angles</li>
                            <li>• Use good lighting (natural light is best)</li>
                            <li>• Include close-up shots showing detail</li>
                            <li>• Include wide shots showing context/location</li>
                            <li>• Make sure photos are in focus and not blurry</li>
                        </ul>
                    </div>

                    {{-- Booking Reference --}}
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">2. Booking Reference Number</h3>
                        <p class="text-gray-700 mb-2">Your Airbnb confirmation code (e.g., HMABCD1234). This links the claim to the specific guest and reservation.</p>
                        <div class="bg-gray-50 p-4 rounded-lg mt-3">
                            <p class="text-sm font-medium text-gray-700">Where to find it:</p>
                            <ul class="text-sm text-gray-600 ml-4 mt-2">
                                <li>• In your Airbnb hosting dashboard</li>
                                <li>• In the booking confirmation email</li>
                                <li>• In your Airbnb app under "Reservations"</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Date & Time --}}
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">3. Date and Time of Discovery</h3>
                        <p class="text-gray-700">When did you discover the damage or issue? Be as specific as possible (e.g., "June 15, 2024 at 11:30 AM during checkout inspection").</p>
                    </div>

                    {{-- Detailed Description --}}
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">4. Detailed Description</h3>
                        <p class="text-gray-700 mb-3">Write a clear narrative explaining:</p>
                        <ul class="space-y-1 text-gray-700 ml-4">
                            <li>• What happened</li>
                            <li>• When you discovered it</li>
                            <li>• How it affects your property</li>
                            <li>• Any communication with the guest about it</li>
                        </ul>
                    </div>

                    {{-- Proof of Value --}}
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">5. Proof of Value</h3>
                        <p class="text-gray-700 mb-3">Documentation showing the cost to repair or replace:</p>
                        <ul class="space-y-1 text-gray-700 ml-4">
                            <li>• Original purchase receipts (if available)</li>
                            <li>• Contractor estimates or invoices</li>
                            <li>• Replacement cost quotes from retailers</li>
                            <li>• Comparable listings online showing current market value</li>
                        </ul>
                        <div class="bg-yellow-50 p-4 rounded-lg mt-3">
                            <p class="text-sm font-bold text-yellow-900">💡 Pro Tip:</p>
                            <p class="text-sm text-yellow-800 mt-1">Get at least 2-3 quotes for major damage. This shows you've done your research and strengthens your claim.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Property Damage --}}
        <div id="property-damage" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center">
                <svg class="h-8 w-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Property Damage Claims</h2>
            </div>
            <div class="p-8">
                <p class="text-lg text-gray-700 mb-6">
                    For damage to walls, floors, furniture, appliances, or any part of your property:
                </p>

                <div class="space-y-6">
                    <div class="bg-red-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Required Evidence:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Before and After Photos:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Photos showing the item/area before damage (from your listing photos or previous inspections) and after damage. If you don't have "before" photos, explain this and show similar undamaged areas in your property for comparison.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Multiple Angles:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Show damage from at least 3 different angles: close-up, medium distance, and wide shot showing location.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Repair Estimates:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Written quotes from licensed contractors. For damage over $500, get at least 2 quotes. Include contractor's business name, license number (if applicable), and contact info.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Guest Communication:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Screenshots of messages with the guest about the damage, if any. This shows you documented it right away.</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-5">
                        <p class="font-bold text-gray-900 mb-2">💡 Example Scenario:</p>
                        <p class="text-gray-700 text-sm">
                            <strong>Bad Evidence:</strong> One blurry photo of a stained carpet with no context, no repair quote.<br>
                            <strong>Good Evidence:</strong> 5 clear photos showing the stain from different angles, a "before" photo of the clean carpet, 2 professional carpet cleaning quotes ($150-$200), and a screenshot of you messaging the guest about it the day after checkout.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Missing Items --}}
        <div id="missing-items" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center">
                <svg class="h-8 w-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Missing Items Claims</h2>
            </div>
            <div class="p-8">
                <p class="text-lg text-gray-700 mb-6">
                    For stolen or missing linens, appliances, décor, equipment, or any property items:
                </p>

                <div class="space-y-6">
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Required Evidence:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="bg-orange-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Proof Item Was Present:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Photos showing the item was in your property before this booking (listing photos, inventory photos, previous guest photos, etc.).</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-orange-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Proof Item Is Now Missing:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Photos of the empty space where the item used to be, showing it's no longer there.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-orange-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Original Purchase Proof:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Original receipt, invoice, or order confirmation showing what you paid for it. If you don't have the original receipt, find the current replacement cost.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-orange-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Replacement Cost:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Current market value for replacing the item. Link to Amazon, Target, or other retailers showing the same or equivalent item and its price.</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-lg">
                        <p class="font-bold text-yellow-900 mb-2">⚠️ Important Note on Depreciation:</p>
                        <p class="text-yellow-800 text-sm">
                            If your item was used/old, you may only be reimbursed for its depreciated value, not the full replacement cost. Be prepared to negotiate. For example, a 5-year-old TV might only be valued at 30-40% of a new one.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Smoke/Odor/Cleaning --}}
        <div id="smoke-odor" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4 flex items-center">
                <svg class="h-8 w-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Smoke / Odor / Cleaning Claims</h2>
            </div>
            <div class="p-8">
                <p class="text-lg text-gray-700 mb-6">
                    For violations of no-smoking rules, pet odors, excessive mess, or any cleaning-related issues:
                </p>

                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Required Evidence:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="bg-gray-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Physical Evidence:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Photos of cigarette butts, ash, burn marks, pet hair/stains, or the source of odor. For smoke smell, photograph ashtrays, balconies, trash cans with cigarette evidence.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-gray-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Professional Cleaning Invoice:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Receipt from your cleaning company showing the extra cost incurred. Highlight charges for deep cleaning, odor removal, carpet shampooing, etc.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-gray-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Your House Rules:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Screenshot of your Airbnb listing showing your no-smoking, no-pets, or cleanliness policies. This proves the guest violated your clearly stated rules.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-gray-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Lost Income (if applicable):</strong>
                                    <p class="text-gray-700 text-sm mt-1">If you had to cancel upcoming bookings due to the issue, provide screenshots of cancelled reservations and documentation of your nightly rate.</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-5">
                        <p class="font-bold text-gray-900 mb-2">💡 Pro Tip for Smoke Claims:</p>
                        <p class="text-gray-700 text-sm">
                            Smoke odor is hard to photograph. Instead, focus on physical evidence: cigarette butts in trash, ash on balcony, burn marks on furniture, and professional cleaning invoices specifically mentioning odor removal.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Unauthorized Guests --}}
        <div id="unauthorized-guests" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center">
                <svg class="h-8 w-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-white">Unauthorized Guests / Party Claims</h2>
            </div>
            <div class="p-8">
                <p class="text-lg text-gray-700 mb-6">
                    For claims involving extra guests beyond the booking, unauthorized parties, or violations of occupancy limits:
                </p>

                <div class="space-y-6">
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Required Evidence:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Security Camera Footage:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Video showing extra people entering/leaving, or large numbers of guests arriving. Ensure cameras comply with Airbnb disclosure rules.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Photos of Extra Vehicles:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Pictures of multiple cars parked at your property beyond what the booking indicated.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Neighbor Complaints / Police Reports:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Documentation from neighbors about noise, crowds, or disturbances. Police reports are especially strong evidence.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Your House Rules on Guest Limits:</strong>
                                    <p class="text-gray-700 text-sm mt-1">Screenshots showing your clearly stated rules about maximum occupancy and no parties.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">✓</span>
                                <div>
                                    <strong class="text-gray-900">Associated Damage:</strong>
                                    <p class="text-gray-700 text-sm mt-1">If the party/extra guests caused damage, document it fully (see Property Damage section above).</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Photo Tips --}}
        <div id="photo-tips" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Photo & Video Best Practices</h2>
            </div>
            <div class="p-8 space-y-6">
                <p class="text-lg text-gray-700">
                    Great photos can make or break your claim. Here's how to take evidence photos that insurance adjusters will trust:
                </p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-5 rounded-lg">
                        <h3 class="font-bold text-green-900 mb-3 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Do This:
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>✓ Use natural light or bright artificial light</li>
                            <li>✓ Take photos immediately after discovering damage</li>
                            <li>✓ Shoot from multiple angles (close-up, medium, wide)</li>
                            <li>✓ Include context showing location in room</li>
                            <li>✓ Keep your camera lens clean</li>
                            <li>✓ Use your phone's timestamp feature if available</li>
                            <li>✓ Take more photos than you think you need</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 p-5 rounded-lg">
                        <h3 class="font-bold text-red-900 mb-3 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Don't Do This:
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>✗ Take blurry or out-of-focus photos</li>
                            <li>✗ Use only one angle</li>
                            <li>✗ Shoot in dark/dim lighting</li>
                            <li>✗ Wait days before documenting</li>
                            <li>✗ Over-edit or filter photos</li>
                            <li>✗ Crop photos too tightly</li>
                            <li>✗ Rely on just 1-2 photos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Common Mistakes --}}
        <div id="common-mistakes" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Common Mistakes to Avoid</h2>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #1: Not Documenting Right Away</h3>
                        <p class="text-sm text-red-800">Waiting days or weeks to photograph damage raises red flags. Document everything immediately after discovery.</p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #2: Not Getting Multiple Quotes</h3>
                        <p class="text-sm text-red-800">A single quote looks suspicious. Get 2-3 estimates for credibility, especially for claims over $500.</p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #3: Inflating Costs</h3>
                        <p class="text-sm text-red-800">Don't exaggerate values. Adjusters can easily verify costs online. Be honest and provide realistic documentation.</p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #4: Poor Photo Quality</h3>
                        <p class="text-sm text-red-800">Blurry, dark, or unclear photos will get your claim denied or reduced. Take the time to shoot clear, well-lit photos.</p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #5: Not Communicating with Guest</h3>
                        <p class="text-sm text-red-800">Always message the guest about damage through Airbnb's platform. This creates a timestamped record that proves the issue occurred.</p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-5 py-3 bg-red-50">
                        <h3 class="font-bold text-red-900 mb-1">❌ Mistake #6: Missing Proof of Value</h3>
                        <p class="text-sm text-red-800">Saying "this cost $500" without receipts or proof won't work. Always provide documentation showing market value.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Final CTA Section --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to File Your Claim?</h2>
            <p class="text-xl text-blue-100 mb-6">Now that you know what's required, gather your evidence and file a strong claim.</p>
            <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition-colors text-lg shadow-lg">
                File a Claim Now
                <svg class="ml-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

    </div>
</div>
@endsection