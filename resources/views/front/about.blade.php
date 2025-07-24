@extends('front.layouts.app')
@section('meta_title', 'About Us - '.$gs->name)
@section('meta_description', 'Learn about '.$gs->name.' and our mission to help Airbnb hosts get fair compensation for property damages with our proven system.')

@section('content')
<!-- Hero Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-4xl font-bold mb-4">About {{$gs->name}}</h1>
            <p class="text-xl mb-0">
                We handle the claims—so you can get back to hosting. Let us recover what you're owed while you focus on your guests.
            </p>
        </div>
    </div>
</div>

<!-- Our Story Section -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Our Story</h2>
            
            <div class="prose prose-lg mx-auto">
                <p>
                    {{$gs->name}} was born out of frustration—and opportunity. As active Airbnb hosts ourselves, we experienced firsthand how difficult and inconsistent the claims process can be. After getting denied for multiple claims we knew were valid, we started asking a simple question: How many other hosts are going through this too?
                </p>
                
                <p>
                    In 2022, after successfully recovering over $10,000 from a single damage claim that we almost gave up on, it hit us—this wasn't just a one-time win. Most hosts weren't filing claims at all, and those who did often gave up after poor communication or unfair denials. When we dug deeper, we discovered that over <b>$3 billion</b> in damages goes unclaimed by hosts every year simply because they don't know the process or don't have the time to deal with it.
                </p>
                
                <p>
                    We realized there was a huge gap—and we knew how to fill it. Fast forward to today, and {{$gs->name}} has helped hosts recover over <b>$85,000</b> in damages since launching the service in <b>January 2025</b>. We've managed <b>over 100 successful claims</b> so far, and we're just getting started.
                </p>

                <p>
                    With <b>6 years of combined hosting experience</b> and management of <b>10 active Airbnb units</b>, we've developed a system that works. We built this service not just to get our money back—but to take the stress off other hosts too. {{$gs->name}} exists so you don't have to chase payouts, argue with support, or waste hours fighting a system we've already mastered.
                </p>
            </div>

            <div class="mt-8 bg-gray-50 rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">What makes us different?</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-2 h-2 bg-accent rounded-full mt-2 mr-3"></span>
                        <span class="text-gray-700">A <b>95% approval rate</b></span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-2 h-2 bg-accent rounded-full mt-2 mr-3"></span>
                        <span class="text-gray-700">Fast turnaround times</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-2 h-2 bg-accent rounded-full mt-2 mr-3"></span>
                        <span class="text-gray-700"><b>Deep knowledge of Airbnb's resolution process and policies</b></span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-2 h-2 bg-accent rounded-full mt-2 mr-3"></span>
                        <span class="text-gray-700">Proven <b>documentation and automation systems</b> that give every claim the best shot at success</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Our Mission Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Our Mission</h2>
            
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-accent/10 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                
                <blockquote class="text-center italic text-gray-700 text-xl mb-6">
                    "We bring specialized knowledge and experience in short-term rental platform policies—especially Airbnb—and know how to present claims for maximum success."
                </blockquote>
                
                <p class="text-gray-600 text-center">
                    Let us handle the claims—so you can get back to hosting. We exist to take the stress off hosts by recovering what you're owed while you focus on what matters most.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-accent mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Expertise</h3>
                    <p class="text-gray-600">
                        We bring specialized knowledge and experience in short-term rental platform policies and optimal claim presentation.
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-accent mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Efficiency</h3>
                    <p class="text-gray-600">
                        Our proven systems and fast turnaround times save hosts time and stress while maximizing claim success.
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-accent mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Results</h3>
                    <p class="text-gray-600">
                        With a 95% approval rate and over $85,000 recovered, we deliver proven results that matter.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-2 text-center">Our Team</h2>
            <p class="text-gray-600 text-center mb-12">Meet the team behind {{$gs->name}}</p>



            @php
                $teamMembers = [
                    [
                        'name'        => 'Wilbert James',
                        'role'        => 'Co-Founder & CEO',
                        'description' => 'Airbnb Superhost with over 4 years of property management experience.',
                        'image_url'   => asset('assets/images/team/1.jpg'),
                    ],
                    [
                        'name'        => 'Jonathan James',
                        'role'        => 'Co-Founder & Operations',
                        'description' => 'Previously managed a portfolio of 10+ Airbnb properties across multiple cities.',
                        'image_url'   => asset('assets/images/team/2.jpg'),
                    ],
                    [
                        'name'        => 'Zichen Chu',
                        'role'        => 'Claims Director',
                        'description' => 'Former customer resolution specialist with 5+ years in the hospitality industry.',
                        'image_url'   => asset('assets/images/team/3.jpg'),
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($teamMembers as $member)
                    <div class="text-center">
                        <div class="relative mb-4 mx-auto w-32 h-32 rounded-full overflow-hidden bg-gray-200 shadow-lg">
                            <img src="{{ $member['image_url'] }}"
                                 alt="{{ $member['name'] }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $member['name'] }}</h3>
                        <p class="text-accent mb-2">{{ $member['role'] }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ $member['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Why Choose {{$gs->name}}</h2>
            
            <div class="space-y-8">
                <!-- Item 1 -->
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-accent p-3 w-12 h-12 flex items-center justify-center text-white font-bold text-xl">
                            1
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Proven Success Rate</h3>
                        <p class="text-gray-600">
                            Our strategically documented and presented claims achieve a 95%+ approval rate, significantly higher than the 40% industry average for self-filed claims.
                        </p>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-accent p-3 w-12 h-12 flex items-center justify-center text-white font-bold text-xl">
                            2
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Specialized Expertise</h3>
                        <p class="text-gray-600">
                            Our team has intimate knowledge of OTA's coverage policies, documentation requirements, and the specific language that resonates with their support team and resolution center.
                        </p>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-accent p-3 w-12 h-12 flex items-center justify-center text-white font-bold text-xl">
                            3
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Time-Saving Convenience</h3>
                        <p class="text-gray-600">
                            We handle the entire claim process from documentation to resolution, freeing you to focus on your guests and property management while we recover your funds.
                        </p>
                    </div>
                </div>
                
                <!-- Item 4 -->
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-accent p-3 w-12 h-12 flex items-center justify-center text-white font-bold text-xl">
                            4
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Fast Turnaround Times</h3>
                        <p class="text-gray-600">
                            Our proven documentation and automation systems give every claim the best shot at success with quick processing and resolution.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h2 class="text-3xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl mb-8">
                Join the hosts who've successfully recovered over $85,000 in damages with our proven system and expertise.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.register') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-white text-accent font-medium rounded-lg hover:bg-gray-100 transition duration-150">
                    Create an Account
                </a>
                <a href="{{ route('front.contact') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white/10 transition duration-150">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>

@endsection