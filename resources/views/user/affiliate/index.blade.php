@extends('front.layouts.app')
@section('meta_title') Refer & Earn @endsection

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
                <!-- Header Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                        <h1 class="text-3xl font-bold">Refer & Earn</h1>
                        <p class="text-blue-100 mt-2">Share your referral link and earn credits when your friends subscribe</p>
                    </div>
                </div>

                @include('includes.alerts')

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121L17 20zm-2.5-3.5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5zm-.5-5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Signups</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalSignups }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Purchased</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $purchasedCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Not Purchased</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $notPurchasedCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Earned</p>
                                <p class="text-2xl font-bold text-gray-800">{{ Helpers::setCurrency($totalEarned) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Balance Card -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Your Wallet Balance</p>
                            <p class="text-4xl font-bold mt-2">{{ Helpers::setCurrency(auth()->user()->wallet_balance) }}</p>
                            <p class="text-green-100 text-sm mt-2">Use this credit on your next subscription</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-full">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Referral Link Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Your Referral Link</h2>
                        <p class="text-gray-600 mt-1">Share this link with friends to earn {{ \App\Models\GeneralSetting::first()->referral_reward_percentage ?? 10 }}% credits when they subscribe</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" 
                                       id="referralLink" 
                                       value="{{ url('/?ref=' . auth()->user()->affiliate_code) }}" 
                                       readonly 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:outline-none font-mono text-sm">
                            </div>
                            <button onclick="copyReferralLink()" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium whitespace-nowrap">
                                Copy Link
                            </button>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="https://wa.me/?text={{ urlencode('Join ' . config('app.name') . ' using my referral link: ' . url('/?ref=' . auth()->user()->affiliate_code)) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.786"/>
                                </svg>
                                WhatsApp
                            </a>
                            <a href="mailto:?subject={{ urlencode('Join ' . config('app.name')) }}&body={{ urlencode('Join ' . config('app.name') . ' using my referral link: ' . url('/?ref=' . auth()->user()->affiliate_code)) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/?ref=' . auth()->user()->affiliate_code) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url('/?ref=' . auth()->user()->affiliate_code) }}&text={{ urlencode('Join ' . config('app.name')) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                        </div>
                    </div>
                </div>

                <!-- How it Works -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">How It Works</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl font-bold text-blue-600">1</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Share Your Link</h3>
                                <p class="text-gray-600 text-sm">Copy and share your unique referral link with friends and family</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl font-bold text-green-600">2</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Friend Subscribes</h3>
                                <p class="text-gray-600 text-sm">Your friend registers and purchases a subscription plan</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl font-bold text-purple-600">3</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Earn Credits</h3>
                                <p class="text-gray-600 text-sm">Get {{ \App\Models\GeneralSetting::first()->referral_reward_percentage ?? 10 }}% of their subscription value as wallet credits</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referral History -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Referral History</h2>
                        <p class="text-gray-600 mt-1">Track all users who signed up using your referral link</p>
                    </div>
                    <div class="overflow-x-auto">
                        @if($referrals->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Friend</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($referrals as $referral)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ strtoupper(substr($referral->name ?? 'U', 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $referral->name ?? 'Unknown User' }}</div>
                                                        <div class="text-sm text-gray-500">{{ $referral->email ?? 'No email' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($referral->created_at)->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($referral->created_at)->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($referral->has_purchased)
                                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Purchased
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Not Purchased
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($referral->has_purchased && $referral->credit_amount > 0)
                                                    <div class="text-sm font-bold text-green-600">
                                                        +{{ Helpers::setCurrency($referral->credit_amount) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Credited to wallet</div>
                                                @elseif($referral->has_purchased)
                                                    <div class="text-sm text-gray-500">{{ Helpers::setCurrency(0) }}</div>
                                                    <div class="text-xs text-gray-400">No credit (free subscription)</div>
                                                @else
                                                    <div class="text-sm text-gray-400">Pending</div>
                                                    <div class="text-xs text-gray-400">Awaiting purchase</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $referrals->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121L17 20zm-2.5-3.5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5zm-.5-5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No referrals yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Start sharing your referral link to see your friends here!</p>
                                <div class="mt-6">
                                    <button onclick="copyReferralLink()" class="inline-flex items-center px-5 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Copy Referral Link
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const referralLink = document.getElementById('referralLink');
    referralLink.select();
    referralLink.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(referralLink.value).then(function() {
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy. Please try again.');
    });
}
</script>
@endsection