<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ $gs->name }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $gs->favicon) }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Dark Mode Toggle Script -->
    <script>
        // Check for dark mode preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    @yield('css')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    @include('front.layouts.partials.header')

    <div class="min-h-screen pt-16">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">@yield('header', 'Dashboard')</h1>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    @include('user.partials.sidebar')
                </div>

                <!-- Content -->
                <div class="lg:w-3/4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('front.layouts.partials.footer')
    @include('front.layouts.partials.scripts')
    @stack('scripts')
</body>
</html>
