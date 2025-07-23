@extends('front.help.layout')
@section('meta_title', 'Frequently Asked Questions')

@section('help_content')
@php
    // 1. Define your FAQ data structure as a nested array:
    $faqs = [
        'general' => [
            [
                'question' => 'What is AirBnb Claims?',
                'answer'   => 'AirBnb Claims is a specialized service that helps Airbnb hosts recover compensation for damages caused by guests. We handle the entire claims process, from documentation and evidence collection to submitting the claim and following up with Airbnb until resolution.'
            ],
            [
                'question' => 'How is your service different from filing a claim directly?',
                'answer'   => 'We offer expertise and experience that significantly increases your chances of claim approval. Our team knows exactly what documentation Airbnb requires, how to present evidence effectively, and how to navigate common rejection reasons. Our success rate is over 85%, compared to around 40% for hosts filing independently.'
            ],
            [
                'question' => 'Why do you need co-host access?',
                'answer'   => 'Airbnb only allows the listing owner or co-host to file official AirCover claims. We use this access exclusively to manage your claims, and you can remove us at any time. This ensures we can act quickly on your behalf to secure the compensation you deserve.'
            ],
            [
                'question' => 'Will you message guests or change my calendar?',
                'answer'   => 'No — we only touch what\'s necessary to file your claim. We never interfere with bookings, guest communications, or your property operations. Your hosting experience remains completely under your control.'
            ],
            [
                'question' => 'Can I remove your team after the claim is processed?',
                'answer'   => 'Yes — co-host access can be revoked at any time via your Airbnb settings. You have complete control over our access and can remove us whenever you choose. Many clients keep us on for ongoing claim management, but the choice is entirely yours.'
            ],
            [
                'question' => 'What exactly can you access as a co-host?',
                'answer'   => 'As a co-host, we can only access the specific listings you add us to. We can view booking details, submit claims, and communicate with Airbnb support about claims. We cannot change pricing, availability, house rules, or any other property settings.'
            ],
            [
                'question' => 'How do I know you won\'t misuse my account?',
                'answer'   => 'We\'re a professional claims management service with a track record of successful claims. All our actions are logged in your Airbnb account, so you can see exactly what we do. Plus, you can remove our access instantly if you\'re ever uncomfortable.'
            ],
            [
                'question' => 'Is my information secure?',
                'answer'   => 'Yes, protecting your information is our top priority. We use bank-level encryption for all data transfers and storage. We never share your personal information with third parties except as required to process your claim with Airbnb. Please review our <a href="'.route('front.help.privacy').'" class="text-accent hover:underline">Privacy Policy</a> for more details.'
            ],
            [
                'question' => 'How much does your service cost?',
                'answer'   => 'We offer several subscription plans to fit different hosting needs. Our pricing is structured to align with your success - we only take a small percentage of successfully approved claims in addition to a modest monthly subscription fee. You can view our current pricing and plan details on our <a href="'.route('user.subscription.plans').'" class="text-accent hover:underline">Plans & Pricing</a> page.'
            ],
            [
                'question' => 'Are you affiliated with Airbnb?',
                'answer'   => 'No, AirBnb Claims is not affiliated with, endorsed by, or officially connected to Airbnb, Inc. We are an independent third-party service that specializes in helping hosts navigate the Airbnb claims process. Our expertise comes from years of experience working with Airbnb\'s policies and resolution processes.'
            ],
        ],

        'claims' => [
            [
                'question' => 'What types of damages can I claim?',
                'answer'   => '
                    You can claim for various types of damages including but not limited to:
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-600">
                        <li>Broken or damaged furniture</li>
                        <li>Stained carpets, rugs, or upholstery</li>
                        <li>Damaged appliances or electronics</li>
                        <li>Stolen or missing items</li>
                        <li>Wall or floor damage</li>
                        <li>Excessive cleaning costs</li>
                        <li>Unauthorized guests or parties</li>
                        <li>Smoke damage or violations of no-smoking policies</li>
                    </ul>
                '
            ],
            [
                'question' => 'How long do I have to file a claim after a guest checks out?',
                'answer'   => 'Airbnb requires that you report damage within 14 days of guest checkout or before the next guest checks in, whichever is earlier. For the best chance of approval, we recommend submitting claims as soon as possible after discovering the damage. Our platform allows you to quickly create and submit claims to meet these deadlines.'
            ],
            [
                'question' => 'What is the average approval rate for claims?',
                'answer'   => 'Our service has an approval rate of over 85% for properly documented claims, which is significantly higher than the industry average. The key factors affecting approval are the quality of evidence, timeliness of the claim submission, and adherence to Airbnb\'s policies. Our team specializes in optimizing all these factors to maximize your chances of approval.'
            ],
            [
                'question' => 'How long does the entire claims process take?',
                'answer'   => 'The timeline varies depending on the complexity of the claim and Airbnb\'s response time. Typically, from submission to resolution, the process takes 2-4 weeks. Simple claims with clear evidence may be resolved faster, while more complex cases might take longer. Our team works to expedite the process as much as possible while ensuring thorough preparation of your claim.'
            ],
            [
                'question' => 'What happens if my claim is rejected?',
                'answer'   => 'If your claim is rejected, we don\'t give up. Our team will analyze the reason for rejection and determine the best course of action. This may include filing an appeal with additional evidence, negotiating directly with the guest, or exploring alternative resolution options. We\'re committed to exhausting all available avenues to help you receive fair compensation for legitimate damages.'
            ],
        ],

        'evidence' => [
            [
                'question' => 'What documentation do I need for a successful claim?',
                'answer'   => '
                    For the strongest possible claim, you should include:
                    <ul class="list-disc pl-5 space-y-1 text-gray-600">
                        <li>Clear photos of the damage from multiple angles</li>
                        <li>"Before" photos showing the condition prior to the guest\'s stay (if available)</li>
                        <li>Communication with the guest about the damage through the Airbnb messaging system</li>
                        <li>Repair or replacement cost estimates from professionals</li>
                        <li>Original purchase receipts or proof of value for damaged items</li>
                        <li>Cleaning reports or professional assessments of damage</li>
                        <li>Airbnb reservation details including confirmation number, dates, and guest information</li>
                    </ul>
                    Our platform provides a structured way to upload and organize all these documents for your claim.
                '
            ],
            [
                'question' => 'How do I take effective photos of damages?',
                'answer'   => '
                    Follow these photography tips for effective documentation:
                    <ul class="list-disc pl-5 space-y-1 text-gray-600">
                        <li>Use good lighting – natural daylight is best</li>
                        <li>Take multiple shots of each damaged item from different angles</li>
                        <li>Include close-up shots showing detail of the damage</li>
                        <li>Take wider shots showing the location of the damage within your property</li>
                        <li>Include a reference object for scale when appropriate (like a ruler or coin)</li>
                        <li>Make sure photos are in focus and clearly show the damage</li>
                        <li>If possible, include timestamp information or metadata that shows when the photos were taken</li>
                    </ul>
                    Check out our <a href="{{ route("front.help.guides") }}#photography" class="text-accent hover:underline">Photography Guide</a> for more detailed instructions.
                '
            ],
            [
                'question' => 'What if I don't have "before" photos of the damaged items?',
                'answer'   => 'While "before" photos are helpful, they're not always necessary for a successful claim. If you don't have specific before photos, you can use listing photos if they show the items in question, maintenance records, prior guest reviews mentioning the condition of your property, or cleaning reports from before the guest's stay. Our team is skilled at building strong cases even without "before" photos by utilizing other types of evidence and documentation.'
            ],
            [
                'question' => 'How should I communicate with guests about damages?',
                'answer'   => '
                    When communicating with guests about damages:
                    <ul class="list-disc pl-5 space-y-1 text-gray-600">
                        <li>Always use the Airbnb messaging system so there's an official record</li>
                        <li>Be polite, professional, and factual</li>
                        <li>Describe the damage clearly and objectively</li>
                        <li>Include photos of the damage in your messages if possible</li>
                        <li>Avoid accusatory language or emotional reactions</li>
                        <li>Clearly state the resolution you're seeking</li>
                        <li>Give the guest an opportunity to respond</li>
                    </ul>
                    Even if the guest denies responsibility, having this communication documented strengthens your claim with Airbnb.
                '
            ],
        ],

        'payments' => [
            [
                'question' => 'How does your pricing work?',
                'answer'   => '
                    Our pricing is structured around subscription plans with different tiers based on your hosting volume and needs. Each plan includes a set number of claims per month and a success fee percentage on approved claims. The subscription ensures we can provide ongoing support and expertise, while the success fee model aligns our incentives with yours – we only make money when you successfully recover damages. Full pricing details are available on our <a href="'.route('user.subscription.plans').'" class="text-accent hover:underline">Plans & Pricing</a> page.
                '
            ],
            [
                'question' => 'When and how do I pay the success fee?',
                'answer'   => '
                    The success fee is only charged when your claim is approved and you receive payment from Airbnb. Once Airbnb issues your payment, you'll receive an invoice from us for the success fee percentage associated with your subscription plan. You can pay this invoice securely through our platform using your preferred payment method. This approach ensures you never pay a success fee unless we've actually helped you recover funds.
                '
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer'   => 'We accept all major credit and debit cards (Visa, Mastercard, American Express, Discover), PayPal, and bank transfers for subscription payments and success fees. All payment processing is handled through secure, PCI-compliant payment processors to ensure your financial information remains protected.'
            ],
            [
                'question' => 'Can I cancel my subscription?',
                'answer'   => '
                    Yes, you can cancel your subscription at any time through your account settings. When you cancel, you'll maintain access to your subscription benefits until the end of your current billing period. Any claims that are already in progress will continue to be handled by our team even after your subscription ends. There are no cancellation fees, and you can rejoin at any time if you need our services again in the future.
                '
            ],
            [
                'question' => 'Do you offer refunds if my claim is denied?',
                'answer'   => '
                    Our business model is designed so you only pay success fees on approved claims. Your subscription fee covers our platform access, expertise, and claim preparation services regardless of the outcome. While we cannot guarantee approval for every claim (as the final decision rests with Airbnb), our high success rate and expertise significantly improve your chances of receiving compensation. If your claim is denied, we don't charge any success fee, and we'll work with you to explore appeals or alternative approaches.
                '
            ],
        ],

        'account' => [
            [
                'question' => 'How do I create an account?',
                'answer'   => '
                    Creating an account is simple! Click on the "Sign Up" button in the top-right corner of our website. You'll need to provide your name, email address, and create a password. After submitting the registration form, you'll receive a verification email. Click the link in that email to verify your account, and you'll be ready to log in. Once logged in, you can complete your profile, select a subscription plan, and start creating claims.
                '
            ],
            [
                'question' => 'How many claims can I submit per month?',
                'answer'   => '
                    The number of claims you can submit per month depends on your subscription plan. Our basic plan includes up to 2 claims per month, while our premium plans offer more claims to accommodate hosts with multiple properties or higher booking volumes. If you need to submit additional claims beyond your plan\'s limit, you can upgrade your subscription at any time or purchase one-time additional claim credits. Check our <a href="'.route('user.subscription.plans').'" class="text-accent hover:underline">Plans page</a> for current plan details and claim allowances.
                '
            ],
            [
                'question' => 'Can I upgrade or downgrade my subscription?',
                'answer'   => '
                    Yes, you can upgrade or downgrade your subscription at any time through your account settings. When upgrading, the new subscription benefits take effect immediately, and you'll be charged a prorated amount for the remainder of your billing cycle. When downgrading, the changes will take effect at the start of your next billing cycle. This flexibility allows you to adjust your subscription as your hosting business and needs change over time.
                '
            ],
            [
                'question' => 'How do I update my payment information?',
                'answer'   => '
                    To update your payment information, log into your account and navigate to "Account Settings" > "Billing & Payment Methods." From there, you can add, edit, or remove payment methods, and set your default payment method for automatic subscription renewals. All payment information is securely stored with our payment processor, not on our servers, to ensure maximum security.
                '
            ],
            [
                'question' => 'What happens to my ongoing claims if I cancel my subscription?',
                'answer'   => '
                    If you cancel your subscription, we will continue to process and manage any claims that were initiated during your active subscription period until they reach a resolution. These claims are still subject to the success fee from your original plan if they are approved. However, you won't be able to submit new claims after cancellation unless you reactivate your subscription. Your account will remain active, allowing you to access historical claim information and resubscribe in the future if needed.
                '
            ],
            [
                'question' => 'What if I\'m not satisfied with your service?',
                'answer'   => 'You can remove our co-host access at any time with no questions asked. We only succeed when you do, so we\'re committed to providing excellent service and maximizing your claim payouts.'
            ],
        ],
    ];
