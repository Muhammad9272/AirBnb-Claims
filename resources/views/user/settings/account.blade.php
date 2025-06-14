@extends('front.layouts.app')

@section('meta_title', 'Account Settings')

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
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <div class="text-center md:text-left text-white">
                            <h1 class="text-2xl md:text-3xl font-bold">{{ auth()->user()->name }}</h1>
                            <p class="text-accent-50 mt-1">{{ auth()->user()->email }}</p>
                            <p class="text-accent-50 mt-1">Member since {{ auth()->user()->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Profile Information Card -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Profile Information</h2>
                        
                        <form action="{{ route('user.account-settings.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('includes.alerts')
                            
                            <!-- Profile Photo -->
                            <div class="flex items-start space-x-6 mb-8">
                                <div class="flex-shrink-0">
                                    <div class="relative">
                                        <img id="preview-image" src="{{ Helpers::image($data->photo, 'user/avatar/', 'user.png') }}" 
                                            class="h-24 w-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                        <label for="photo" class="absolute bottom-0 right-0 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 p-1.5 rounded-full cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 shadow-sm transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" 
                                                onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800 dark:text-white">Profile Photo</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Upload a professional profile picture</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Recommended: JPG or PNG, at least 400×400 pixels</p>
                                </div>
                            </div>
                    
                            <!-- Form Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                    <input type="text" id="name" name="name" value="{{ $data->name }}" required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm placeholder-gray-400">
                                    @error('name')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                                </div>
        
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                    <div class="relative">
                                        <input type="email" id="email" value="{{ $data->email }}" disabled
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contact support to change your email</p>
                                </div>
        
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                    <div class="relative">
                                        <input type="tel" id="phone" name="phone" value="{{ $data->phone }}"
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm placeholder-gray-400">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
        
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                    <div class="relative">
                                        <select id="gender" name="gender" 
                                            class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-accent focus:border-accent shadow-sm appearance-none">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ $data->gender == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $data->gender == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ $data->gender == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-lg transition duration-150">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                <!-- Existing Danger Zone Content -->
            </div>
        </div>
    </div>
</div>
@endsection