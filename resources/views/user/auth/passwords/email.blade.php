@extends('front.layouts.app')
@section('meta_title') Forgot Password @endsection

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
                    Forgot Password?
                </h1>
                <p class="text-xl text-gray-600">
                    Enter your email to reset your password
                </p>
            </div>

            <!-- Forgot Password Form Card -->
            <div class="auth-card p-8 shadow-2xl">
                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Enter your email address and we'll send you instructions to reset your password.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.forgot.submit') }}" method="POST" class="space-y-6">
                    @include('includes.alerts')
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-gray-700 font-medium text-sm mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                   placeholder="Enter your email" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-4 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-accent/25 flex items-center justify-center">
                        <span>Send Reset Link</span>
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <!-- Back to Login Link -->
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Remember your password? 
                        <a href="{{ route('user.login') }}" 
                           class="text-accent hover:text-accent-light font-medium transition duration-200">
                            Back to Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
