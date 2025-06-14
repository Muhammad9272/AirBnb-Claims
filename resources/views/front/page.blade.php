@extends('front.layouts.app')
@section('title') 
    {{ $page->title }}
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-purple-900/60 to-indigo-900/60 backdrop-blur-xl py-20">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="product-particles absolute inset-0"></div>
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-purple-300 to-purple-500 text-transparent bg-clip-text">
                {{ $page->title }}
            </h1>
            <div class="flex items-center text-sm text-gray-300">
                <a href="{{ route('front.index') }}" class="hover:text-purple-300 transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-purple-300">{{ $page->title }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- Legal Pages (Terms, Privacy) -->
            @if(in_array($page->id, ['2', '3']))
                <div class="card-glow rounded-2xl overflow-hidden backdrop-blur-sm border border-purple-500/10">
                    <!-- Header -->
                    <div class="relative overflow-hidden bg-purple-500/10 p-10 border-b border-purple-500/20">
                        <div class="relative z-10">
                            <h2 class="text-3xl font-bold mb-3 text-white">{{ $page->title }}</h2>
                            <p class="text-purple-200/60">Last updated: {{ \Carbon\Carbon::parse($page->updated_at)->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-10 space-y-6 prose prose-lg prose-invert max-w-none">
                        {!! $page->details !!}
                    </div>
                </div>

            <!-- About Page -->
            @elseif($page->id == 1)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Company Image -->
                    <div class="card-glow rounded-2xl overflow-hidden group">
                        <img src="{{asset('/images/nina.png')}}" alt="Company Image" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                    </div>

                    <!-- Company Info -->
                    <div class="card-glow rounded-2xl p-8 bg-gradient-to-b from-purple-500/10 to-transparent">
                        <h2 class="text-2xl font-bold mb-4 text-white">{{$gs->name}}</h2>
                        <p class="text-purple-200/60 leading-relaxed">
                            At {{$gs->name}} we specialize in providing premium gaming enhancements with a focus on security and reliability. Our mission is to deliver undetectable and high-quality products to our gaming community.
                        </p>
                    </div>

                    <!-- Additional Info -->
                    <div class="card-glow rounded-2xl p-8 bg-gradient-to-b from-purple-500/10 to-transparent">
                        <h2 class="text-2xl font-bold mb-6 text-white">Our Values</h2>
                        <ul class="space-y-4">
                            <li class="flex items-center group">
                                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-500/20 mr-3 group-hover:bg-purple-500/30 transition-colors">
                                    <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="text-purple-200/60 group-hover:text-purple-200 transition-colors">Security First</span>
                            </li>
                            <li class="flex items-center group">
                                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-500/20 mr-3 group-hover:bg-purple-500/30 transition-colors">
                                    <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="text-purple-200/60 group-hover:text-purple-200 transition-colors">24/7 Support</span>
                            </li>
                            <li class="flex items-center group">
                                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-500/20 mr-3 group-hover:bg-purple-500/30 transition-colors">
                                    <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="text-purple-200/60 group-hover:text-purple-200 transition-colors">Regular Updates</span>
                            </li>
                        </ul>
                    </div>
                </div>

            <!-- Other Pages -->
            @else
                <div class="card-glow rounded-2xl overflow-hidden backdrop-blur-sm border border-purple-500/10">
                    <div class="p-10 prose prose-lg prose-invert max-w-none">
                        {!! $page->details !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('css')
<style>
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .prose {
        color: rgba(203, 213, 225, 0.8);
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: white;
        margin-top: 2em;
        margin-bottom: 1em;
        font-weight: 600;
    }
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        line-height: 1.8;
    }
    .prose a {
        color: #A78BFA;
        text-decoration: none;
        transition: color 0.2s;
    }
    .prose a:hover {
        color: #8B5CF6;
    }
    .prose ul {
        list-style-type: disc;
        padding-left: 1.5em;
    }
    .prose li {
        margin-top: 0.75em;
        margin-bottom: 0.75em;
    }
</style>
@endsection