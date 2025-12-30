<div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-4">
    <!-- User Profile Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mr-4 flex-shrink-0 overflow-hidden">
                @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
                    <img src="{{ Helpers::image(auth()->user()->photo, 'user/avatar/', 'user.png') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-white text-xl font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-monument text-lg font-semibold truncate">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-white/90 break-all">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="bg-white bg-opacity-10 rounded-lg p-3 backdrop-blur-sm">
                <div class="text-lg font-bold">{{ auth()->user()->claims->count() }}</div>
                <div class="text-xs text-blue-100">Total Claims</div>
            </div>
            <div class="bg-white bg-opacity-10 rounded-lg p-3 backdrop-blur-sm">
                <div class="text-lg font-bold">{{ auth()->user()->claims->where('status', 'approved')->count() }}</div>
                <div class="text-xs text-blue-100">Approved</div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Claims Section -->
        <div class="space-y-1">
            <div class="flex items-center space-x-3 p-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">Claims Management</span>
            </div>
            
            <!-- My Claims -->
            <a href="{{ route('user.claims.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('user.claims.index') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium">My Claims</span>
                @if(auth()->user()->claims->where('status', 'pending')->count() > 0)
                    <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ auth()->user()->claims->where('status', 'pending')->count() }}</span>
                @endif
            </a>

            <!-- Create New Claim -->
            <a href="{{ Route::is('user.claims.create') ? route('user.claims.create') : 'javascript:void(0)' }}"
            @if (!Route::is('user.claims.create'))
                onclick="openEvidenceModal()"
            @endif 
            class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-green-50 hover:text-green-600 {{ request()->routeIs('user.claims.create') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : 'text-gray-600 hover:text-green-600' }} cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="font-medium">New Claim</span>
            </a>
        </div>

        <!-- Subscription Section -->
        <div class="space-y-1">
            <div class="flex items-center space-x-3 p-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">Subscription</span>
            </div>

            <!-- Current Plan -->
            <a href="{{ route('user.subscription.plans') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-purple-50 hover:text-purple-600 {{ request()->routeIs('user.subscription.*') ? 'bg-purple-50 text-purple-600 border-r-4 border-purple-600' : 'text-gray-600 hover:text-purple-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div class="flex-1">
                    <span class="font-medium block">My Plan</span>
                    @php
                        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
                    @endphp
                    @if($activeSubscription)
                        <span class="text-xs text-green-600">{{ $activeSubscription->plan->name }}</span>
                    @else
                        <span class="text-xs text-red-500">No Active Plan</span>
                    @endif
                </div>
            </a>

            <!-- Transactions -->
            <a href="{{ route('user.subscription.transactions') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('user.subscription.transactions') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="font-medium">Transactions</span>
            </a>
        </div>

        <!-- Account Section -->
        <div class="space-y-1">
            <div class="flex items-center space-x-3 p-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">Account</span>
            </div>

            <!-- Profile Settings -->
            <a href="{{ route('user.profile') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('user.profile') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Profile Settings</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('user.notifications') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-yellow-50 hover:text-yellow-600 {{ request()->routeIs('user.notifications') ? 'bg-yellow-50 text-yellow-600 border-r-4 border-yellow-600' : 'text-gray-600 hover:text-yellow-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19H6.5A2.5 2.5 0 014 16.5v-9A2.5 2.5 0 016.5 5h9A2.5 2.5 0 0118 7.5V11" />
                </svg>
                <span class="font-medium">Notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
        </div>

        <!-- Affiliate Section -->
        <div class="space-y-1">
            <div class="flex items-center space-x-3 p-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">Affiliate</span>
            </div>
            
            <a href="{{ route('user.affiliate.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-purple-600 {{ request()->routeIs('user.affiliate.index') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-purple-600 border-r-4 border-purple-600' : 'text-gray-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-medium">Refer & Earn</span>
                <span class="ml-auto bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs px-2 py-1 rounded-full">New</span>
            </a>

            <a href="{{ route('user.affiliate.wallet') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 hover:text-teal-600 {{ request()->routeIs('user.affiliate.wallet') ? 'bg-gradient-to-r from-green-50 to-teal-50 text-teal-600 border-r-4 border-teal-600' : 'text-gray-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="font-medium">Wallet</span>
            </a>

            @if(auth()->user()->role_type === 'influencer')
                <a href="{{ route('user.influencer.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:text-pink-600 {{ request()->routeIs('user.influencer.index') ? 'bg-gradient-to-r from-purple-50 to-pink-50 text-pink-600 border-r-4 border-pink-600' : 'text-gray-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="font-medium">Influencer Dashboard</span>
                    <span class="ml-auto px-2 py-1 text-xs font-semibold text-white bg-gradient-to-r from-purple-500 to-pink-500 rounded-full">
                        Pro
                    </span>
                </a>
            @endif
        </div>

        <!-- Support Section -->
        <div class="space-y-1">
            <div class="flex items-center space-x-3 p-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">Support</span>
            </div>

            <!-- Help Center -->
            <a href="{{ route('front.help.overview') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('front.help.overview') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Help Center</span>
            </a>

            <!-- Contact Support -->
            <a href="{{ route('user.tickets.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-green-50 hover:text-green-600 {{ request()->routeIs('user.tickets.index') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : 'text-gray-600 hover:text-green-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-medium">Contact Support</span>
            </a>
        </div>

        <!-- Logout -->
        <div class="pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg transition-all duration-200 hover:bg-red-50 hover:text-red-600 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Current Subscription Status -->
    @php
        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
    @endphp
    @if($activeSubscription)
    <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
        <div class="text-center">
            <div class="text-sm font-medium text-green-800">{{ $activeSubscription->plan->name }}</div>
            <div class="text-xs text-green-600">
                @if($activeSubscription->expires_at)
                    Expires: {{ $activeSubscription->expires_at->format('M d, Y') }}
                @else
                    Active Subscription
                @endif
            </div>
            <div class="mt-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3"/>
                    </svg>
                    Active
                </span>
            </div>
        </div>
    </div>
    @else
    <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
        <div class="text-center">
            <div class="text-sm font-medium text-orange-800">No Active Plan</div>
            <div class="text-xs text-orange-600 mb-3">Subscribe to start filing claims</div>
            <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Upgrade Now
            </a>
        </div>
    </div>
    @endif
</div>
@include('user.partials.evidence-requirements-modal')

