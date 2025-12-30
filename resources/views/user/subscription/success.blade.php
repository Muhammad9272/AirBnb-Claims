@extends('front.layouts.app')
@section('meta_title') Subscription Complete @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">Subscription Completed!</h1>
                </div>
                
                <div class="p-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8 text-center">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-500 mb-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-medium text-green-800 mb-2">Thank You for Your Subscription!</h2>
                        <p class="text-green-700">Your subscription has been successfully processed.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Subscription Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Plan</p>
                                <p class="font-medium">{{ $subscription->plan->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Amount</p>
                                <p class="font-medium">${{ number_format($subscription->price, 2) }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Active Until</p>
                                <p class="font-medium">{{ $subscription->expires_at ? $subscription->expires_at->format('F j, Y') : 'Processing' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Claims Limit</p>
                                <p class="font-medium">{{ $subscription->plan->claims_limit ? $subscription->plan->claims_limit : 'Unlimited' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center space-y-4">
                        <p class="text-gray-700">You can now start using our service to manage your Airbnb claims.</p>
                        
                        <div class="flex flex-col md:flex-row gap-4 justify-center">
                            <a onclick="openEvidenceModal()" class="inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-accent hover:bg-accent-dark cursor-pointer">
                                <i class="fas fa-file-invoice mr-2"></i> Create Your First Claim
                            </a>
                            
                            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-home mr-2"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.partials.evidence-requirements-modal')
@endsection
