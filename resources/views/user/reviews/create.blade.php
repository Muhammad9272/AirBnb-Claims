@extends('front.layouts.app')

@section('meta_title', 'Write a Review')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Write a Review</h1>
            
            <!-- Product Info -->
            <div class="flex items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <img src="{{ Helpers::image($product->main_image, 'products/') }}" 
                     alt="{{ $product->name }}" 
                     class="w-16 h-16 object-cover rounded-md">
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category ? $product->category->name : 'Uncategorized' }}</p>
                </div>
            </div>
            
            <!-- Verification Status -->
            @if(!$hasOrdered)
                <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-md mb-6">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Your review won't be labeled as 'Verified Purchase' because we couldn't confirm your purchase of this product.
                    </p>
                </div>
            @else
                <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-md mb-6">
                    <p class="text-sm text-green-800 dark:text-green-300">
                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Your review will be marked as 'Verified Purchase'.
                    </p>
                </div>
            @endif
            
            <!-- Review Form -->
            <form action="{{ route('user.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Rating -->
                <div class="mb-6">
                    <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <div class="flex space-x-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }} {{ $i == 5 && !old('rating') ? 'checked' : '' }}>
                                <svg class="w-8 h-8 peer-checked:text-yellow-400 text-gray-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Review Text -->
                <div class="mb-6">
                    <label for="review_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Review</label>
                    <textarea id="review_text" name="review_text" rows="5" 
                              class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Share your experience with this product...">{{ old('review_text') }}</textarea>
                    @error('review_text')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submission Note -->
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    <p>Your review will be visible to other customers after it has been approved by our moderators. We reserve the right to reject inappropriate content.</p>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary-gradient text-white rounded-md hover:opacity-90 transition-opacity">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Simple star rating behavior
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('input[name="rating"]');
        const starSvgs = document.querySelectorAll('input[name="rating"] + svg');
        
        stars.forEach((star, index) => {
            star.addEventListener('change', function() {
                const rating = this.value;
                
                // Update all stars
                starSvgs.forEach((svg, i) => {
                    if (i < rating) {
                        svg.classList.add('text-yellow-400');
                        svg.classList.remove('text-gray-300');
                    } else {
                        svg.classList.remove('text-yellow-400');
                        svg.classList.add('text-gray-300');
                    }
                });
            });
            
            // Hover effect
            starSvgs[index].addEventListener('mouseenter', function() {
                starSvgs.forEach((svg, i) => {
                    if (i <= index) {
                        svg.classList.add('text-yellow-400');
                        svg.classList.remove('text-gray-300');
                    }
                });
            });
            
            starSvgs[index].addEventListener('mouseleave', function() {
                const checkedStar = document.querySelector('input[name="rating"]:checked');
                const checkedValue = checkedStar ? parseInt(checkedStar.value) : 0;
                
                starSvgs.forEach((svg, i) => {
                    if (i < checkedValue) {
                        svg.classList.add('text-yellow-400');
                        svg.classList.remove('text-gray-300');
                    } else {
                        svg.classList.remove('text-yellow-400');
                        svg.classList.add('text-gray-300');
                    }
                });
            });
        });
    });
</script>
@endsection
