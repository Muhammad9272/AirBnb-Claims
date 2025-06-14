@extends('front.help.layout')

@section('meta_title', 'Terms of Service')
@section('meta_description', "Read our terms of service to understand the rules and guidelines for using our platform.")

@section('help_content')
<div class="prose prose-lg max-w-none">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h1>
    
    <p class="text-gray-700">
        Last Updated: {{ date('F d, Y') }}
    </p>
    
    <div class="my-8">
        <p class="text-gray-700">
            Welcome to AirBnb Claims. These Terms of Service ("Terms") govern your use of our website, products, and services ("Services"). By using our Services, you agree to these Terms. Please read them carefully.
        </p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Acceptance of Terms</h2>
    
    <p class="text-gray-700">
        By accessing or using our Services, you acknowledge that you have read, understood, and agree to be bound by these Terms. If you do not agree to these Terms, you should not use our Services.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Service Description</h2>
    
    <p class="text-gray-700">
        AirBnb Claims provides a platform to assist Airbnb hosts in filing, documenting, and managing damage claims through Airbnb's Resolution Center. Our Services include claim preparation, documentation review, submission assistance, and follow-up communications with Airbnb on your behalf.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. User Accounts</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>3.1. You must create an account to use our Services. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate, current, and complete.</p>
        
        <p>3.2. You are responsible for safeguarding your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>
        
        <p>3.3. We reserve the right to disable any user account at any time if, in our opinion, you have failed to comply with these Terms.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Subscription Plans and Payments</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>4.1. Some of our Services require payment through subscription plans. All payments are processed through our secure payment providers.</p>
        
        <p>4.2. By subscribing to a paid plan, you agree to the pricing, payment terms, and conditions outlined during the subscription process.</p>
        
        <p>4.3. Subscriptions are automatically renewed unless canceled before the renewal date. You can cancel your subscription at any time through your account settings.</p>
        
        <p>4.4. We reserve the right to change our subscription prices with notice. Any price changes will apply to billing cycles after the notice has been given.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. User Content and Submissions</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>5.1. By submitting content to our platform (including photos, descriptions, and communications), you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, adapt, publish, and distribute such content for the purpose of providing our Services.</p>
        
        <p>5.2. You represent and warrant that you own or have the necessary rights to submit the content, and that the content does not infringe on the intellectual property rights or other rights of any third party.</p>
        
        <p>5.3. We do not claim ownership of your content, but we need these rights to operate our Services effectively on your behalf.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Limitations of Liability</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>6.1. Our Services are provided "as is" without any warranties, expressed or implied.</p>
        
        <p>6.2. We do not guarantee the outcome of any claim submitted through our platform. The final decision on claims rests with Airbnb, and we cannot be held responsible for rejected claims or unsatisfactory compensation amounts.</p>
        
        <p>6.3. We shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use our Services.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Relationship with Airbnb</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>7.1. AirBnb Claims is not affiliated with, endorsed by, or connected to Airbnb, Inc. We are an independent service that assists hosts with their claims.</p>
        
        <p>7.2. Our Services do not guarantee approval from Airbnb for any claim, and we operate within Airbnb's existing policies and guidelines for claims.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">8. Termination</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>8.1. We may terminate or suspend your account and access to our Services immediately, without prior notice or liability, for any reason, including if you breach these Terms.</p>
        
        <p>8.2. Upon termination, your right to use the Services will cease immediately. However, all provisions of these Terms which by their nature should survive termination shall survive, including ownership provisions, warranty disclaimers, indemnity, and limitations of liability.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">9. Changes to Terms</h2>
    
    <p class="text-gray-700">
        We reserve the right to modify these Terms at any time. If we make changes, we will notify you by updating the date at the top of these Terms and, in some cases, we may provide additional notice. Your continued use of our Services after any changes indicates your acceptance of the new Terms.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">10. Contact Information</h2>
    
    <p class="text-gray-700">
        If you have any questions about these Terms, please contact us at <a href="mailto:support@airbnbclaims.com" class="text-accent hover:text-accent-dark">support@airbnbclaims.com</a>.
    </p>
</div>

<div class="mt-10 p-6 bg-gray-50 rounded-xl border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Need Clarification?</h3>
    <p class="text-gray-600 mb-4">If you have any questions about our Terms of Service, our support team is here to help.</p>
    <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        Contact Support
    </a>
</div>
@endsection

@section('script')

@endsection
