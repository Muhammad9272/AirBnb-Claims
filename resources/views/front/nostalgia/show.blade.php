@extends('front.layouts.app')

@section('meta_title', $item->name )
@section('meta_description', Str::limit(strip_tags($item->description), 160)  )

@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 w-full bg-cover bg-center" 
             style="background-image: url('{{ $item->gallery ? Helpers::image(json_decode($item->gallery)[0], 'nostalgia/items/') : asset('assets/front/images/homepagebg.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-60 flex flex-col justify-center items-center text-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold tracking-wide px-4">{{ $item->name }}</h1>
            <div class="container mx-auto px-4 md:px-8 py-4">
                <nav class="text-sm text-gray-300">
                    <a href="/" class="hover:text-purple-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('front.nostalgia.index') }}" class="hover:text-purple-400">Nostalgia</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-100">{{ $item->name }}</span>
                </nav>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-2/3">
                <!-- Gallery Slider -->
                @if($item->gallery && count(json_decode($item->gallery)) > 0)
                    <div class="mb-8 relative">
                        <div class="swiper nostalgiaGallery">
                            <div class="swiper-wrapper">
                                @foreach(json_decode($item->gallery) as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ Helpers::image($image, 'nostalgia/items/') }}" 
                                             alt="{{ $item->name }}"
                                             class="w-full rounded-lg h-[400px] object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next !text-white"></div>
                            <div class="swiper-button-prev !text-white"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                @endif

                
                <!-- Tags -->
                {{-- @if(!empty($item->tags))
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach(json_decode($item->tags, true) as $tag)
                            <x-tag>{{ $tag }}</x-tag>
                        @endforeach
                    </div>
                @endif --}}

                <!-- External Resources -->
                @if(!empty($item->formatted_resources))
                  {{--   <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-xl font-semibold mb-6 dark:text-white">Resources</h3>
                        <div class="space-y-6">
                            @foreach($item->formatted_resources as $key => $links)
                                <div class="resource-section">
                                    <h4 class="flex items-center text-gray-900 dark:text-gray-100 font-medium mb-3">
                                        @switch($key)
                                            @case('external_links')
                                                <i class="fas fa-external-link-alt w-5 mr-2 text-purple-500"></i>
                                                External Links
                                                @break
                                            @case('common_faults')
                                                <i class="fas fa-exclamation-triangle w-5 mr-2 text-yellow-500"></i>
                                                Known Issues
                                                @break
                                            @case('guides')
                                                <i class="fas fa-book w-5 mr-2 text-blue-500"></i>
                                                Repair Guides
                                                @break
                                            @case('buy_links')
                                                <i class="fas fa-shopping-cart w-5 mr-2 text-green-500"></i>
                                                Where to Buy
                                                @break
                                            @default
                                                <i class="fas fa-link w-5 mr-2 text-gray-500"></i>
                                                {{ Str::title(str_replace('_', ' ', $key)) }}
                                        @endswitch
                                    </h4>
                                    <div class="grid gap-3">
                                        @foreach(explode(',', $links) as $link)
                                            @if(filter_var(trim($link), FILTER_VALIDATE_URL))
                                                <a href="{{ trim($link) }}" 
                                                   target="_blank" 
                                                   class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-purple-50 dark:hover:bg-gray-600 transition-colors group">
                                                    <div class="flex-grow">
                                                        <div class="text-gray-900 dark:text-gray-100 group-hover:text-purple-600 dark:group-hover:text-purple-400">
                                                            {{ parse_url(trim($link))['host'] ?? trim($link) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                            {{ str_replace(['http://', 'https://', 'www.'], '', trim($link)) }}
                                                        </div>
                                                    </div>
                                                    <i class="fas fa-external-link-alt ml-2 text-gray-400 group-hover:text-purple-500"></i>
                                                </a>
                                            @else
                                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                    <span class="text-gray-700 dark:text-gray-300">{{ trim($link) }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                    <div class="bg-white11 dark:bg-gray-80011 rounded-xl1 shadow-sm1 p-4">
                        {{-- <h3 class="text-xl font-semibold mb-4 dark:text-white">Resources</h3> --}}
                        <div class="flex flex-wrap -mx-2">
                            @foreach($item->formatted_resources as $key => $links)
                                <div class="px-2 w-1/4">
                                    <h4 class="text-gray-900 dark:text-gray-100 font-medium mb-2">
                                        @switch($key)
                                            @case('external_links')
                                                External Links
                                                @break
                                            @case('common_faults')
                                                Known Issues
                                                @break
                                            @case('guides')
                                                Repair Guides
                                                @break
                                            @case('buy_links')
                                                Where to Buy
                                                @break
                                            @default
                                                {{ Str::title(str_replace('_', ' ', $key)) }}
                                        @endswitch
                                    </h4>
                                    <ul class="space-y-1">
                                        @foreach(explode(',', $links) as $link)
                                            <li>
                                                @if(filter_var(trim($link), FILTER_VALIDATE_URL))
                                                    <a href="{{ trim($link) }}" 
                                                       target="_blank" 
                                                       class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        {{ parse_url(trim($link))['host'] ?? trim($link) }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-700 dark:text-gray-300">{{ trim($link) }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            <div class="bg-white mt-5 dark:bg-gray-800 rounded-xl shadow-sm p-4">
                        <h3 class="text-xl font-semibold mb-4 dark:text-white">Description</h3>
                <!-- Description -->
                <div class="prose prose-lg max-w-none dark:prose-invert mb-8">
                    {!! $item->description !!}
                </div>
            </div>


                <!-- Related Items -->
                @if($related->count() > 0)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-8">
                        <h3 class="text-2xl font-bold mb-6 dark:text-white">Related Items</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            @foreach($related as $relatedItem)
                                <a href="{{ route('front.nostalgia.show', $relatedItem->slug) }}" 
                                   class="group">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                                        @if($relatedItem->gallery)
                                            <img src="{{ Helpers::image(json_decode($relatedItem->gallery)[0], 'nostalgia/items/') }}" 
                                                 alt="{{ $relatedItem->name }}"
                                                 class="w-full h-40 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <h4 class="font-semibold group-hover:text-purple-600 dark:text-white transition-colors">
                                                {{ $relatedItem->name }}
                                            </h4>
                                            @if($relatedItem->release_year)
                                                <span class="text-sm text-gray-500">
                                                    Released: {{ $relatedItem->release_year }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3">
                <!-- Specifications -->
                @if(!empty($item->formatted_specifications))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                        
                        <div class="space-y-4">
                            @foreach($item->formatted_specifications as $key => $value)
                                <div class="border-b border-gray-200 dark:border-gray-700 py-2 text-center">
                                    <div class="font-bold text-lg mb-1 text-gray-800 dark:text-gray-200">
                                        {{ Str::title(str_replace('_', ' ', $key)) }}
                                    </div>
                                    <div class="text-gray-600 dark:text-gray-400">
                                        {{ $value }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.nostalgiaGallery')) {
            new Swiper('.nostalgiaGallery', {
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
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
    });
</script>
@endsection
