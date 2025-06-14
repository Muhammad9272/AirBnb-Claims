@extends('front.layouts.app')

@section('meta_title', 'My Reviews')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>
        
        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">My Reviews</h1>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $reviews->total() }} {{ Str::plural('Review', $reviews->total()) }}
                        </span>
                    </div>
                </div>
                
                <div>
                    @forelse ($reviews as $review)
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex">
                                    <img src="{{ Helpers::image($review->product->main_image ?? '', 'products/', 'placeholder.jpg') }}" 
                                         class="w-16 h-16 object-cover rounded-md" 
                                         alt="{{ $review->product->name ?? 'Product' }}">
                                    <div class="ml-4">
                                        <h2 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $review->product->name ?? 'Unknown Product' }}
                                        </h2>
                                        <div class="flex items-center mt-1">
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $review->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($review->review_text, 100) ?? 'No written review provided.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full 
                                        {{ $review->approved 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' 
                                        }}">
                                        {{ $review->approved ? 'Approved' : 'Pending' }}
                                    </span>
                                    <a href="{{ route('user.reviews.edit', $review->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No Reviews Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">You haven't written any product reviews yet.</p>
                            <a href="{{ route('front.products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                                Browse Products
                            </a>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="p-6">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
