@extends('front.help.layout')
@section('meta_title', 'Privacy Policy')

@section('help_content')
<div class="prose prose-lg max-w-none">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Policy</h1>
    
    <p class="text-gray-700">
        Last Updated: {{ date('F d, Y') }}
    </p>
    
    <div class="my-8">
        <p class="text-gray-700">
            This Privacy Policy describes how AirBnb Claims ("we", "us", or "our") collects, uses, and shares your personal information when you use our website and services. We are committed to protecting your privacy and ensuring the security of your personal information.
        </p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Information We Collect</h2>
    
    <div class="text-gray-700 space-y-4">
        <p><strong>1.1. Information You Provide to Us</strong></p>
        <ul class="list-disc pl-6 space-y-2">
            <li>Account Information: When you create an account, we collect your name, email address, phone number, and password.</li>
            <li>Profile Information: You may choose to provide additional information such as your profile picture, address, and gender.</li>
            <li>Claim Information: When you submit a claim, we collect details about your property, the damage incurred, reservation information, guest details, and any photos or documents you upload as evidence.</li>
            <li>Payment Information: If you subscribe to a paid plan, we collect payment information, though actual payment processing is handled by our secure payment processors.</li>
            <li>Communications: We keep records of your communications with our support team and any feedback you provide.</li>
        </ul>
        
        <p><strong>1.2. Information Collected Automatically</strong></p>
        <ul class="list-disc pl-6 space-y-2">
            <li>Usage Information: We collect information about how you interact with our website, including pages visited, features used, and time spent on the platform.</li>
            <li>Device Information: We collect information about the device you use to access our Services, including hardware model, operating system, browser type, and IP address.</li>
            <li>Cookies and Similar Technologies: We use cookies and similar tracking technologies to collect information about your browsing activities and to provide and improve our Services.</li>
        </ul>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. How We Use Your Information</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>We use the information we collect to:</p>
        <ul class="list-disc pl-6 space-y-2">
            <li>Provide, maintain, and improve our Services</li>
            <li>Process and manage your claims with Airbnb</li>
            <li>Process your payments and manage your subscription</li>
            <li>Communicate with you about your account, claims, and our Services</li>
            <li>Send you technical notices, updates, security alerts, and support messages</li>
            <li>Respond to your comments, questions, and customer service requests</li>
            <li>Monitor and analyze trends, usage, and activities in connection with our Services</li>
            <li>Detect, investigate, and prevent fraudulent transactions and other illegal activities</li>
            <li>Personalize your experience and deliver content relevant to your interests</li>
        </ul>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. How We Share Your Information</h2>
    
    <div class="text-gray-700 space-y-4">
        <p>We may share your personal information in the following situations:</p>
        <ul class="list-disc pl-6 space-y-2">
            <li><strong>With Airbnb:</strong> To process your claims, we may share relevant information with Airbnb through their Resolution Center.</li>
            <li><strong>With Service Providers:</strong> We share information with third-party vendors and service providers who perform services for us or on our behalf, such as payment processing, data analysis, email delivery, hosting services, and customer service.</li>
            <li><strong>For Legal Purposes:</strong> We may disclose your information if required to do so by law or in response to valid legal requests, such as subpoenas, court orders, or government regulations.</li>
            <li><strong>With Your Consent:</strong> We may share your information with third parties when you have given us your consent to do so.</li>
            <li><strong>Business Transfers:</strong> If we are involved in a merger, acquisition, or sale of all or a portion of our assets, your information may be transferred as part of that transaction.</li>
        </ul>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Your Privacy Choices</h2>
    
    <div class="text-gray-700 space-y-4">
        <p><strong>4.1. Account Information</strong></p>
        <p>You can update your account information at any time by accessing your account settings on our website. If you wish to delete your account, please contact our support team.</p>
        
        <p><strong>4.2. Marketing Communications</strong></p>
        <p>You can opt out of receiving promotional emails from us by following the instructions in those emails. Even if you opt out, we may still send you non-promotional emails, such as those about your account or our ongoing business relations.</p>
        
        <p><strong>4.3. Cookies</strong></p>
        <p>Most web browsers are set to accept cookies by default. If you prefer, you can usually choose to set your browser to remove or reject cookies. Please note that if you choose to remove or reject cookies, this could affect the availability and functionality of our Services.</p>
    </div>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Data Security</h2>
    
    <p class="text-gray-700">
        We implement appropriate technical and organizational measures to protect the security of your personal information. However, please be aware that no method of transmission over the internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Data Retention</h2>
    
    <p class="text-gray-700">
        We will retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When determining how long to retain information, we consider the amount, nature, and sensitivity of the information, the potential risk of harm from unauthorized use or disclosure, and whether we can achieve the purposes of processing through other means.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Children's Privacy</h2>
    
    <p class="text-gray-700">
        Our Services are not directed to children under 18 years of age, and we do not knowingly collect personal information from children under 18. If we learn we have collected personal information from a child under 18, we will delete that information as quickly as possible. If you believe a child under 18 may have provided us personal information, please contact us.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">8. International Data Transfers</h2>
    
    <p class="text-gray-700">
        We are based in the United Kingdom and process information in various locations. The information we collect may be transferred to, stored, and processed in countries other than the country in which you reside. If you are located in the European Economic Area (EEA), we ensure appropriate safeguards are in place for transferring your personal information.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">9. Changes to This Privacy Policy</h2>
    
    <p class="text-gray-700">
        We may update this Privacy Policy from time to time to reflect changes to our information practices. If we make any material changes, we will notify you by updating the date at the top of this Privacy Policy, and in some cases, we may provide additional notice. We encourage you to review the Privacy Policy whenever you access our Services to stay informed about our information practices.
    </p>
    
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">10. Contact Us</h2>
    
    <p class="text-gray-700">
        If you have any questions or concerns about this Privacy Policy or our privacy practices, please contact us at:
    </p>
    
    <div class="text-gray-700 mt-4">
        <p><strong>Email:</strong> <a href="mailto:privacy@airbnbclaims.com" class="text-accent hover:text-accent-dark">privacy@airbnbclaims.com</a></p>
        <p><strong>Address:</strong> 123 Business Street, London, UK</p>
    </div>
</div>

<div class="mt-10 p-6 bg-gray-50 rounded-xl border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Your Privacy Matters</h3>
    <p class="text-gray-600 mb-4">If you have any questions about our privacy practices or would like to exercise your data rights, our support team is here to help.</p>
    <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        Contact Support
    </a>
</div>
@endsection


