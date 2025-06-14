@extends('front.help.layout')
@section('meta_title', 'Search Results')

@section('help_content')
<h1 class="text-3xl font-bold text-gray-900 mb-6">Search Results</h1>

<p class="text-lg text-gray-700 mb-8">
    Showing results for: <span class="font-semibold">{{ $query }}</span>
</p>

<!-- Search Results Content -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- This is a placeholder - actual search implementation would populate results here -->
    <div class="p-6">
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Search functionality coming soon</h3>
            <p class="mt-1 text-gray-500">We're working on implementing search for our help center.</p>
        </div>
        
        <div class="mt-6">
            <h3 class="font-medium text-gray-900 mb-3">Popular Help Topics</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('front.help.guides') }}#getting-started" class="text-accent hover:text-accent-dark flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Getting Started with AirBnb Claims
                    </a>
                </li>
                <li>
                    <a href="{{ route('front.help.faqs') }}" class="text-accent hover:text-accent-dark flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Frequently Asked Questions
                    </a>
                </li>
                <li>
                    <a href="{{ route('front.help.guides') }}#documentation" class="text-accent hover:text-accent-dark flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Documentation Guide
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="mt-8 text-center">
    <p class="text-gray-600 mb-4">Can't find what you're looking for?</p>
    <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-accent hover:bg-accent-dark">
        Contact Support
    </a>
</div>
@endsection
