@extends('front.layouts.app')
@section('meta_title', 'Contact Us - AirBnb Claims')
@section('meta_description', 'Get in touch with our team for questions about AirBnb claims, our services, or to request assistance with your case.')

@section('content')


<!-- Hero Section (with Unsplash background) -->
<div 
  class="relative py-16 bg-cover bg-center" 
  style="background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1950&q=80');"
>
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl mb-0">
                Have questions? We're here to help with all your Airbnb claims needs.
            </p>
        </div>
    </div>
</div>


<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-8 border border-gray-200 shadow-lg h-full">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-accent/10 rounded-full p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Email</h3>
                                    <p class="mt-1 text-gray-600">
                                        <a href="mailto:support@airbnbclaims.com" class="hover:text-accent">support@airbnbclaims.com</a>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">Our team typically responds within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-accent/10 rounded-full p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Phone</h3>
                                    <p class="mt-1 text-gray-600">
                                        <a href="tel:+18005551234" class="hover:text-accent">(800) 555-1234</a>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">Monday-Friday, 9AM-5PM EST</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-accent/10 rounded-full p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Office</h3>
                                    <p class="mt-1 text-gray-600">
                                        123 Business Avenue<br>
                                        Suite 456<br>
                                        New York, NY 10001
                                    </p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-6 mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Follow Us</h3>
                                <div class="flex space-x-4">
                                    <a href="#" class="text-gray-500 hover:text-accent">
                                        <span class="sr-only">Facebook</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-500 hover:text-accent">
                                        <span class="sr-only">Twitter</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-500 hover:text-accent">
                                        <span class="sr-only">LinkedIn</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl p-8 border border-gray-200 shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a message</h2>
                        
                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-green-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <form action="{{ route('front.contact.submit') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                        placeholder="Your full name"
                                        required
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                        placeholder="your@example.com"
                                        required
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    value="{{ old('subject') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                    placeholder="Reason for contacting us"
                                    required
                                >
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="6" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                    placeholder="Type your message here..."
                                    required
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input 
                                        id="privacy" 
                                        name="privacy" 
                                        type="checkbox" 
                                        class="w-4 h-4 rounded border-gray-300 text-accent focus:ring-accent focus:ring-offset-0" 
                                        required
                                    >
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="privacy" class="text-gray-600">
                                        I agree to the <a href="{{ route('front.help.privacy') }}" class="text-accent hover:underline">Privacy Policy</a> and consent to having my data processed.
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-base font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                    </svg>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Section -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">How quickly will I receive a response?</h3>
                        <p class="text-gray-600">
                            We aim to respond to all inquiries within 24 business hours. For urgent matters related to active claims, please use the support portal in your account dashboard for faster assistance.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Can I schedule a consultation call?</h3>
                        <p class="text-gray-600">
                            Yes! You can schedule a free 15-minute consultation with one of our claims specialists. Simply mention that you'd like to schedule a call in your message, and we'll provide available time slots.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Do you offer custom plans for property managers?</h3>
                        <p class="text-gray-600">
                            Absolutely! If you manage multiple Airbnb properties, we can create a custom plan tailored to your specific needs and volume. Contact our sales team for a personalized quote.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">How do I get technical support?</h3>
                        <p class="text-gray-600">
                            For technical issues with your account or the platform, please email <a href="mailto:support@airbnbclaims.com" class="text-accent hover:underline">support@airbnbclaims.com</a> with "Technical Support" in the subject line, or create a support ticket from your user dashboard.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-16">
                <div class="h-96 rounded-xl overflow-hidden shadow-lg">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48326.07446697973!2d-74.00601508408146!3d40.71277579184411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c249c5e6d8cdd5%3A0x30f9b3a4f4a8d9ff!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sus!4v1584455643623"
                        width="100%"
                        height="100%"
                        class="border-0"
                        allowfullscreen=""
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
