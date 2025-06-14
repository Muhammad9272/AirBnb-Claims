  <a href="{{ route('front.nostalgia.show', $data->slug) }}"
                           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">
    <div class="w-full h-48 overflow-hidden">
        <img src="{{ Helpers::image($data->main_image, 'nostalgia/items/') }}"
             alt="{{ $data->name }}"
             class="w-full h-full object-cover">
    </div>
    <div class="p-6 flex flex-col flex-grow">
        <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">{{ $data->name }}</h3>
        @if($data->release_year)
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Released: {{ $data->release_year }}</p>
        @endif
        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
            <span class="text-purple-600 dark:text-purple-400">View Details</span>
            @if(isset($data->formatted_specifications['desire']))
                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full text-sm">
                    Desire: {{ $data->formatted_specifications['desire'] }}/10
                </span>
            @endif
        </div>
    </div>
</a>