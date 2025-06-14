@extends('front.layouts.app')

@section('meta_title', "Bit Tasks Public Ledger" )
@section('meta_description', "View all bit tasks and their submission status in our public ledger" )



@section('content')
<div class="relative bg-gradient-to-r from-purple-200 to-indigo-200 dark:from-purple-900/50 dark:to-indigo-900/50 backdrop-blur-xl py-16">
    <div class="product-particles absolute inset-0"></div>
    <div class="container mx-auto px-4 relative z-10">
        <h1 class="text-4xl font-bold mb-3 bg-gradient-to-r from-purple-400 to-purple-600 text-transparent bg-clip-text">
            Public Bit Ledger
        </h1>
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('front.index') }}" class="hover:text-purple-400">Home</a>
            <span class="mx-2">/</span>
            <span>Bit Ledger</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Task Submission Ledger</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                        A public record of all tasks and their submissions
                    </p>
                </div>
                
                <!-- Filters -->
                <div class="w-full md:w-auto">
                    <form action="{{ route('front.bit.ledger') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <!-- Status Filter -->
                        <div class="w-full sm:w-auto">
                            <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">All Status</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                            Filter
                        </button>
                        
                        @if(request()->has('status'))
                            <a href="{{ route('front.bit.ledger') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white focus:outline-none dark:focus:ring-gray-700">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Container with Horizontal Scrolling for Mobile -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Task</th>
                        <th scope="col" class="px-6 py-3">Reward</th>
                        <th scope="col" class="px-6 py-3">Last Activity</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Submissions</th>
                        <th scope="col" class="px-6 py-3 text-center">Approved</th>
                        <th scope="col" class="px-6 py-3 text-center">Processing</th>
                        <th scope="col" class="px-6 py-3 text-center">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bitTasks as $task)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $task->title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $task->reward }} BIT
                            </td>
                            <td class="px-6 py-4">
                                {{ $task->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $task->total_submissions }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs {{ $task->approved_count > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $task->approved_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs {{ $task->processing_count > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $task->processing_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('front.bit.ledger.show', $task->slug) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                    <span class="sr-only">View details for {{ $task->title }}</span>
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-base">No tasks found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3">
            {{ $bitTasks->withQueryString()->links() }}
        </div>
    </div>
    
    <!-- Additional Info Card -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">About the Bit Task Ledger</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
            This public ledger provides transparency for all bit tasks and their submissions in our system. Before applying for a task, 
            you can check how many others have already submitted work for it and the status of those submissions.
        </p>
        <p class="text-gray-600 dark:text-gray-400">
            Tasks with multiple approved submissions may still accept new submissions, but your chances of approval might be lower.
            For the best results, look for tasks with few or no approved submissions.
        </p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="px-2 mr-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                        Processing
                    </span>
                    <span class="text-gray-700 dark:text-gray-300">Submission is under review</span>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="px-2 mr-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        Approved
                    </span>
                    <span class="text-gray-700 dark:text-gray-300">Submission accepted & Rewarded</span>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="px-2 mr-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        Rejected
                    </span>
                    <span class="text-gray-700 dark:text-gray-300">Submission did not meet requirements</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ route('user.bit-tasks.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Browse Available Tasks
        </a>
    </div>
</div>
@endsection
