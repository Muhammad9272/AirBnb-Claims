@extends('front.layouts.app')


@section('meta_title', "Nostalgia Collection" )
@section('meta_description', "Explore our collection of nostalgic computer and gaming items"  )


@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 w-full bg-cover bg-center" style="background-image: url('{{asset('assets/front/images/homepagebg.jpg')}}');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold tracking-wide">Nostalgia Collection</h1>
            <!-- Breadcrumb -->
            <div class="container mx-auto px-4 md:px-8 py-4">
                <nav class="text-sm text-gray-300">
                    <a href="/" class="hover:text-purple-400">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-100">Nostalgia</span>
                </nav>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <!-- Search -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                    <form action="{{ route('front.nostalgia.index') }}" method="GET" class="relative">
                        <!-- Retain all existing request parameters except 'search' -->
                        @foreach(request()->except('search') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <input type="text" 
                               name="search" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600"
                               placeholder="Search items..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">Categories</h3>
                    <div class="space-y-3">
                        @foreach($categories as $mainCategory)
                            <div class="category-group">
                                <!-- Main Category -->
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('front.nostalgia.index', ['category' => $mainCategory->slug]) }}" 
                                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium 
                                              {{ request('category') == $mainCategory->slug ? 'text-purple-600 dark:text-purple-400' : '' }}">
                                        {{ $mainCategory->name }}
                                    </a>
                                    @if($mainCategory->children->count() > 0)
                                        <button class="category-toggle p-1 text-gray-400 hover:text-gray-600" 
                                                data-target="sub-{{ $mainCategory->id }}">
                                            <i class="fas fa-chevron-down transition-transform duration-200 
                                                {{ (request('category') == $mainCategory->slug || 
                                                    $mainCategory->children->pluck('slug')->contains(request('subcategory'))) ? 'rotate-180' : '' }}">
                                            </i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Subcategories -->
                                @if($mainCategory->children->count() > 0)
                                    <div id="sub-{{ $mainCategory->id }}" class="ml-4 mt-2 space-y-2 
                                         {{ (request('category') == $mainCategory->slug || 
                                             $mainCategory->children->pluck('slug')->contains(request('subcategory'))) ? 'block' : 'hidden' }}">
                                        @foreach($mainCategory->children as $subCategory)
                                            <div>
                                                <div class="flex items-center justify-between">
                                                    <a href="{{ route('front.nostalgia.index', [
                                                            'category' => $mainCategory->slug, 
                                                            'subcategory' => $subCategory->slug
                                                        ]) }}"
                                                       class="text-gray-600 dark:text-gray-400 hover:text-purple-600 text-sm
                                                              {{ request('subcategory') == $subCategory->slug ? 'text-purple-600 dark:text-purple-400' : '' }}">
                                                        {{ $subCategory->name }}
                                                    </a>
                                                    @if($subCategory->children->count() > 0)
                                                        <button class="category-toggle p-1 text-gray-400 hover:text-gray-600" 
                                                                data-target="child-{{ $subCategory->id }}">
                                                            <i class="fas fa-chevron-down transition-transform duration-200
                                                                {{ (request('subcategory') == $subCategory->slug || 
                                                                    $subCategory->children->pluck('slug')->contains(request('childcategory'))) ? 'rotate-180' : '' }}">
                                                            </i>
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Child Categories -->
                                                @if($subCategory->children->count() > 0)
                                                    <div id="child-{{ $subCategory->id }}" class="ml-4 mt-1 space-y-1
                                                         {{ (request('subcategory') == $subCategory->slug || 
                                                             $subCategory->children->pluck('slug')->contains(request('childcategory'))) ? 'block' : 'hidden' }}">
                                                        @foreach($subCategory->children as $childCategory)
                                                            <a href="{{ route('front.nostalgia.index', [
                                                                    'category' => $mainCategory->slug,
                                                                    'subcategory' => $subCategory->slug,
                                                                    'childcategory' => $childCategory->slug
                                                                ]) }}"
                                                               class="block text-gray-500 dark:text-gray-500 hover:text-purple-600 text-sm pl-2
                                                                      {{ request('childcategory') == $childCategory->slug ? 'text-purple-600 dark:text-purple-400' : '' }}">
                                                                {{ $childCategory->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Year Filter -->
                @if($years->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">Release Year</h3>
                        <div class="space-y-2">
                            @foreach($years as $year)
                                <a href="{{ route('front.nostalgia.index', array_merge(request()->except('year'), ['year' => $year])) }}"
                                   class="flex items-center justify-between py-1 text-gray-700 dark:text-gray-300 hover:text-purple-600">
                                    <span>{{ $year }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Items Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($items as $data)
                      @include('front.nostalgia.includes.nostalgia-card')
                    @empty
                        <div class="col-span-full text-center py-12">
                            <h3 class="text-xl text-gray-600 dark:text-gray-400">No items found</h3>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $items->links('front.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category toggle functionality
    document.querySelectorAll('.category-toggle').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = button.dataset.target;
            const targetDiv = document.getElementById(targetId);
            const icon = button.querySelector('i');
            
            targetDiv.classList.toggle('hidden');
            icon.style.transform = targetDiv.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});
</script>
@endsection
