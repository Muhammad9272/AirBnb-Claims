@extends('front.layouts.app')

@section('meta_title',"Products" )
@section('meta_description', "Browse our collection of computer parts and accessories"  )


@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 w-full bg-cover bg-center" style="background-image: url('{{asset('assets/front/images/homepagebg.jpg')}}');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold tracking-wide">Our Products</h1>
            <div class="container mx-auto px-4 md:px-8 py-4">
                <nav class="text-sm text-gray-300">
                    <a href="/" class="hover:text-purple-400">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-100">Products</span>
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
                    <form action="{{ route('front.products.index') }}" method="GET" class="relative">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="text" name="search" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-600"
                               placeholder="Search products..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Categories with Nested Display -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">Categories</h3>
                    <div class="space-y-3">
                        @foreach($categories->whereNull('parent_id') as $category)
                            <div class="category-item">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('front.products.index', ['category' => $category->slug]) }}"
                                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium
                                              {{ request('category') == $category->slug && !request('subcategory') ? 'text-purple-600' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                    @if($category->subcategories->count() > 0)
                                        <button class="category-toggle p-1" data-target="cat-{{ $category->id }}">
                                            <i class="fas fa-chevron-down transition-transform
                                                {{ (request('category') == $category->slug || 
                                                    $category->subcategories->pluck('slug')->contains(request('subcategory'))) 
                                                    ? 'rotate-180' : '' }}">
                                            </i>
                                        </button>
                                    @endif
                                </div>
                                @if($category->subcategories->count() > 0)
                                    <div id="cat-{{ $category->id }}" 
                                         class="pl-4 mt-2 space-y-2 subcategories 
                                                {{ (request('category') == $category->slug || 
                                                    $category->subcategories->pluck('slug')->contains(request('subcategory'))) 
                                                    ? '' : 'hidden' }}">
                                        @foreach($category->subcategories as $subcategory)
                                            <a href="{{ route('front.products.index', [
                                                    'category' => $category->slug,
                                                    'subcategory' => $subcategory->slug
                                                ]) }}"
                                               class="block text-gray-600 dark:text-gray-400 hover:text-purple-600 py-1
                                                      {{ request('subcategory') == $subcategory->slug ? 'text-purple-600' : '' }}">
                                                {{ $subcategory->name }}
                                                <span class="text-sm text-gray-500">({{ $subcategory->directSubProducts->count() }})</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">Price Range</h3>
                    <form action="{{ route('front.products.index') }}" method="GET" class="space-y-4">
                        @foreach(request()->except(['min_price', 'max_price']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Min</label>
                                <input type="number" name="min_price" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-600" 
                                       value="{{ request('min_price', $priceRange->min_price ?? '') }}"
                                       min="0" step="0.01">
                            </div>
                            <div class="flex-1">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Max</label>
                                <input type="number" name="max_price" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-600" 
                                       value="{{ request('max_price', $priceRange->max_price ?? '') }}"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Apply Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Sort Options -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold dark:text-white">{{ $products->total() }} Products</h2>
                    <select onchange="window.location.href=this.value"
                            class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2">
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ route('front.products.index', array_merge(request()->except('sort'), ['sort' => $value])) }}"
                                    {{ request('sort') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Products Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $data)
                        @include('front.products.includes.product-card')
                    @empty
                        <div class="col-span-full text-center py-12">
                            <h3 class="text-xl text-gray-600 dark:text-gray-400">No products found</h3>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links('front.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category toggle functionality
        document.querySelectorAll('.category-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const targetDiv = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                targetDiv.classList.toggle('hidden');
                icon.style.transform = targetDiv.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
    });
    </script>
    @endsection
@endsection
