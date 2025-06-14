@extends('front.layouts.app')
@section('meta_title') Subscription History @endsection

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
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800">Subscription History</h1>
                        <p class="text-gray-600 mt-1">Track all your subscription transactions and status</p>
                    </div>

                    @include('includes.alerts')

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 border-b border-gray-100">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Active Subscription</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                                        @php
                                            $activeSubscription = $transactions->where('status', 'active')->first();
                                        @endphp
                                        {{ $activeSubscription ? $activeSubscription->plan->name : 'None' }}
                                    </h3>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-5 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Total Spent</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                                        ${{ number_format($transactions->sum('price'), 2) }}
                                    </h3>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-5 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Total Transactions</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                                        {{ $transactions->count() }}
                                    </h3>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($transactions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Transaction
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Plan Details
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Payment
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Expiration
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $transaction)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if($transaction->transaction_id)
                                                            <span class="text-xs font-mono bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                                {{ substr($transaction->transaction_id, 0, 8) }}...
                                                            </span>
                                                            <span class="block text-xs text-gray-500 mt-1">
                                                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                                            </span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-accent rounded-full flex items-center justify-center text-white">
                                                            {{ strtoupper(substr($transaction->plan->name ?? 'UP', 0, 2)) }}
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $transaction->plan->name ?? 'Unknown Plan' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $transaction->stripe_price ? 'Stripe Subscription' : 'Manual Payment' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        ${{ number_format($transaction->price, 2) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        via {{ ucfirst($transaction->payment_method ?? 'N/A') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                            'active' => 'bg-green-100 text-green-800 border-green-200',
                                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                            'expired' => 'bg-gray-100 text-gray-800 border-gray-200'
                                                        ];
                                                        $statusColor = $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                    @endphp
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusColor }}">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($transaction->canceled_at)
                                                        <span class="text-red-500 font-medium">
                                                            Cancelled on {{ $transaction->canceled_at->format('M d, Y') }}
                                                        </span>
                                                    @elseif($transaction->expires_at)
                                                        @if($transaction->expires_at->isPast())
                                                            <span class="text-red-500 font-medium">
                                                                Expired on {{ $transaction->expires_at->format('M d, Y') }}
                                                            </span>
                                                        @else
                                                            <span class="font-medium">
                                                                {{ $transaction->expires_at->format('M d, Y') }}
                                                            </span>
                                                            <span class="block text-xs text-gray-500">
                                                                {{ $transaction->expires_at->diffForHumans() }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-500">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6">
                                {{ $transactions->links() }}
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions yet</h3>
                                <p class="mt-2 text-md text-gray-500 max-w-md mx-auto">You haven't subscribed to any plans yet. Get started by exploring our available plans.</p>
                                <div class="mt-6">
                                    <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center px-5 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-colors">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        View Available Plans
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
@endsection
