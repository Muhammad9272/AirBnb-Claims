@extends('front.layouts.app')

@section('meta_title', $service->title )
@section('meta_description', $service->summary ?? Str::limit(strip_tags($service->content), 160)  )


@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 w-full bg-cover bg-center" 
             style="background-image: url('{{ $service->main_image ? Helpers::image($service->main_image, 'services/') : asset('assets/front/images/homepagebg.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-60 flex flex-col justify-center items-center text-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold tracking-wide px-4">{{ $service->title }}</h1>
            <div class="container mx-auto px-4 md:px-8 py-4">
                <nav class="text-sm text-gray-300">
                    <a href="/" class="hover:text-purple-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('front.services.index') }}" class="hover:text-purple-400">Services</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-100">{{ $service->title }}</span>
                </nav>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
       
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-2/3">
                <!-- Service Gallery Slider -->
                @if($service->gallery && count(json_decode($service->gallery)) > 0)
                    <div class="mb-8 relative">
                        <div class="swiper serviceGallery">
                            <div class="swiper-wrapper">
                                @foreach(json_decode($service->gallery) as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ Helpers::image($image, 'services/') }}" 
                                             alt="{{ $service->title }}"
                                             class="w-full rounded-lg h-[400px] object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add swiper navigation and pagination -->
                            <div class="swiper-button-next !text-white"></div>
                            <div class="swiper-button-prev !text-white"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                @endif

                <!-- Service Content -->
                <div class="prose prose-lg max-w-none dark:prose-invert mb-8">
                    {!! $service->content !!}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3 space-y-8">
                <!-- Service Packages -->
                @if($service->items)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-4">
                            Service Packages
                        </h3>
                        <form id="quoteForm" action="{{ route('front.services.quote') }}" method="POST" class="space-y-6">
                            @csrf
                            @include('includes.alerts')
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <input type="hidden" name="service_title" value="{{ $service->title }}">
                            
                            <div class="space-y-4">
                                @foreach(json_decode($service->items, true) as $key => $package)
                                    <label class="relative block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-purple-500 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <input type="radio" name="selected_package" value="{{ $key }}" required
                                                       class="w-5 h-5 text-purple-600 border-gray-300 focus:ring-purple-500">
                                                <div class="ml-4">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $package['label'] }}</h4>
                                                    <span class="text-purple-600 dark:text-purple-400 font-bold text-lg">
                                                        {{ Helpers::setCurrency($package['price']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <div class="space-y-6 mt-8">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name
                                    </label>
                                    <input type="text" id="name" name="name" required 
                                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                  focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" id="email" name="email" required 
                                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                  focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" id="phone" name="phone" required 
                                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                  focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Additional Details
                                    </label>
                                    <textarea id="message" name="message" rows="4" 
                                              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                                                     bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                     focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 
                                               rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                    <span>Request Quote</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

   
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    @include('components.why-choose-us')
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if gallery exists
        if (document.querySelector('.serviceGallery')) {
            const swiper = new Swiper('.serviceGallery', {
                observer: true,
                observeParents: true,
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    type: 'bullets'
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                on: {
                    init: function() {
                        console.log('Swiper initialized successfully');
                    }
                }
            });
        }
        
        // Show confirmation after form submission
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            // Form validation already handled by HTML5 required attributes
            // Additional JS validation can be added here if needed
        });
    });
</script>

<style>
    .swiper {
        width: 100%;
        height: 400px;
        border-radius: 0.75rem;
    }
    
    .swiper-slide {
        position: relative;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        background-color: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
        color: white;
    }
    
    .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: white;
        opacity: 0.5;
    }
    
    .swiper-pagination-bullet-active {
        opacity: 1;
        background: white;
    }
</style>
@endsection


