 <a href="{{ route('front.services.show', $service->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full">
                        <div class="relative w-full h-48 overflow-hidden">
                            @if($service->main_image)
                                <img src="{{ Helpers::image($service->main_image, 'services/') }}" 
                                     alt="{{ $service->title }}"
                                     class="w-full h-full object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300">
                            @endif
                            @if($service->items)
                                <div class="absolute top-4 right-4 bg-purple-600 text-white px-3 py-1 rounded-full text-sm">
                                    From {{ Helpers::setCurrency(min(array_column(json_decode($service->items, true), 'price'))) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-2">
                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full text-sm">
                                    {{ $service->category->name }}
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold mb-3 text-gray-900 dark:text-white group-hover:text-purple-600">
                                {{ $service->title }}
                            </h3>
                            @if($service->summary)
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($service->summary, 100) }}</p>
                            @endif
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center text-purple-600 dark:text-purple-400 font-medium">
                                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>