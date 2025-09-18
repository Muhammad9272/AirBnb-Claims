@extends('front.layouts.app')
@section('meta_title') Checkout - {{ $plan->name }} @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-800">Complete Your Subscription</h1>
                        <a href="{{ route('user.subscription.plans') }}" class="text-accent hover:text-accent-dark">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Plans
                        </a>
                    </div>
                </div>
                
                @include('includes.alerts')
                
                <div class="p-6">
                    <!-- Plan Details -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Plan Summary</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700 font-medium">{{ $plan->name }}</span>
                            <span class="text-lg font-bold">{{ Helpers::setCurrency($plan->price) }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Billing: {{ ucfirst(Helpers::setInterval($plan->interval)) }}</p>
                            <p>Claims limit: {{ $plan->claims_limit ? $plan->claims_limit : 'Unlimited' }}</p>
                        </div>
                    </div>

                    <!-- Service Agreement Section -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Service Agreement</h3>
                        
                        <div class="text-sm text-gray-700 max-h-64 overflow-y-auto border border-gray-200 rounded bg-white p-4 mb-4">
                            <h4 class="font-bold text-gray-800 mb-2">CLAIMPILOT+ SERVICE AGREEMENT</h4>
                            <p class="mb-3">This Service Agreement ("Agreement") is entered into by and between the undersigned user ("Client") and Claimpilot+ ("Company") as of the date of acceptance below.</p>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">1. Services Provided</h5>
                            <p class="mb-3">Company agrees to provide claims management services to Client, which includes filing, managing, and processing damage or reimbursement claims ("Claims") on behalf of Client for properties listed on Airbnb ("Services").</p>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">2. Payment Terms</h5>
                            <ul class="list-disc list-inside mb-3 space-y-1">
                                <li>Client agrees to pay Company <strong>20% (twenty percent)</strong> of the gross payout of any successful Claim submitted and processed by Company on Client's behalf.</li>
                                <li>Payment will be automatically charged to the payment method provided upon payout disbursement from Airbnb or invoiced with net-7 payment terms, at the discretion of the Company.</li>
                                <li>Client acknowledges and agrees that Company's fee is only due upon <strong>successful payout</strong> of Claims.</li>
                            </ul>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">3. Authorization & Access</h5>
                            <ul class="list-disc list-inside mb-3 space-y-1">
                                <li>Client agrees to add Company as a <strong>Co-Host</strong> on their Airbnb account to allow full access necessary for claim submission and management, including but not limited to: viewing bookings, messaging guests, accessing calendars, and submitting claims.</li>
                                <li>Company agrees that its access will only be used for the purpose of filing, managing, and processing Claims and for no other purpose. Company will <strong>not interfere</strong> with bookings, pricing, guest communication, or property settings outside the scope of Claims management.</li>
                            </ul>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">4. Confidentiality</h5>
                            <p class="mb-3">Company shall keep confidential all information obtained through Client's Airbnb account and shall not disclose such information to any third party except as required to perform Services or as required by law.</p>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">5. Limitation of Liability</h5>
                            <ul class="list-disc list-inside mb-3 space-y-1">
                                <li>Client agrees that Company is not liable for any denied claims, partial payouts, platform suspensions, or negative outcomes resulting from Airbnb's policies or decisions.</li>
                                <li>Company shall not be liable for indirect, incidental, consequential, special, or punitive damages arising out of or relating to this Agreement or the Services provided.</li>
                            </ul>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">6. Indemnification</h5>
                            <p class="mb-3">Client agrees to indemnify and hold harmless Company and its owners, employees, and contractors from any claims, damages, losses, or expenses (including reasonable attorneys' fees) arising out of Client's use of Services, violation of this Agreement, or violation of Airbnb's terms resulting from actions taken on Client's behalf within the agreed scope.</p>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">7. Term and Termination</h5>
                            <ul class="list-disc list-inside mb-3 space-y-1">
                                <li>This Agreement shall remain in effect for as long as Client maintains an active subscription with Company.</li>
                                <li>Either party may terminate this Agreement with <strong>7 days written notice</strong>. All pending claims submitted prior to termination shall remain under the terms of this Agreement, and Company will be entitled to its fee upon successful payout.</li>
                            </ul>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">8. Governing Law</h5>
                            <p class="mb-3">This Agreement shall be governed by and construed in accordance with the laws of the state of <strong>Nevada</strong>, without regard to its conflict of laws principles.</p>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">9. Entire Agreement</h5>
                            <ul class="list-disc list-inside mb-3 space-y-1">
                                <li>This Agreement constitutes the entire understanding between the parties and supersedes all prior agreements, oral or written, with respect to the subject matter herein.</li>
                                <li>No amendment or modification of this Agreement shall be binding unless in writing and signed by both parties.</li>
                            </ul>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">10. Contact Information</h5>
                            <div class="bg-gray-100 p-3 rounded mb-3">
                                <p class="font-medium">Claimpilot+</p>
                                <p>JJ&J Investments LLC</p>
                                <p>Address: 930 S 4th St, Ste 209 #2631 Las Vegas, NV 89101</p>
                                <p>Email: support@claimpilotplus.com</p>
                            </div>
                            
                            <h5 class="font-semibold text-gray-800 mb-2">11. Acceptance</h5>
                            <p class="mb-3">By clicking "I Agree" and completing checkout, Client acknowledges that they have read, understood, and agree to be legally bound by the terms of this Agreement.</p>
                            
                            
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="agreement_checkbox" name="agreement_accepted" required class="mt-1 h-4 w-4 text-accent border-gray-300 rounded focus:ring-accent">
                            <label for="agreement_checkbox" class="text-sm text-gray-700 cursor-pointer">
                                <strong>I agree to the Claimpilot+ Service Agreement</strong> and authorize Claimpilot+ to be added as a Co-Host on my Airbnb account solely for the purpose of claims management.
                            </label>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('subscription.process.payment') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="payment_method" value="stripe">
                        <input type="hidden" name="agreement_accepted" id="agreement_accepted_hidden" value="0">
                        
                        <div class="border-t border-gray-200 mt-6 pt-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-medium">Total Due Today:</span>
                                <span class="text-2xl font-bold">{{ Helpers::setCurrency($plan->price) }}</span>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">
                                            You will be redirected to Stripe's secure payment page to complete your subscription.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" id="payment-button" class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-medium text-center flex items-center justify-center cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Proceed to Payment
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>By subscribing, you agree to our <a href="{{ route('front.help.terms') }}" class="text-accent hover:underline">Terms of Service</a> and <a href="{{ route('front.help.privacy') }}" class="text-accent hover:underline">Privacy Policy</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const agreementCheckbox = document.getElementById('agreement_checkbox');
    const paymentButton = document.getElementById('payment-button');
    const hiddenAgreementInput = document.getElementById('agreement_accepted_hidden');
    
    agreementCheckbox.addEventListener('change', function() {
        if (this.checked) {
            paymentButton.disabled = false;
            paymentButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            paymentButton.classList.add('bg-accent', 'hover:bg-accent-dark');
            hiddenAgreementInput.value = '1';
        } else {
            paymentButton.disabled = true;
            paymentButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            paymentButton.classList.remove('bg-accent', 'hover:bg-accent-dark');
            hiddenAgreementInput.value = '0';
        }
    });
    
    // Prevent form submission if agreement is not accepted
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        if (!agreementCheckbox.checked) {
            e.preventDefault();
            alert('Please accept the Service Agreement to proceed with your subscription.');
        }
    });
});
</script>
@endsection