<article class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full overflow-hidden">
    @if($post->photo)
        <div class="w-full h-64 overflow-hidden rounded-t-xl">
            <img
                src="{{ Helpers::image($post->photo, 'blog/') }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover"
            >
        </div>
    @endif

    <div class="p-6 flex flex-col flex-grow">
        <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
            <span>
                <i class="far fa-calendar-alt mr-1"></i>
                {{ $post->created_at->format('M d, Y') }}
            </span>
            <span>
                <i class="far fa-eye mr-1"></i>
                {{ $post->views }} views
            </span>
        </div>

        <h2 class="text-xl font-semibold mb-3">
            <a
                href="{{ route('front.blog.show', $post->slug) }}"
                class="text-gray-900 hover:text-accent transition-colors"
            >
                {{ $post->title }}
            </a>
        </h2>

        <div class="text-gray-600 mb-4 flex-grow">
            {{ Str::limit($post->summary, 120) }}
        </div>

        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-200">
            <a
                href="{{ route('front.blog.show', $post->slug) }}"
                class="inline-flex items-center text-accent hover:text-accent-light"
            >
                Read More
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
            <span class="px-3 py-1 bg-accent-light/20 text-accent rounded-full text-sm font-medium">
                {{ $post->category->name }}
            </span>
        </div>
    </div>
</article>
