@extends('front.layouts.app')

@section('meta_title', 'Task Details')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4 space-y-8">
            <!-- Back Link -->
            <div>
                <a href="{{ route('user.bit-tasks.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Tasks
                </a>
            </div>

            <!-- Task Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $task->title }}</h1>
                        <div class="mt-2">
                            <span class="bg-white/20 text-white text-sm font-medium px-3 py-1 rounded-full">
                                {{ $task->bit_value }} Bits Reward
                            </span>
                        </div>
                    </div>
                    <div class="text-center bg-white/20 rounded-lg p-3">
                        <p class="text-sm text-white/80">Your Balance</p>
                        <p class="text-xl font-bold text-white">{{ auth()->user()->bit_balance }} Bits</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Task Details -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Task Description</h2>
                        <div class="prose prose-sm max-w-none dark:prose-invert mb-6">
                            {!! $task->description !!}
                        </div>

                        @if($task->required_proof)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Required Proof</h3>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-200">
                                            <p>{{ $task->required_proof }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submission Form - Only show if there's no approved submission and no pending submission -->
                        @if(!$hasApprovedSubmission)
                            @if(!$hasPendingSubmission)
                                @if(!$task->max_submissions || $recentSubmissionsCount < $task->max_submissions)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Submit Task</h3>
                                        
                                        <form action="{{ route('user.bit-tasks.submit', $task->slug) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            
                                            @if ($errors->any())
                                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">There were errors with your submission</h3>
                                                            <div class="mt-2 text-sm text-red-700 dark:text-red-200">
                                                                <ul class="list-disc pl-5 space-y-1">
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="mb-6">
                                                <label for="submission_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Submission</label>
                                                <textarea id="submission_content" name="submission_content" rows="6" required
                                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('submission_content') }}</textarea>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Explain how you completed this task and include any relevant details.</p>
                                            </div>
                                            
                                            @if($task->required_proof)
                                                <div class="mb-6">
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Proof</label>
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex-1">
                                                            <input type="file" name="proof[]" multiple 
                                                                accept="image/*,application/pdf" 
                                                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-md file:border-0
                                                                    file:text-sm file:font-medium
                                                                    file:bg-purple-50 file:text-purple-700
                                                                    hover:file:bg-purple-100
                                                                    dark:file:bg-gray-700 dark:file:text-purple-400
                                                                    dark:hover:file:bg-gray-600"
                                                            />
                                                        </div>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                            Max {{ config('fileformats.max_proof_images') }} files
                                                        </span>
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        Accepted formats: JPEG, PNG, PDF. Max size: 2MB per file.
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            <div class="flex justify-end">
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    Submit Task
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Monthly submission limit reached</h3>
                                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                                    <p>You have reached your maximum {{ $task->max_submissions }} submission(s) for this task this month. Your limit will reset 30 days after your first submission.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Pending Review</h3>
                                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-200">
                                                <p>You already have a submission for this task that is pending review. Please wait for the review to be completed before submitting again.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800 dark:text-green-300">Task Completed</h3>
                                        <div class="mt-2 text-sm text-green-700 dark:text-green-200">
                                            <p>You have already completed this task successfully and received your bits.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submission History Section -->
                        @if($userSubmissions->count() > 0)
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Your Submission History</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admin Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($userSubmissions as $submission)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $submission->created_at->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($submission->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                            @elseif($submission->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                            @endif">
                                                            {{ ucfirst($submission->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                        @if($submission->admin_notes)
                                                            {{ $submission->admin_notes }}
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">No notes</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Task Information</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reward</h3>
                                <p class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $task->bit_value }} Bits</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <p class="text-sm font-medium">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                        {{ $task->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            
                            @if($task->max_submissions)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Your Monthly Submission Limit</h3>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $recentSubmissionsCount }} / {{ $task->max_submissions }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $task->max_submissions - $recentSubmissionsCount }} remaining)</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Resets 30 days after submission</p>
                                </div>
                            @endif
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Submissions</h3>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $task->total_submissions }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">How It Works</h3>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li>Complete the task as described</li>
                                <li>Submit your work with any required proof</li>
                                <li>Wait for admin review (usually 24-48 hours)</li>
                                <li>Earn bits upon approval</li>
                                <li>Use bits for discounts during checkout</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
