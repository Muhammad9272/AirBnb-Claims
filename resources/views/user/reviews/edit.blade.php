@extends('front.layouts.app')

@section('meta_title', 'Edit Review')

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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Your Review</h1>
            
            <!-- Product Info -->
            <div class="flex items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <img src="{{ Helpers::image($review->product->main_image, 'products/') }}" 
                     alt="{{ $review->product->name }}" 
                     class="w-16 h-16 object-cover rounded-md">
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $review->product->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $review->product->category ? $review->product->category->name : 'Uncategorized' }}
                    </p>
                </div>
            </div>
            
            <!-- Review Form -->
            <form action="{{ route('user.reviews.update', $review->id) }}" method="POST">
                @csrf
                
                <!-- Rating -->
                <div class="mb-6">
                    <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <div class="flex space-x-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" 
                                       {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                                <svg class="w-8 h-8 peer-checked:text-yellow-400 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} transition-colors" 
                                     fill="currentColor" viewBox="0 0 20 20">
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
                              placeholder="Share your experience with this product...">{{ old('review_text', $review->review_text) }}</textarea>
                    @error('review_text')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Review Status -->
                <div class="mb-6">
                    <div class="{{ $review->approved ? 'bg-green-50 dark:bg-green-900/30' : 'bg-yellow-50 dark:bg-yellow-900/30' }} p-4 rounded-md">
                        <p class="text-sm {{ $review->approved ? 'text-green-800 dark:text-green-300' : 'text-yellow-800 dark:text-yellow-300' }}">
                            @if($review->approved)
                                <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Your review is currently live on the product page.
                            @else
                                <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Your review is pending approval by our moderators.
                            @endif
                        </p>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Note: Editing your review will require re-approval by our moderators.
                    </p>
                </div>
                
                <!-- Submit Button & Delete Link -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('user.reviews.delete', $review->id) }}" 
                       class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                       onclick="return confirm('Are you sure you want to delete your review?')">
                        Delete Review
                    </a>
                    
                    <button type="submit" class="px-4 py-2 bg-primary-gradient text-white rounded-md hover:opacity-90 transition-opacity">
                        Update Review
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