@endphp

<h1 class="text-3xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h1>
<p class="text-lg text-gray-700 mb-8">
    Find answers to commonly asked questions about our AirBnb claims service, subscription plans, and the claims process.
</p>

{{-- 2. Category Buttons (General, Claims Process, etc.) --}}
<div x-data="{ activeCategory: 'general' }" class="mb-10">
    <div class="flex flex-wrap gap-4 mb-8">
        @foreach($faqs as $categoryKey => $questions)
            {{-- Convert category key to a human-friendly label --}}
            @php
                $labels = [
                    'general' => 'General',
                    'claims' => 'Claims Process',
                    'evidence' => 'Evidence & Documentation',
                    'payments' => 'Payments & Billing',
                    'account' => 'Account & Subscription',
                ];
            @endphp

            <button
                @click="activeCategory = '{{ $categoryKey }}'"
                :class="activeCategory === '{{ $categoryKey }}' 
                         ? 'bg-accent text-white' 
                         : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-4 py-2 rounded-full font-medium text-sm transition duration-150"
            >
                {{ $labels[$categoryKey] }}
            </button>
        @endforeach
    </div>

    {{-- 3. Loop over each category's FAQs --}}
    @foreach($faqs as $categoryKey => $questions)
        <div
            x-show="activeCategory === '{{ $categoryKey }}'"
            class="space-y-6"
            x-data="{ open: null }"
        >
            @foreach($questions as $index => $item)
                {{-- Use index+1 so accordion IDs start at 1 --}}
                @php $idx = $index + 1; @endphp

                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button
                        @click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors"
                    >
                        <span class="font-medium">{{ $item['question'] }}</span>
                        <svg
                            :class="{ 'rotate-180': open === {{ $idx }} }"
                            class="h-5 w-5 text-gray-500 transform transition-transform duration-200"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open === {{ $idx }}" class="px-4 pb-4 bg-white prose prose-sm">
                        {!! $item['answer'] !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

{{-- 4. "Still have questions?" Banner --}}
<div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Still have questions?</h3>
    <p class="text-gray-600 mb-4">If you couldn't find the answer you're looking for, our support team is here to help.</p>
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('user.tickets.create') }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            Contact Support
        </a>
        <a href="{{ route('front.help.guides') }}"
           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Read Our Guides
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection