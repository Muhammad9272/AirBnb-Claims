@extends('front.layouts.app')

@section('meta_title', 'Create Support Ticket')

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
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Create Support Ticket</h1>
                        <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center text-accent hover:text-accent-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Tickets
                        </a>
                    </div>

                    @include('includes.alerts')

                    <!-- Ticket Form -->
                    <div class="p-6">
                        <form action="{{ route('user.tickets.store') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Basic Ticket Information Section -->
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 mb-4">Ticket Information</h2>
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Subject -->
                                        <div>
                                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                                            <input type="text" 
                                                   id="subject"
                                                   name="subject"
                                                   placeholder="Brief summary of your issue"
                                                   value="{{ old('subject') }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                   required>
                                            @error('subject')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                                            <textarea id="description" 
                                                      name="description" 
                                                      rows="6"
                                                      placeholder="Please provide detailed information about your issue, including any error messages, steps to reproduce the problem, and any other relevant details..."
                                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                      required>{{ old('description') }}</textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Support Guidelines -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Support Guidelines
                                    </h3>
                                    <ul class="space-y-1 text-sm text-blue-700">
                                        <li>• Be specific about your issue to help us resolve it faster</li>
                                        <li>• Include any relevant order numbers or account details</li>
                                        <li>• Our support team typically responds within 24 hours</li>
                                        <li>• You'll receive email notifications for updates to your ticket</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-8 flex justify-end space-x-4">
                                <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Submit Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection