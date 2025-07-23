@extends('front.layouts.app')
@section('meta_title') Register @endsection

@section('css')

@endsection

@section('content')
<div class="bg-gray-50 py-16 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-accent rounded-full blur-3xl decorative-blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-light rounded-full blur-3xl decorative-blob" style="animation-delay: -3s;"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">
                    Create Account
                </h1>
                <p class="text-xl text-gray-600">
                    Join ClaimPilot+ and protect your Airbnb investment
                </p>
            </div>

            <!-- Register Form Card -->
            <div class="auth-card p-8 mb-8">
                <form method="POST" action="{{ route('user.register') }}" enctype="multipart/form-data" class="space-y-6">
                    @include('includes.alerts')
                    @csrf

                    <!-- Username Input -->
                    <div>
                        <label class="block text-gray-700 font-medium text-sm mb-2">Full Name</label>
                        <div class="relative">
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                   placeholder="Enter your full name" />
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-gray-700 font-medium text-sm mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                   placeholder="Enter your email" />
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium text-sm mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password-input" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                       placeholder="Create a strong password" />
                                <button type="button" onclick="togglePasswordVisibility('password-input', 'password-toggle-icon')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-accent">
                                    <i class="ri-eye-fill align-middle" id="password-toggle-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium text-sm mb-2">Confirm Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm-password-input" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                       placeholder="Confirm your password" />
                                <button type="button" onclick="togglePasswordVisibility('confirm-password-input', 'confirm-password-toggle-icon')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-accent">
                                    <i class="ri-eye-fill align-middle" id="confirm-password-toggle-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" required
                                   class="w-4 h-4 rounded border-gray-300 text-accent focus:ring-accent focus:ring-offset-0"/>
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the 
                                <a href="{{ route('front.help.terms') }}" 
                                   class="text-accent hover:text-accent-light transition duration-200">
                                    Terms of Service
                                </a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-accent/25 flex items-center justify-center">
                        <span>Create Account</span>
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                     @if ($gs->google_login == 1)
                         <!-- Social Signup Divider -->
                        <div class="relative text-center my-6">
                            <span class="px-3 bg-white relative z-10 text-sm text-gray-500">
                                or sign up with
                            </span>
                            <div class="absolute top-1/2 w-full h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent -z-1"></div>
                        </div>
                    @endif

                   
                    <!-- Social Login Buttons -->
                    <div>
                        @if ($gs->google_login == 1)
                            <a href="{{ url('oauth/google') }}" 
                               class="w-full flex items-center justify-center space-x-3 py-3 px-4 bg-white hover:bg-gray-50 border border-gray-300 hover:border-gray-400 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-gray-700">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582L19.91 3C17.782 1.145 15.055 0 12 0 7.27 0 3.198 2.698 1.24 6.65l4.026 3.115Z"/>
                                    <path fill="#34A853" d="M16.04 18.013c-1.09.703-2.474 1.078-4.04 1.078a7.077 7.077 0 0 1-6.723-4.823l-4.04 3.067A11.965 11.965 0 0 0 12 24c2.933 0 5.735-1.043 7.834-3l-3.793-2.987Z"/>
                                    <path fill="#4A90E2" d="M19.834 21c2.195-2.048 3.62-5.096 3.62-9 0-.71-.109-1.473-.272-2.182H12v4.637h6.436c-.317 1.559-1.17 2.766-2.395 3.558L19.834 21Z"/>
                                    <path fill="#FBBC05" d="M5.277 14.268A7.12 7.12 0 0 1 4.909 12c0-.782.125-1.533.357-2.235L1.24 6.65A11.934 11.934 0 0 0 0 12c0 1.92.445 3.73 1.237 5.335l4.04-3.067Z"/>
                                </svg>
                                <span class="ml-2">Continue with Google</span>
                            </a>
                        @endif
                    </div>

                    <!-- Sign In Link -->
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Already have an account? 
                        <a href="{{ route('user.login') }}" 
                           class="text-accent hover:text-accent-light font-medium transition duration-200">
                            Sign In
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

@include('user.auth.includes.modals')

@section('script')
<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
    } else {
        input.type = 'password';
        icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
    }
}
</script>
@endsection
@endsection
