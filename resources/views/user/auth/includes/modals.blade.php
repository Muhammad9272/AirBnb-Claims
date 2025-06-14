@if(session('showVerificationModal'))
    <div id="verificationModal" class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Container -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md">
                <!-- Modal Content -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/20 overflow-hidden">
                    <div class="px-6 pt-8 pb-6">
                        <!-- Email Icon -->
                        <div class="mx-auto w-16 h-16 mb-6 rounded-full bg-accent/10 flex items-center justify-center
                                  border border-accent/20">
                            <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <!-- Modal Header -->
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h3>
                            <p class="text-gray-600">
                                Please enter the 4 digit code sent to<br>
                                <span class="text-accent font-medium">{{session('email')}}</span>
                            </p>
                        </div>

                        <!-- Verification Form -->
                        <form id="verify_account_modal" action="{{ route('user.verify.email') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="email" value="{{session('email')}}">
                            
                            <!-- Alert Container -->
                            <div id="alert-container" class="mb-4 hidden">
                                <div class="p-4 rounded-lg"></div>
                            </div>
                            
                            <!-- Verification Code Input -->
                            <div class="relative group">
                                <label for="verification" class="block text-sm font-medium text-gray-700 mb-2">
                                    Verification Code
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                       id="verification" 
                                       name="token" 
                                       placeholder="Enter 4-digit code">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-accent/25 flex items-center justify-center"
                                    id="verify-submit-btn">
                                <span class="relative flex items-center justify-center">
                                    Verify Email
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" 
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <svg id="loading-spinner" class="w-5 h-5 ml-2 animate-spin hidden" 
                                         viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" 
                                                stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" 
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </form>

                        <!-- Resend Code -->
                        <div class="mt-6 text-center">
                            <p class="text-gray-600 text-sm">
                                Didn't receive the code? 
                                <a href="javascript:;" 
                                   data-href="{{route('user.resend.verify', session('email'))}}"
                                   class="text-accent hover:text-accent-light font-medium transition-colors resendcodelk">
                                    Resend
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verify_account_modal');
            const submitBtn = document.getElementById('verify-submit-btn');
            const spinner = document.getElementById('loading-spinner');
            const alertContainer = document.getElementById('alert-container');
            const alertBox = alertContainer.querySelector('div');

            function showAlert(message, type) {
                alertBox.className = `p-4 rounded-lg ${
                    type === 'success' 
                        ? 'bg-green-500/10 border border-green-500/20 text-green-400' 
                        : 'bg-red-500/10 border border-red-500/20 text-red-400'
                }`;
                alertBox.textContent = message;
                alertContainer.classList.remove('hidden');
            }

            function setLoading(isLoading) {
                submitBtn.disabled = isLoading;
                spinner.classList.toggle('hidden', !isLoading);
                submitBtn.querySelector('span:first-child').textContent = isLoading ? 'Verifying...' : 'Confirm';
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                setLoading(true);
                alertContainer.classList.add('hidden');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: form.querySelector('input[name="email"]').value,
                        token: form.querySelector('input[name="token"]').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.msg, 'success');
                        setTimeout(() => {
                            window.location.href = data.route;
                        }, 1500);
                    } else if (data.error) {
                        showAlert(data.error, 'error');
                        setLoading(false);
                    }
                })
                .catch(error => {
                    showAlert('An error occurred. Please try again.', 'error');
                    setLoading(false);
                });
            });

            // Handle resend code
            const resendLink = document.querySelector('.resendcodelk');
            if (resendLink) {
                resendLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-href');
                    this.textContent = 'Sending...';
                    this.style.pointerEvents = 'none';

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('Verification code resent successfully!', 'success');
                            } else {
                                showAlert('Failed to resend code. Please try again.', 'error');
                            }
                        })
                        .catch(error => {
                            showAlert('An error occurred. Please try again.', 'error');
                        })
                        .finally(() => {
                            this.textContent = 'Resend';
                            this.style.pointerEvents = 'auto';
                        });
                });
            }
        });
    </script>
    @endsection
@endif
