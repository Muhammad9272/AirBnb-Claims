@extends('front.layouts.app')
@section('meta_title') My Profile @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Profile Overview Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center p-1">
                                        @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
                                            <img src="{{ \App\CentralLogics\Helpers::image(auth()->user()->photo, 'user/avatar/') }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            <div class="w-full h-full rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-bold text-3xl">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-center md:text-left text-white">
                                <h1 class="text-2xl md:text-3xl font-bold">{{ auth()->user()->name }}</h1>
                                <p class="text-blue-100 mt-1">{{ auth()->user()->email }}</p>
                                <p class="text-blue-100 mt-1">Member since {{ auth()->user()->created_at->format('F Y') }}</p>
                                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start">
                                    @php
                                        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
                                    @endphp
                                    
                                    @if($activeSubscription)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            {{ $activeSubscription->plan->name }} Plan
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                            No Active Plan
                                        </span>
                                    @endif
                                    
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ auth()->user()->claims->count() }} Claims
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('user.profile') }}" class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500 flex items-center hover:shadow-lg transition duration-300">
                        <div class="bg-blue-100 rounded-full h-12 w-12 flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
                            <p class="text-sm text-gray-500">Update your information</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('user.change-password') }}" class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500 flex items-center hover:shadow-lg transition duration-300">
                        <div class="bg-purple-100 rounded-full h-12 w-12 flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Password</h3>
                            <p class="text-sm text-gray-500">Change your password</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('user.subscription.plans') }}" class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500 flex items-center hover:shadow-lg transition duration-300">
                        <div class="bg-green-100 rounded-full h-12 w-12 flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Subscription</h3>
                            <p class="text-sm text-gray-500">Manage your plan</p>
                        </div>
                    </a>
                </div>

                <!-- Profile Summary -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Profile Summary</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Full Name</h3>
                                <p class="mt-1 text-base text-gray-900">{{ auth()->user()->name }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Email Address</h3>
                                <p class="mt-1 text-base text-gray-900">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Phone Number</h3>
                                <p class="mt-1 text-base text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Country</h3>
                                <p class="mt-1 text-base text-gray-900">
                                    @if(auth()->user()->country)
                                        {{ \App\CentralLogics\Helpers::getCountries()[auth()->user()->country] ?? auth()->user()->country }}
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-500">Bio</h3>
                                <p class="mt-1 text-base text-gray-900">{{ auth()->user()->bio ?? 'No bio information provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('user.account-settings') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Claims Activity -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Claims Activity</h2>
                            <a href="{{ route('user.claims.index') }}" class="text-sm text-accent hover:text-accent-dark flex items-center">
                                View All Claims
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <h3 class="text-sm font-medium text-blue-800 mb-1">Total Claims</h3>
                                <p class="text-3xl font-bold text-blue-900">{{ auth()->user()->claims->count() }}</p>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4 text-center">
                                <h3 class="text-sm font-medium text-purple-800 mb-1">In Progress</h3>
                                <p class="text-3xl font-bold text-purple-900">
                                    {{ auth()->user()->claims->whereIn('status', ['pending', 'under_review'])->count() }}
                                </p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <h3 class="text-sm font-medium text-green-800 mb-1">Approved</h3>
                                <p class="text-3xl font-bold text-green-900">
                                    {{ auth()->user()->claims->where('status', 'approved')->count() }}
                                </p>
                            </div>
                        </div>
                        
                        @if(auth()->user()->claims->count() > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Recent Claims</h3>
                                <div class="space-y-3">
                                    @foreach(auth()->user()->claims->sortByDesc('created_at')->take(3) as $claim)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-150">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($claim->title, 40) }}</h4>
                                                <p class="text-xs text-gray-500">Claim #{{ $claim->claim_number }} | {{ $claim->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'bg-blue-100 text-blue-800',
                                                        'under_review' => 'bg-purple-100 text-purple-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusClass = $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }} mr-2">
                                                    {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                                </span>
                                                <a href="{{ route('user.claims.show', $claim->id) }}" class="text-accent hover:text-accent-dark">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No claims yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first claim.</p>
                                <div class="mt-6">
                                    <a onclick="openEvidenceModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent cursor-pointer">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create First Claim
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.partials.evidence-requirements-modal')
@endsection

