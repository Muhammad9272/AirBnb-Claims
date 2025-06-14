@extends('front.layouts.app')



@section('meta_title', "Page Not Found" )

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-gray-200 dark:text-gray-800">404</h1>
            
        </div>
        <h2 class="text-3xl font-semibold mb-4 text-gray-800 dark:text-white">Oops! Page not found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>
        <div class="flex gap-4">
            <a href="{{ route('front.index') }}" class="bg-primary-gradient text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300">
                Back to Homepage
            </a>
            <a href="javascript:history.back()" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection
