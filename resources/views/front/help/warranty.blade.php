@extends('front.help.layout')

@section('meta_title', 'Product Warranty Terms')
@section('meta_description', 'Learn about our comprehensive product warranty coverage and terms.')

@section('help-content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
            <!-- Page Header -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Product Warranty Terms</h1>
                <p class="text-gray-600 dark:text-gray-400">Our commitment to product quality and customer satisfaction</p>
            </div>

            <!-- Key Points Summary -->
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-4">Key Coverage Points</h2>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">30-day satisfaction guarantee</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">1-year warranty against manufacturing defects</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Free repairs for covered issues</span>
                    </li>
                </ul>
            </div>

            <!-- Video Guide -->
            <div class="aspect-w-16 aspect-h-9 mb-8">
                <iframe class="rounded-lg w-full h-[400px]" 
                        src="https://www.youtube.com/embed/YOUR_VIDEO_ID" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>

            <!-- Warranty Terms -->
            <div class="prose prose-purple max-w-none dark:prose-invert">
                <h2>Warranty Coverage</h2>
                <p>Our standard warranty covers all manufacturing defects and malfunctions under normal use conditions for a period of one year from the date of purchase.</p>

                <h3>What's Covered</h3>
                <ul>
                    <li>Manufacturing defects affecting product functionality</li>
                    <li>Material defects under normal use conditions</li>
                    <li>Electronic component failures (where applicable)</li>
                    <li>Structural integrity issues</li>
                </ul>

                <h3>What's Not Covered</h3>
                <ul>
                    <li>Damage from misuse or accidents</li>
                    <li>Normal wear and tear</li>
                    <li>Unauthorized modifications</li>
                    <li>Damage from improper maintenance</li>
                </ul>

                <h2>Making a Warranty Claim</h2>
                <p>To make a warranty claim, please follow these steps:</p>
                <ol>
                    <li>Contact our customer service team</li>
                    <li>Provide your order number and product details</li>
                    <li>Describe the issue in detail</li>
                    <li>Include photos or video of the problem if possible</li>
                </ol>

                <h2>Return Policy</h2>
                <p>We offer a 30-day satisfaction guarantee. If you're not completely satisfied with your purchase, you can return it within 30 days for a full refund.</p>

                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6 my-8">
                    <h3 class="text-lg font-semibold mb-4">Contact Support</h3>
                    <p>For warranty claims or questions:</p>
                    <ul>
                        <li>Email: support@example.com</li>
                        <li>Phone: 1-800-WARRANTY</li>
                        <li>Hours: Monday-Friday, 9am-5pm EST</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
