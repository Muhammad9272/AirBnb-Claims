@extends('front.layouts.app')
@section('meta_title') Dashboard @endsection

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
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div class="mb-4 sm:mb-0">
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">
                                    {{ App\CentralLogics\Helpers::getGreeting() }}, {{ auth()->user()->name }}!
                                </h1>
                                <p class="text-blue-100 text-sm sm:text-base">
                                    @php
                                        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
                                    @endphp
                                    
                                    @if($activeSubscription)
                                        You have an active <span class="font-semibold">{{ $activeSubscription->plan->name }}</span> plan.
                                    @else
                                        You don't have an active subscription plan yet.
                                    @endif
                                </p>
                            </div>
                            <div>
                                @if(!$activeSubscription)
                                    <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Upgrade Now
                                    </a>
                                @else
                                    <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        New Claim
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Claims Card -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-b-4 border-blue-500 transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Claims</p>
                                <p class="text-2xl font-bold text-gray-800">{{ auth()->user()->claims->count() }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full h-12 w-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- In Progress Claims Card -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-b-4 border-purple-500 transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">In Progress</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ auth()->user()->claims->whereIn('status', ['pending', 'under_review'])->count() }}
                                </p>
                            </div>
                            <div class="bg-purple-100 rounded-full h-12 w-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approved Claims Card -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-b-4 border-green-500 transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Approved</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ auth()->user()->claims->where('status', 'approved')->count() }}
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full h-12 w-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Amount Card -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-b-4 border-yellow-500 transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Amount</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    ${{ number_format(auth()->user()->claims->sum('amount_requested'), 2) }}
                                </p>
                            </div>
                            <div class="bg-yellow-100 rounded-full h-12 w-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Recent Claims -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Claims</h2>
                            <a href="{{ route('user.claims.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @forelse(auth()->user()->claims->sortByDesc('created_at')->take(5) as $claim)
                                <div class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $claim->title }}</h3>
                                            <p class="text-xs text-gray-500 mb-1">Claim #{{ $claim->claim_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $claim->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-blue-100 text-blue-800',
                                                    'under_review' => 'bg-purple-100 text-purple-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                                $statusColor = $statusColors[$claim->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }} mb-2">
                                                {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                            </span>
                                            <span class="text-sm font-semibold">${{ number_format($claim->amount_requested, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No claims yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first claim.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Create First Claim
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Notifications & Activity -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                            <a href="{{ route('user.notifications') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @php
                                $notifications = auth()->user()->notifications()->latest()->take(5)->get();
                            @endphp
                            
                            @forelse($notifications as $notification)
                                <div class="px-6 py-4 hover:bg-gray-50 transition duration-150 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                                    <div class="flex">
                                        <div class="flex-shrink-0 mr-3">
                                            @php
                                                $iconMap = [
                                                    'claim_status_changed' => 'bg-purple-100 text-purple-600',
                                                    'new_comment' => 'bg-green-100 text-green-600',
                                                    'claim_submitted' => 'bg-blue-100 text-blue-600',
                                                    'default' => 'bg-gray-100 text-gray-600'
                                                ];
                                                
                                                $iconClass = $iconMap[$notification->type] ?? $iconMap['default'];
                                            @endphp
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $iconClass }}">
                                                @if($notification->type == 'claim_status_changed')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                @elseif($notification->type == 'new_comment')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                @elseif($notification->type == 'claim_submitted')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium {{ $notification->is_read ? 'text-gray-900' : 'text-blue-800' }}">
                                                {{ $notification->title }}
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">New</span>
                                                @endif
                                            </h3>
                                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit($notification->message, 100) }}</p>
                                            <div class="mt-1 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="text-xs text-blue-600 hover:text-blue-800">
                                                        View
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-5 5v-5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 3h-4a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 3H5a2 2 0 00-2 2v14a2 2 0 002 2h9" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No activity yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Your notifications will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Subscription Status / Quick Links -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Subscription Status</h2>
                    </div>
                    <div class="p-6">
                        @if($activeSubscription)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-green-50 rounded-lg p-4 border border-green-200 mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">{{ $activeSubscription->plan->name }}</h3>
                                    <p class="text-sm text-green-700">
                                        Your subscription is active until 
                                        <span class="font-semibold">{{ $activeSubscription->expires_at ? $activeSubscription->expires_at->format('F d, Y') : 'Lifetime' }}</span>
                                    </p>
                                </div>
                                <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Manage Plan
                                    </a>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-base font-medium text-gray-900 mb-3">Your Plan Benefits</h3>
                                <ul class="space-y-2">


 @php
                            $feature_list = explode("\n", $activeSubscription->plan->features);
                        @endphp

                                    @foreach($feature_list as $feature)
                <li class="flex items-center text-gray-600">
                  <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ $feature }}
                </li>
                @endforeach
                                    
                                </ul>
                            </div>
                        @else
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-yellow-50 rounded-lg p-4 border border-yellow-200 mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800">No Active Subscription</h3>
                                    <p class="text-sm text-yellow-700">
                                        Subscribe to a plan to start filing claims and accessing premium features.
                                    </p>
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                        View Plans
                                    </a>
                                </div>
                            </div>
                            
                            <div class="text-center py-4">
                                <h3 class="text-base font-medium text-gray-900 mb-3">Why Subscribe?</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="text-accent text-xl mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-medium">Protected Claims</h4>
                                        <p class="text-sm text-gray-600 mt-1">We handle your AirBnB claims professionally</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="text-accent text-xl mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-medium">Maximize Recovery</h4>
                                        <p class="text-sm text-gray-600 mt-1">Get the highest possible claim approvals</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="text-accent text-xl mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-medium">Fast Processing</h4>
                                        <p class="text-sm text-gray-600 mt-1">Quick turnaround on all claim submissions</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
