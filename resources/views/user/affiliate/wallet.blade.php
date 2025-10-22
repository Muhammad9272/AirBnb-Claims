@extends('front.layouts.app')
@section('meta_title') Wallet Transactions @endsection

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
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6">
                        <h1 class="text-3xl font-bold">Wallet Transactions</h1>
                        <p class="text-green-100 mt-2">View your wallet earnings and spending history</p>
                    </div>
                </div>

                @include('includes.alerts')

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Current Balance</p>
                                <p class="text-2xl font-bold text-gray-800">{{ Helpers::setCurrency(auth()->user()->wallet_balance) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Earned</p>
                                <p class="text-2xl font-bold text-green-600">{{ Helpers::setCurrency($totalEarned) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Spent</p>
                                <p class="text-2xl font-bold text-red-600">{{ Helpers::setCurrency($totalSpent) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions History -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Transaction History</h2>
                        <p class="text-gray-600 mt-1">All your wallet transactions in one place</p>
                    </div>
                    <div class="overflow-x-auto">
                        @if($transactions->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $transaction->created_at ? $transaction->created_at->format('h:i A') : '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($transaction->transaction_type === 'referral_earned')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Earned
                                                    </span>
                                                @elseif($transaction->transaction_type === 'subscription_used')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Used
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucwords(str_replace('_', ' ', $transaction->transaction_type)) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $transaction->description }}</div>
                                                @if($transaction->relatedUser)
                                                    <div class="text-xs text-gray-500">User: {{ $transaction->relatedUser->name }}</div>
                                                @endif
                                                @if($transaction->relatedSubscription)
                                                    <div class="text-xs text-gray-500">Subscription #{{ $transaction->relatedSubscription->id }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($transaction->amount >= 0)
                                                    <div class="text-sm font-bold text-green-600">
                                                        +{{ Helpers::setCurrency($transaction->amount) }}
                                                    </div>
                                                @else
                                                    <div class="text-sm font-bold text-red-600">
                                                        {{ Helpers::setCurrency($transaction->amount) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 font-medium">{{ Helpers::setCurrency($transaction->balance_after) }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $transactions->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Your wallet transactions will appear here once you start earning or using credits</p>
                                <div class="mt-6">
                                    <a href="{{ route('user.affiliate.index') }}" class="inline-flex items-center px-5 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121L17 20zm-2.5-3.5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5zm-.5-5a4 4 0 01-7.5-1.5 4 4 0 117.5 1.5z"></path>
                                        </svg>
                                        Start Referring Friends
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">About Wallet Credits</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Earn credits when your referred friends purchase subscriptions</li>
                                    <li>Use wallet credits to reduce your subscription costs</li>
                                    <li>Credits are automatically applied during checkout</li>
                                    <li>Track all your earnings and spending in one place</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
