@extends('front.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/front/pages/co-detail.min.css') }}" />
@endsection

@section('meta')
    <title>Server Error - {{ $gs->name }}</title>
    <meta name="description" content="We're experiencing some technical difficulties. Please try again later.">
@endsection

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-gray-200 dark:text-gray-800">500</h1>
        </div>
        <h2 class="text-3xl font-semibold mb-4 text-gray-800 dark:text-white">Internal Server Error</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md">
            We're experiencing some technical difficulties right now. Please try again later or contact our support team if the problem persists.
        </p>
        <div class="flex gap-4">
            <a href="{{ route('front.index') }}" class="bg-primary-gradient text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300">
                Back to Homepage
            </a>
            <a href="mailto:support@{{ $gs->site_email }}" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
