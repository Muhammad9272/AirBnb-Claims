@extends('front.layouts.app')

@section('meta_title', 'Submission History')

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
                        <h1 class="text-2xl font-bold text-white">My Submissions</h1>
                        <p class="text-white/80 mt-1">Track the status of your task submissions</p>
                    </div>
                    <div class="text-center bg-white/20 rounded-lg p-3">
                        <p class="text-sm text-white/80">Your Balance</p>
                        <p class="text-xl font-bold text-white">{{ auth()->user()->bit_balance }} Bits</p>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-2">
                        <button type="button" onclick="filterSubmissions('all')"
                                class="tab-btn active border-b-2 border-purple-500 py-4 px-4 text-center text-sm font-medium text-purple-600 dark:text-purple-400"
                                id="all-tab">
                            All
                        </button>
                        <button type="button" onclick="filterSubmissions('pending')"
                                class="tab-btn border-b-2 border-transparent py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                                id="pending-tab">
                            Pending
                        </button>
                        <button type="button" onclick="filterSubmissions('approved')"
                                class="tab-btn border-b-2 border-transparent py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                                id="approved-tab">
                            Approved
                        </button>
                        <button type="button" onclick="filterSubmissions('rejected')"
                                class="tab-btn border-b-2 border-transparent py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                                id="rejected-tab">
                            Rejected
                        </button>
                    </nav>
                </div>

                @if($submissions->isEmpty())
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No Submissions Yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">You haven't submitted any tasks yet.</p>
                        <a href="{{ route('user.bit-tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                            Browse Available Tasks
                        </a>
                    </div>
                @else
                    <div class="overflow-hidden">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($submissions as $submission)
                                <li class="submission-item p-6" data-status="{{ $submission->status }}">
                                    <div class="flex flex-col md:flex-row justify-between">
                                        <div class="mb-4 md:mb-0">
                                            <div class="flex items-center">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $submission->task->title }}</h3>
                                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($submission->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                    @elseif($submission->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                    @endif">
                                                    {{ ucfirst($submission->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-4">
                                                <div>
                                                    <svg class="text-gray-400 w-4 h-4 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                    </svg>
                                                    Submitted: {{ $submission->created_at->format('M d, Y') }}
                                                </div>
                                                
                                                @if($submission->status === 'approved' && $submission->approved_at)
                                                    <div>
                                                        <svg class="text-green-500 w-4 h-4 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Approved: {{ $submission->approved_at->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            @if($submission->status === 'approved')
                                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">+{{ $submission->task->bit_value }} Bits</span>
                                            @endif
                                            
                                            <a href="{{ route('user.bit-tasks.show', $submission->task->slug) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View Task
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($submission->admin_notes && $submission->status !== 'pending')
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Admin Feedback:</h4>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $submission->admin_notes }}</p>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Pagination -->
                    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function filterSubmissions(status) {
        // Update active tab
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-purple-500', 'text-purple-600', 'dark:text-purple-400');
            btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        });
        
        const activeTab = document.getElementById(`${status}-tab`);
        activeTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        activeTab.classList.add('active', 'border-purple-500', 'text-purple-600', 'dark:text-purple-400');
        
        // Filter items
        const items = document.querySelectorAll('.submission-item');
        items.forEach(item => {
            if (status === 'all' || item.dataset.status === status) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }
</script>
@endpush
@endsection
