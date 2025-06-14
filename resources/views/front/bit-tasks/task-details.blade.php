@extends('front.layouts.app')

@section('meta_title', $task->title )
@section('meta_description', "View all submissions for the $task->title bit task" )



@section('content')
<div class="relative bg-gradient-to-r from-purple-200 to-indigo-200 dark:from-purple-900/50 dark:to-indigo-900/50 backdrop-blur-xl py-16">
    <div class="product-particles absolute inset-0"></div>
    <div class="container mx-auto px-4 relative z-10">
        <h1 class="text-3xl font-bold mb-3 bg-gradient-to-r from-purple-400 to-purple-600 text-transparent bg-clip-text">
            Task Details
        </h1>
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('front.index') }}" class="hover:text-purple-400">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('front.bit.ledger') }}" class="hover:text-purple-400">Bit Ledger</a>
            <span class="mx-2">/</span>
            <span>Task Details</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Task Details Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 mb-8">
        <div class="bg-gray-50 dark:bg-gray-700 p-6 border-b border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Created {{ $task->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 text-sm font-semibold rounded-full">
                        {{ $task->reward }} BIT Reward
                    </span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Task Description</h3>
            <div class="prose dark:prose-invert max-w-none mb-6">
                {!! $task->description !!}
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-6">
                <h3 class="text-md font-semibold mb-2 text-gray-900 dark:text-white">Submission Stats</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Submissions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->total_submissions }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Approved</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $task->approved_count }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Processing</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $task->processing_count }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Rejected</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $task->rejected_count }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                <a href="{{ route('front.bit.ledger') }}" class="inline-flex items-center text-gray-700 dark:text-gray-300 hover:text-purple-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Task List
                </a>
                
                <a href="{{ route('user.bit-tasks.show', $task->slug) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Apply for This Task
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Submissions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Task Submissions</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                Anonymous list of all submissions for this task
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Submitted By</th>
                        <th scope="col" class="px-6 py-3">Submitted On</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                #{{ $submission->id }}
                            </td>
                            <td class="px-6 py-4">
                                <!-- Anonymize the user info -->
                                User #{{ substr(md5($submission->user_id), 0, 8) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $submission->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->status == 'processing')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Processing
                                    </span>
                                @elseif($submission->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Approved
                                    </span>
                                @elseif($submission->status == 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-base">No submissions yet</p>
                                    <p class="text-sm mt-1">Be the first to complete this task!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3">
            {{ $submissions->withQueryString()->links() }}
        </div>
    </div>
    
    <div class="mt-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Why Submit for This Task?</h3>
        <ul class="space-y-2 text-gray-700 dark:text-gray-300">
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Earn {{ $task->reward }} BIT for successful completion</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Build your reputation and unlock higher-value tasks</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Contribute to the platform's growth and community</span>
            </li>
        </ul>
        
        <div class="mt-6 text-center">
            <a href="{{ route('user.bit-tasks.show', $task->slug) }}" class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                Apply for This Task
            </a>
        </div>
    </div>
</div>
@endsection
