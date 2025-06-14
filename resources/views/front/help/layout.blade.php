@extends('front.layouts.app')
@section('meta_title') {{ $title ?? 'Help Center' }} - AirBnb Claims @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Help Center Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-accent to-accent-light p-8 text-center">
                <h1 class="text-3xl font-bold text-white mb-4">Help Center</h1>
                <p class="text-white/80 max-w-3xl mx-auto text-lg">
                    Find answers to common questions about AirBnb claims processing and how our service works.
                </p>
                
                <div class="mt-8">
                    <form action="{{ route('front.help.search') }}" method="GET" class="flex justify-center">
                        <div class="relative w-full max-w-xl">
                            <input
                                type="text"
                                name="query"
                                placeholder="Search for answers..."
                                class="w-full px-5 py-3 rounded-full border-0 focus:ring-2 focus:ring-accent text-gray-900 shadow-lg"
                                required
                            >
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-accent hover:bg-accent-dark text-white p-2 rounded-full transition duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area with Sidebar -->
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="md:w-1/4 order-2 md:order-1">
                @php
                    $helpMenu = [
                        ['label' => 'Overview',            'route' => 'front.help.overview'],
                        ['label' => 'Claim Guides',        'route' => 'front.help.guides'],
                        ['label' => 'Frequently Asked Questions', 'route' => 'front.help.faqs'],
                        ['label' => 'Terms of Service',    'route' => 'front.help.terms'],
                        ['label' => 'Privacy Policy',      'route' => 'front.help.privacy'],
                    ];
                @endphp
              <div class="sticky top-20">
                <div class="bg-white rounded-xl shadow-md overflow-hidden top-8">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Help Categories</h2>
                    </div>
                    <nav class="p-2">
                        <ul class="space-y-1">
                            @foreach($helpMenu as $item)
                                <li>
                                    <a href="{{ route($item['route']) }}"
                                       class="block px-4 py-2 rounded-lg
                                           {{ request()->routeIs($item['route']) 
                                               ? 'bg-accent text-white' 
                                               : 'text-gray-700 hover:bg-gray-100' }}">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                
                <!-- Quick Support Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Need Help?</h3>
                        <p class="text-gray-600 text-sm mb-4">Can’t find what you’re looking for? Our support team is here to help.</p>
                        <a href="{{ route('user.tickets.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150 w-full justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Create Support Ticket
                        </a>
                    </div>
                </div>
              </div>
            </div>
            
            <!-- Main Content -->
            <div class="md:w-3/4 order-1 md:order-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 sm:p-8">
                        @yield('help_content')
                    </div>
                </div>
                
                <!-- FAQ Section -->
                @yield('faq_section')
                
                <!-- Related Articles Section -->
                @yield('related_articles')
            </div>
        </div>
    </div>
</div>
@endsection
