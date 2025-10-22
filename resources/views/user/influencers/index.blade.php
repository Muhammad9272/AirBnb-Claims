@extends('front.layouts.app')
@section('meta_title') Influencer Dashboard @endsection


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
                    <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white p-6">
                        <h1 class="text-3xl font-bold">Influencer Dashboard</h1>
                        <p class="text-purple-100 mt-2">Track your affiliate performance and commission earnings</p>
                    </div>
                </div>

                @include('includes.alerts')

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Referrals -->
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-gray-600 text-sm mb-2">Total Referrals</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalReferrals }}</p>
                        @if($monthlyReferrals > 0)
                            <p class="text-xs text-blue-600 mt-2">+{{ $monthlyReferrals }} this month</p>
                        @endif
                    </div>

                    <!-- Total Earnings (Paid) -->
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-gray-600 text-sm mb-2">Total Earnings</p>
                        <p class="text-3xl font-bold text-gray-800">{{ \App\CentralLogics\Helpers::setCurrency($totalEarnings) }}</p>
                        @if($monthlyEarnings > 0)
                            <p class="text-xs text-green-600 mt-2">+{{ \App\CentralLogics\Helpers::setCurrency($monthlyEarnings) }} this month</p>
                        @endif
                    </div>

                    <!-- Pending Earnings -->
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-gray-600 text-sm mb-2">Pending</p>
                        <p class="text-3xl font-bold text-gray-800">{{ \App\CentralLogics\Helpers::setCurrency($pendingEarnings) }}</p>
                        <p class="text-xs text-gray-500 mt-2">Awaiting approval</p>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-gray-600 text-sm mb-2">Conversion Rate</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $conversionRate }}%</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $paidCommissions }} paid / {{ $approvedCommissions }} approved</p>
                    </div>
                </div>

                <!-- Affiliate Link Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Your Affiliate Link</h2>
                        <p class="text-gray-600 mt-1">Share this link to track referrals and earn {{ $gs->influencer_commission_percentage ?? 10 }}% commission on approved claims</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <div class="flex items-center bg-gray-100 rounded-lg px-4 py-3">
                                    <input type="text" 
                                           id="affiliateLink" 
                                           value="{{ url('/?ref=' . auth()->user()->affiliate_code) }}" 
                                           readonly 
                                           class="flex-1 bg-transparent border-none focus:outline-none text-gray-700">
                                    <span class="ml-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                        {{ auth()->user()->affiliate_code }}
                                    </span>
                                </div>
                            </div>
                            <button onclick="copyAffiliateLink()" 
                                    id="copyBtn"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center space-x-2">
                                <i class="las la-copy text-xl"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/?ref=' . auth()->user()->affiliate_code)) }}&text={{ urlencode('Check out this amazing Airbnb claims management service!') }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors duration-200">
                                <i class="lab la-twitter text-xl mr-2"></i>
                                Share on Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/?ref=' . auth()->user()->affiliate_code)) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                <i class="lab la-facebook text-xl mr-2"></i>
                                Share on Facebook
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url('/?ref=' . auth()->user()->affiliate_code)) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors duration-200">
                                <i class="lab la-linkedin text-xl mr-2"></i>
                                Share on LinkedIn
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Commission Structure -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Commission Structure</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $gs->influencer_commission_percentage ?? 10 }}%</div>
                                <p class="text-gray-600">Commission Rate</p>
                                <p class="text-sm text-gray-500 mt-2">On approved claims</p>
                            </div>
                            <div class="text-center p-4 bg-pink-50 rounded-lg">
                                <div class="text-3xl font-bold text-pink-600 mb-2">
                                    @if($gs->influencer_commission_duration_days)
                                        {{ $gs->influencer_commission_duration_days }} Days
                                    @else
                                        Unlimited
                                    @endif
                                </div>
                                <p class="text-gray-600">Commission Duration</p>
                                <p class="text-sm text-gray-500 mt-2">From claim creation date</p>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-lg">
                                <div class="text-3xl font-bold text-red-600 mb-2">
                                    @if($gs->influencer_max_claims)
                                        {{ $gs->influencer_max_claims }} Claims
                                    @else
                                        Unlimited
                                    @endif
                                </div>
                                <p class="text-gray-600">Max Claims Limit</p>
                                <p class="text-sm text-gray-500 mt-2">Per referred customer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission History -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Commission History</h2>
                        <p class="text-gray-600 mt-1">Track all commissions earned from your referrals</p>
                    </div>
                    <div class="overflow-x-auto">
                        @if($commissions->count() > 0)
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Info</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Commission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Commission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commissions as $commission)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $commission->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $commission->customer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $commission->customer->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $commission->claim->claim_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ \App\CentralLogics\Helpers::setCurrency($commission->claim->amount_requested) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                @if($commission->claim->amount_approved)
                                                    {{ \App\CentralLogics\Helpers::setCurrency($commission->claim->amount_approved) }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                {{ \App\CentralLogics\Helpers::setCurrency($commission->estimated_commission) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                                @if($commission->commission_amount)
                                                    {{ \App\CentralLogics\Helpers::setCurrency($commission->commission_amount) }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($commission->status === 'paid')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="las la-check-circle text-base mr-1"></i>
                                                        Paid
                                                    </span>
                                                @elseif($commission->status === 'approved')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <i class="las la-check text-base mr-1"></i>
                                                        Approved
                                                    </span>
                                                @elseif($commission->status === 'rejected')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="las la-times-circle text-base mr-1"></i>
                                                        Rejected
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="las la-clock text-base mr-1"></i>
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="px-6 py-4 bg-gray-50">
                                {{ $commissions->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="las la-money-bill-wave text-6xl text-gray-400"></i>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No Commissions Yet</h3>
                                <p class="mt-2 text-gray-500">Start sharing your affiliate link to earn commissions!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyAffiliateLink() {
    const affiliateLink = document.getElementById('affiliateLink');
    const copyBtn = document.getElementById('copyBtn');
    
    // Fallback for older browsers
    if (!navigator.clipboard) {
        // Fallback method
        affiliateLink.select();
        affiliateLink.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(copyBtn);
            } else {
                alert('Failed to copy. Please copy manually.');
            }
        } catch (err) {
            console.error('Fallback: Could not copy text: ', err);
            alert('Failed to copy. Please copy manually.');
        }
        return;
    }
    
    // Modern clipboard API
    navigator.clipboard.writeText(affiliateLink.value)
        .then(function() {
            showCopySuccess(copyBtn);
        })
        .catch(function(err) {
            console.error('Could not copy text: ', err);
            // Try fallback method
            affiliateLink.select();
            try {
                document.execCommand('copy');
                showCopySuccess(copyBtn);
            } catch (e) {
                alert('Failed to copy. Please copy manually.');
            }
        });
}

function showCopySuccess(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="las la-check text-xl"></i><span>Copied!</span>';
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-green-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }, 2000);
}
</script>
@endsection