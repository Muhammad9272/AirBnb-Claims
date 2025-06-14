@extends('front.layouts.app')

@section('meta_title', 'Change Password')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-accent to-accent-light p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Security Settings</h1>
                            <p class="text-accent-50 mt-1">Manage your password and account security</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password form section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Security Information</h2>
                    
                    <form action="{{ route('user.change-password.update') }}" method="post">
                        @csrf
                        @include('includes.alerts')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" required
                                           class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm placeholder-gray-400">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <button type="button" onclick="togglePassword(this)" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent focus:outline-none">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <div class="relative">
                                    <input type="password" id="new_password" name="new_password" required
                                           class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm placeholder-gray-400">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <button type="button" onclick="togglePassword(this)"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent focus:outline-none">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Password must be at least 8 characters long and contain letters, numbers, and symbols.</p>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                           class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm placeholder-gray-400">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <button type="button" onclick="togglePassword(this)"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent focus:outline-none">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('confirm_password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        
                        <!-- Password Strength Indicator (Optional) -->
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</h3>
                            <ul class="space-y-1 text-sm text-gray-500">
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    At least 8 characters long
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Contains at least one uppercase letter
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Contains at least one number
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Contains at least one special character
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                <!-- Add any additional security tips here -->
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection