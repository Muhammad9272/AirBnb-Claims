@extends('front.layouts.app')
@section('meta_title', 'About Us - AirBnb Claims')
@section('meta_description', 'Learn about AirBnb Claims and our mission to help Airbnb hosts get fair compensation for property damages.')

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
            <h1 class="text-4xl font-bold mb-4">About AirBnb Claims</h1>
            <p class="text-xl mb-0">
                We're on a mission to help Airbnb hosts navigate the complex claims process and get fair compensation for property damages.
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
                    AirBnb Claims was founded in 2021 by a group of Airbnb superhosts who experienced firsthand the challenges of filing damage claims and recovering fair compensation. After numerous frustrating experiences with rejected claims, delayed responses, and inadequate payouts, we realized there was a need for expert guidance in this process.
                </p>
                
                <p>
                    Our founders' combined experience of over 15 years in property management and short-term rentals gave us unique insights into the intricacies of Airbnb's policies and resolution processes. We identified the common pitfalls that lead to denied claims and developed strategies to overcome these obstacles.
                </p>
                
                <p>
                    What began as informal advice to fellow hosts has since evolved into a comprehensive service dedicated to helping hosts navigate the complex landscape of Airbnb damages. Today, AirBnb Claims is trusted by hundreds of hosts worldwide to handle their claims with expertise and professionalism.
                </p>
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
                    "To level the playing field for Airbnb hosts by providing expertise, advocacy, and support throughout the claims process, ensuring they receive fair compensation for legitimate damages."
                </blockquote>
                
                <p class="text-gray-600 text-center">
                    We believe that hosts shouldn't have to absorb the costs of guest damages due to complicated claim processes or lack of experience in documentation and negotiation.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-accent mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Fairness</h3>
                    <p class="text-gray-600">
                        We're committed to helping hosts receive the compensation they rightfully deserve for property damages.
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
                        Our streamlined process saves hosts time and stress while increasing the likelihood of claim approval.
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-accent mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Expertise</h3>
                    <p class="text-gray-600">
                        We bring specialized knowledge and experience in Airbnb's policies and optimal claim presentation.
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
            <p class="text-gray-600 text-center mb-12">Meet the experts behind AirBnb Claims</p>

            @php
                $teamMembers = [
                    [
                        'name'        => 'Sarah Johnson',
                        'role'        => 'Co-Founder & CEO',
                        'description' => 'Former Airbnb Superhost with over 8 years of property management experience.',
                        'image_url'   => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                    ],
                    [
                        'name'        => 'Michael Chen',
                        'role'        => 'Co-Founder & Operations',
                        'description' => 'Previously managed a portfolio of 15+ Airbnb properties across multiple cities.',
                        'image_url'   => 'https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=400&q=80',
                    ],
                    [
                        'name'        => 'Emma Roberts',
                        'role'        => 'Claims Director',
                        'description' => 'Former customer resolution specialist with 5+ years in the hospitality industry.',
                        'image_url'   => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=400&q=80',
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
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Why Choose AirBnb Claims</h2>
            
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
                            Our strategically documented and presented claims achieve an 85%+ approval rate, significantly higher than the 40% industry average for self-filed claims.
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
                            Our team has intimate knowledge of Airbnb's policies, documentation requirements, and the specific language that resonates with their support team and resolution center.
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Transparent Pricing Model</h3>
                        <p class="text-gray-600">
                            Our success-based pricing ensures our interests are aligned with yours – we only succeed when you receive your compensation.
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
                Join hundreds of satisfied hosts who've successfully recovered compensation for property damages with our help.
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
