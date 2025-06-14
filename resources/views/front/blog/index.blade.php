@extends('front.layouts.app')

@section('meta_title', "Blog")
@section('meta_description', "Explore our latest insights, guides, and tech stories about computer nostalgia, services, and products")

@section('meta')
    <meta name="keywords" content="blog, tech articles, computer nostalgia, tech guides">
    <!-- Open Graph Tags -->
    <meta property="og:title" content="Blog - {{ $gs->name }}">
    <meta property="og:description" content="Explore our latest insights, guides, and tech stories">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/front/images/homepagebg.jpg') }}">
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Blog - {{ $gs->name }}">
    <meta name="twitter:description" content="Explore our latest insights, guides, and tech stories">
    <meta name="twitter:image" content="{{ asset('assets/front/images/homepagebg.jpg') }}">
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')
    <!-- Banner Section -->
    <section class="relative h-80 bg-cover bg-center" style="background-image: url('{{ asset('assets/front/images/homepagebg.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-white text-4xl md:text-5xl font-bold">Blog</h1>
            <!-- Breadcrumb -->
            <nav class="mt-4 text-sm text-white/80">
                <a href="{{ url('/') }}" class="hover:text-accent font-semibold">Home</a>
                <span class="mx-2">/</span>
                <span class="font-medium">Blog</span>
            </nav>
        </div>
    </section>

    <!-- Blog Section -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-2/3">
                <!-- Search Bar -->
                <div class="mb-8">
                    <form action="{{ route('front.blog.index') }}" method="GET" class="flex gap-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search articles..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent"
                        />
                        <button
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-xl hover:from-accent-light hover:to-accent-dark transition-colors"
                        >
                            Search
                        </button>
                    </form>
                </div>

                <!-- Blog Posts Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        @include('front.blog.includes.blog-card')
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <h3 class="text-xl text-gray-600">No posts found</h3>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    @include('front.partials.pagination', ['paginator' => $posts])
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3 space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a
                                href="{{ route('front.blog.index', ['category' => $category->slug]) }}"
                                class="flex items-center justify-between px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors
                                    {{ request('category') == $category->slug
                                        ? 'bg-accent/10 text-accent font-medium'
                                        : 'text-gray-700'
                                    }}"
                            >
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                    {{ $category->blogs_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Featured Posts -->
                @if($featured->count())
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Posts</h3>
                        <div class="space-y-4">
                            @foreach($featured as $post)
                                <a href="{{ route('front.blog.show', $post->slug) }}" class="flex items-start gap-4 group">
                                    @if($post->photo)
                                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-200">
                                            <img
                                                src="{{ Helpers::image($post->photo, 'blog/') }}"
                                                alt="{{ $post->title }}"
                                                class="w-full h-full object-cover"
                                            >
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-gray-900 group-hover:text-accent transition-colors">
                                            {{ Str::limit($post->title, 50) }}
                                        </h4>
                                        <span class="text-sm text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    {{-- @include('components.why-choose-us') --}}
@endsection
