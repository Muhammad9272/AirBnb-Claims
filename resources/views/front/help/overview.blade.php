@extends('front.help.layout')
@section('meta_title', 'Help Center Overview')

@section('help_content')
@php
    // Define the cards for the Help Center overview
    $helpCards = [
        [
            'bgColor'       => 'blue',
            'heading'       => 'How It Works',
            'description'   => 'Our service helps Airbnb hosts get fair compensation for property damages. We handle the entire claim process from filing to resolution, maximizing your chances of approval.',
            'route'         => route('front.help.guides'),
            'routeLabel'    => 'Learn more',
            'iconSvg'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>',
        ],
        [
            'bgColor'       => 'green',
            'heading'       => 'Benefits of Our Service',
            'description'   => 'Our expertise in handling Airbnb claims increases approval rates and compensation amounts. Let us manage the process while you focus on your property.',
            'route'         => route('user.subscription.plans'),
            'routeLabel'    => 'View our plans',
            'iconSvg'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>',
        ],
    ];
@endphp

<h1 class="text-3xl font-bold text-gray-900 mb-6">AirBnb Claims Help Center</h1>

<p class="text-lg text-gray-700 mb-6">
    Welcome to our Help Center. We’ve created this resource to help you navigate the process of filing and managing AirBnb damage claims.
    Whether you’re a new user or returning host, you’ll find valuable information to maximize your claim success.
</p>

{{-- Loop over the two help cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @foreach($helpCards as $card)
        <div class="bg-{{ $card['bgColor'] }}-50 rounded-xl p-6 border border-{{ $card['bgColor'] }}-100">
            <div class="flex items-start">
                <div class="bg-{{ $card['bgColor'] }}-100 rounded-full p-3 mr-4">
                    {!! $card['iconSvg'] !!}
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $card['heading'] }}</h2>
                    <p class="text-gray-700">
                        {{ $card['description'] }}
                    </p>
                    <a href="{{ $card['route'] }}" class="inline-flex items-center text-{{ $card['bgColor'] }}-600 hover:text-{{ $card['bgColor'] }}-800 mt-3">
                        {{ $card['routeLabel'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Getting Started Steps (no change needed here) --}}
<div class="mb-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Getting Started</h2>
    <ol class="space-y-6">
        <li class="flex">
            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-accent text-white font-bold mr-4">1</div>
            <div>
                <h3 class="font-semibold text-lg text-gray-800">Create an account</h3>
                <p class="text-gray-700 mt-1">
                    Sign up on our platform and verify your email address. Browse our subscription options and choose a plan that fits your needs.
                </p>
            </div>
        </li>
        <li class="flex">
            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-accent text-white font-bold mr-4">2</div>
            <div>
                <h3 class="font-semibold text-lg text-gray-800">Submit your claim</h3>
                <p class="text-gray-700 mt-1">
                    Provide details about your Airbnb reservation and the damages incurred. Include the date of the incident, description of damages, and the amount you’re claiming.
                </p>
            </div>
        </li>
        <li class="flex">
            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-accent text-white font-bold mr-4">3</div>
            <div>
                <h3 class="font-semibold text-lg text-gray-800">Upload evidence</h3>
                <p class="text-gray-700 mt-1">
                    Upload photos of the damage, communication with guests, quotes for repairs, receipts, and any other supporting documentation.
                </p>
            </div>
        </li>
        <li class="flex">
            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-accent text-white font-bold mr-4">4</div>
            <div>
                <h3 class="font-semibold text-lg text-gray-800">We handle the rest</h3>
                <p class="text-gray-700 mt-1">
                    Our team will review your claim and evidence, then submit a professionally prepared claim to Airbnb. We’ll manage all communication and follow up until a resolution is reached.
                </p>
            </div>
        </li>
    </ol>
</div>

{{-- “Need Personal Assistance?” Banner (loop not needed) --}}
<div class="bg-gray-50 rounded-xl p-6 border border-gray-200 mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Need Personal Assistance?</h2>
    <p class="text-gray-700 mb-4">
        Our support team is available to help you with any questions or issues you may have regarding your Airbnb claims.
    </p>
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            Contact Support
        </a>
        <a href="{{ route('front.help.faqs') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Browse FAQ
        </a>
    </div>
</div>
@endsection
