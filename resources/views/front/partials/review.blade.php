  <!-- Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md mx-auto p-6 shadow-xl w-full transform transition-all">
            <!-- Close button -->
            <button id="close-review-modal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Write a Review
            </div>
            
            <form id="review-form" method="POST" action="{{ route('user.reviews.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Product info -->
                <div class="flex items-center mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <img src="{{ Helpers::image($product->main_image, 'products/') }}" 
                         alt="{{ $product->name }}" 
                         class="w-12 h-12 object-cover rounded-md">
                    <div class="ml-3">
                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</h3>
                    </div>
                </div>
                
                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <div class="flex space-x-2" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" data-rating="{{ $i }}" class="rating-star text-gray-300 focus:outline-none">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                </div>
                
                <!-- Review text -->
                <div class="mb-4">
                    <label for="review_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Review</label>
                    <textarea id="review_text" name="review_text" rows="4" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Share your experience with this product..."></textarea>
                </div>
                
                <!-- Submission note -->
                <div class="mb-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Your review will be visible to other customers after approval. We appreciate your honest feedback!
                    </p>
                </div>
                
                <!-- Submit button -->
                <div class="flex justify-end">
                    <button type="button" id="submit-review" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
    <script>
    // Review Modal & Form functionality 
    $(document).ready(function() {
        // Review Modal Variables
        const $modal = $('#review-modal');
        const $form = $('#review-form');
        const $ratingInput = $('#rating-input');
        const $ratingStars = $('.rating-star');
        const $reviewTextarea = $('#review_text');
        const $modalTitle = $('#modal-title');
        const $openButton = $('#open-review-modal');
        const $editButton = $('#open-edit-review-modal');
        const $closeButton = $('#close-review-modal');
        const $submitButton = $('#submit-review');
        
        // User review data
        const userReview = @json($userReview);
        
        // Open modal for new review
        $openButton.on('click', function() {
            $modalTitle.text('Write a Review');
            $form.attr('action', "{{ route('user.reviews.store') }}");
            setRating(5); // Default rating
            $reviewTextarea.val(''); // Clear textarea
            $modal.removeClass('hidden');
        });
        
        // Open modal for editing review
        $editButton.on('click', function() {
            $modalTitle.text('Edit Your Review');
            $form.attr('action', "{{ route('user.reviews.update', '') }}/" + userReview.id);
            
            // Add method field for update
            if (!$form.find('input[name="_method"]').length) {
                $form.append('<input type="hidden" name="_method" value="POST">');
            }
            
            // Set existing values
            setRating(userReview.rating);
            $reviewTextarea.val(userReview.review_text || '');
            $modal.removeClass('hidden');
        });
        
        // Close modal
        $closeButton.on('click', function() {
            $modal.addClass('hidden');
        });
        
        // Also close when clicking outside
        $modal.on('click', function(e) {
            if (e.target === this) {
                $modal.addClass('hidden');
            }
        });
        
        // Handle star rating selection
        $ratingStars.on('click', function() {
            const rating = parseInt($(this).data('rating'));
            setRating(rating);
        });
        
        // Hover effects
        $ratingStars.on('mouseenter', function() {
            const rating = parseInt($(this).data('rating'));
            highlightStars(rating);
        }).on('mouseleave', function() {
            const currentRating = parseInt($ratingInput.val());
            highlightStars(currentRating);
        });
        
        // Submit review via AJAX
        $submitButton.on('click', function() {
            const formData = new FormData($form[0]);
            const originalText = $submitButton.text();
            
            // Validate
            if (!formData.get('rating')) {
                alert('Please select a rating');
                return;
            }
            
            // Show loading state
            $submitButton.text('Submitting...').prop('disabled', true);
            
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    if (data.success) {
                        // Hide modal
                        $modal.addClass('hidden');
                        
                        // Show success message
                        showToast('Success!', data.message, 'success');
                        
                        // Reload the page after a delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast('Error', data.message || 'Something went wrong', 'error');
                    }
                },
                error: function() {
                    showToast('Error', 'Something went wrong', 'error');
                },
                complete: function() {
                    // Reset button state
                    $submitButton.text(originalText).prop('disabled', false);
                }
            });
        });
        
        // Helper function to highlight stars
        function highlightStars(rating) {
            $ratingStars.each(function(index) {
                if (index < rating) {
                    $(this).addClass('text-yellow-400').removeClass('text-gray-300');
                } else {
                    $(this).removeClass('text-yellow-400').addClass('text-gray-300');
                }
            });
        }
        
        // Helper function to set rating
        function setRating(rating) {
            $ratingInput.val(rating);
            highlightStars(rating);
        }
        
        // Toast notification function
        function showToast(title, message, type = 'success') {
            // Create toast element
            const $toast = $(`
                <div class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-500 translate-y-0 opacity-0 ${type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'}" style="border-left: 4px solid">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" viewBox="0 0 20 20" fill="currentColor">
                                ${type === 'success' 
                                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                                }
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">${title}</h3>
                            <div class="mt-1 text-sm ${type === 'success' ? 'text-green-700' : 'text-red-700'}">${message}</div>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 ${type === 'success' ? 'text-green-500 hover:bg-green-100' : 'text-red-500 hover:bg-red-100'} focus:outline-none close-toast">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).appendTo('body');
            
            // Animate in
            setTimeout(() => {
                $toast.removeClass('translate-y-0 opacity-0').addClass('translate-y-5 opacity-100');
            }, 10);
            
            // Close button functionality
            $toast.find('.close-toast').on('click', function() {
                removeToast($toast);
            });
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                removeToast($toast);
            }, 5000);
        }
        
        function removeToast($toast) {
            $toast.removeClass('translate-y-5 opacity-100').addClass('translate-y-0 opacity-0');
            
            setTimeout(() => {
                $toast.remove();
            }, 300);
        }
    });
    </script>
@endpush    