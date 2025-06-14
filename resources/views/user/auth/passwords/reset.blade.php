@extends('front.layouts.app')
@section('meta_title') Reset Password @endsection

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
                    Create New Password
                </h1>
                <p class="text-xl text-gray-600">
                    Enter a new secure password for your account
                </p>
            </div>

            <!-- Reset Password Form Card -->
            <div class="auth-card p-8 shadow-2xl">
                <form action="{{ route('user.password.reset.update') }}" method="POST" class="space-y-6">
                    @include('includes.alerts')
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div>
                        <label class="block text-gray-700 font-medium text-sm mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ $email ?? old('email') }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                   placeholder="Enter your email"
                                   {{ $email ? 'readonly' : '' }} />
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium text-sm mb-2">New Password</label>
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

                    <!-- Password Requirements -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Password requirements:</h4>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li class="flex items-center">
                                <svg class="h-4 w-4 text-green-500 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Minimum 8 characters
                            </li>
                            <li class="flex items-center">
                                <svg class="h-4 w-4 text-green-500 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                At least one uppercase letter
                            </li>
                            <li class="flex items-center">
                                <svg class="h-4 w-4 text-green-500 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                At least one number
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-accent/25 flex items-center justify-center">
                        <span>Reset Password</span>
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
