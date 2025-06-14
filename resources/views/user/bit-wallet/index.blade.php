@extends('front.layouts.app')

@section('meta_title', 'Bit Wallet')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4 space-y-8">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Bit Wallet</h1>
                        <p class="text-white/80 mt-1">Manage your bits and view transaction history</p>
                    </div>
                    <div class="text-center bg-white/20 rounded-lg p-4">
                        <p class="text-sm text-white/80">Available Balance</p>
                        <p class="text-2xl font-bold text-white">{{ $user->bit_balance }} Bits</p>
                        <p class="text-xs text-white/60 mt-1">≈ {{ Helpers::setCurrency($user->bit_balance * $gs->bit_value) }} value</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <!-- Transaction History -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Transaction History</h2>
                        </div>
                        
                        @if($transactions->isEmpty())
                            <div class="p-8 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No Transactions Yet</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">You haven't earned or spent any bits yet.</p>
                                <a href="{{ route('user.bit-tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                    Start Earning Bits
                                </a>
                            </div>
                        @else
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transactions as $transaction)
                                    <div class="p-6 flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center">
                                                <div class="mr-4">
                                                    @if($transaction->amount > 0)
                                                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4 10a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $transaction->description }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium {{ $transaction->amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }} Bits
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                Balance: {{ $transaction->balance_after }} Bits
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="md:col-span-1">
                    <!-- Bit Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">About Bits</h2>
                        
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">What are Bits?</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    Bits are rewards you earn by completing tasks. Each bit is worth {{ Helpers::setCurrency($gs->bit_value) }} in discounts on purchases.
                                </p>
                            </div>
                            
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">How to Earn Bits</h3>
                                <ul class="mt-1 text-sm text-gray-600 dark:text-gray-300 space-y-1 list-disc list-inside">
                                    <li>Complete available tasks</li>
                                    <li>Get your submissions approved</li>
                                    <li>Special promotions</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">How to Use Bits</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    Apply your bits during checkout for discounts on your purchases. They'll be automatically applied to your total.
                                </p>
                            </div>
                            
                            <a href="{{ route('user.bit-tasks.index') }}" class="inline-flex items-center justify-center w-full mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Earn More Bits
                            </a>
                        </div>
                    </div>
                    
                    <!-- Stats Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Your Stats</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Earned:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $transactions->where('amount', '>', 0)->sum('amount') }} Bits
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Spent:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ abs($transactions->where('amount', '<', 0)->sum('amount')) }} Bits
                                </span>
                            </div>
                            
                            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Balance:</span>
                                <span class="font-bold text-purple-600 dark:text-purple-400">
                                    {{ $user->bit_balance }} Bits
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
