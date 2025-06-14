@extends('front.layouts.app')
@section('meta_title', "Our Services")
@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 w-full bg-cover bg-center" style="background-image: url('{{asset('assets/front/images/homepagebg.jpg')}}');">
        <div class="absolute inset-0 bg-white dark:bg-black bg-opacity-50 dark:bg-opacity-50 flex flex-col justify-center items-center text-center">
            <h1 class="text-black dark:text-white text-3xl md:text-5xl font-bold tracking-wide">Our Services</h1>
            <div class="container mx-auto px-4 md:px-8 py-4">
                <nav class="text-sm text-gray-800 dark:text-gray-200">
                    <a href="/" class="hover:text-purple-600 font-bold">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 dark:text-gray-200 font-semibold">Services</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Services Filter Section -->
    <div class="bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Category Filter -->
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('front.services.index') }}" 
                       class="group relative px-6 py-2 rounded-full transition-all duration-300 
                       {{ !request('category') ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-purple-100 dark:hover:bg-gray-700' }}">
                        <span class="relative z-10">All Services</span>
                        <span class="absolute inset-0 rounded-full group-hover:bg-purple-100 dark:group-hover:bg-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('front.services.index', ['category' => $category->slug]) }}" 
                           class="group relative px-6 py-2 rounded-full transition-all duration-300 
                           {{ request('category') == $category->slug ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-purple-100 dark:hover:bg-gray-700' }}">
                            <span class="relative z-10">{{ $category->name }}</span>
                            <span class="absolute inset-0 rounded-full group-hover:bg-purple-100 dark:group-hover:bg-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        </a>
                    @endforeach
                </div>

                <!-- Search Bar -->
                <div class="flex justify-center">
                    <form action="{{ route('front.services.index') }}" method="GET" class="w-full max-w-xl">
                        <!-- Retain all existing request parameters except 'search' -->
                        @foreach(request()->except('search') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-full 
                                   bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                                   focus:ring-2 focus:ring-purple-500 focus:border-transparent 
                                   transition-all duration-300 shadow-sm focus:shadow-md"
                                   placeholder="Search services..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 
                                    text-gray-400 dark:text-gray-500 
                                    hover:text-purple-600 dark:hover:text-purple-400 
                                    transition-colors duration-300">
                                <i class="fas fa-search text-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                   @include('front.services.includes.services-card')
                @empty
                    <div class="col-span-full text-center py-12">
                        <h3 class="text-xl text-gray-600 dark:text-gray-400">No services found</h3>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $services->links('front.partials.pagination') }}
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    @include('components.why-choose-us')
@endsection