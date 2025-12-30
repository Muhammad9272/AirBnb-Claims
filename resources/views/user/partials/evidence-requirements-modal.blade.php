<div id="evidenceModal"
     class="hidden fixed inset-0 z-50 bg-gray-900/80 flex items-center justify-center px-4 py-6">

    <div class="relative bg-white w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden
                rounded-xl sm:rounded-2xl shadow-2xl font-sans">

        <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 sm:px-6 py-4 text-white">
            <div class="flex items-start gap-3">
                <svg class="h-7 w-7 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856
                             c1.54 0 2.502-1.667 1.732-3L13.732 4
                             c-.77-1.333-2.694-1.333-3.464 0L3.34 16
                             c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h2 class="text-lg sm:text-2xl font-extrabold tracking-tight">
                        Evidence Requirements
                    </h2>
                    <p class="text-sm sm:text-base text-red-100 mt-1">
                        Please read carefully before submitting your claim
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-5 sm:py-6 space-y-7">

            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-md p-4">
                <h3 class="text-sm sm:text-base font-semibold text-yellow-800">
                    Claims without proper evidence may be reduced or denied
                </h3>
                <p class="text-sm text-yellow-700 mt-1 leading-relaxed">
                    This guide explains exactly what Airbnb requires so you can maximize your payout.
                </p>
            </div>

            <div class="space-y-4">
                <h3 class="flex items-center text-base sm:text-lg font-semibold text-gray-900">
                    <span
                        class="w-7 h-7 mr-3 rounded-full bg-blue-100 text-blue-700
                               text-xs sm:text-sm font-bold flex items-center justify-center">
                        1
                    </span>
                    Basic Evidence (Required for All Claims)
                </h3>

                <div class="bg-blue-50 rounded-lg p-4 sm:p-5 space-y-3">
                    <ul class="space-y-3">
                        @php
                            $items = [
                                'Clear photos or videos showing the issue',
                                'Airbnb booking reference number',
                                'Date and time the issue was discovered',
                                'Detailed explanation of what happened',
                                'Receipts or proof of item value or repair cost',
                            ];
                        @endphp

                        @foreach ($items as $item)
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293
                                             a1 1 0 00-1.414-1.414L9 10.586
                                             7.707 9.293a1 1 0 00-1.414 1.414l2 2
                                             a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm sm:text-base text-gray-700 leading-relaxed">
                                    {{ $item }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="flex items-center text-base sm:text-lg font-semibold text-gray-900">
                    <span
                        class="w-7 h-7 mr-3 rounded-full bg-purple-100 text-purple-700
                               text-xs sm:text-sm font-bold flex items-center justify-center">
                        2
                    </span>
                    Claim-Specific Evidence
                </h3>

                @php
                    $claims = [
                        [
                            'title' => 'Property Damage',
                            'items' => [
                                'Photos from multiple angles',
                                'Before photos if available',
                                'Repair estimates or invoices',
                                'Guest communication screenshots',
                            ],
                        ],
                        [
                            'title' => 'Missing Items',
                            'items' => [
                                'Listing or inventory photos',
                                'Photos showing item is missing',
                                'Original receipts or proof of value',
                                'Replacement cost documentation',
                            ],
                        ],
                        [
                            'title' => 'Smoke / Odor / Cleaning',
                            'items' => [
                                'Photos of smoking or odor source',
                                'Professional cleaning invoices',
                                'Policy violation screenshots',
                                'Lost booking evidence (if any)',
                            ],
                        ],
                        [
                            'title' => 'Unauthorized Guests / Parties',
                            'items' => [
                                'Security footage (if available)',
                                'Photos of extra guests or damage',
                                'Neighbor or police reports',
                                'House rules screenshots',
                            ],
                        ],
                    ];
                @endphp

                @foreach ($claims as $claim)
                    <div
                        class="border border-gray-200 rounded-lg p-4 sm:p-5
                               hover:shadow-md transition-all duration-200">
                        <h4 class="font-semibold text-gray-900 mb-2">
                            {{ $claim['title'] }}
                        </h4>
                        <ul class="text-sm sm:text-base text-gray-700 space-y-1 ml-4 list-disc">
                            @foreach ($claim['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-5">
                <h3 class="text-base sm:text-lg font-semibold text-red-900 mb-2">
                    If Evidence Is Missing
                </h3>
                <ul class="text-sm sm:text-base text-red-800 space-y-2">
                    <li>• Claim may be denied completely</li>
                    <li>• Payout may be reduced significantly</li>
                    <li>• Claim may be delayed pending evidence</li>
                </ul>
                <p class="mt-3 font-semibold text-red-900 text-sm sm:text-base">
                    Take a few minutes now — it can make the difference between $0 and a full payout.
                </p>
            </div>
        </div>

        <div
            class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
            <a href="{{ route('user.claims.evidence.guidelines') }}"
               class="text-sm sm:text-base text-blue-600 hover:text-blue-800 font-medium">
                View full evidence guidelines
            </a>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <label class="flex items-center gap-2 text-sm sm:text-base">
                    <input id="evidenceConfirmCheckbox" type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700 font-medium">
                        I understand the evidence requirements
                    </span>
                </label>

                <button
                    id="continueToClaimBtn"
                    disabled
                    class="w-full sm:w-auto inline-flex items-center justify-center
                           px-6 py-3 rounded-lg text-sm sm:text-base font-semibold text-white
                           bg-gradient-to-r from-blue-600 to-indigo-600
                           hover:from-blue-700 hover:to-indigo-700
                           disabled:opacity-40 disabled:cursor-not-allowed
                           transition-all duration-200">
                    Continue to Claim
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/evidence-modal.js') }}"></script>
