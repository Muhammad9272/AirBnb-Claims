 <div class="flex">
            <div class="card flex flex-col h-full min-w-[290px] md:max-w-[365px]
                        bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden
                        transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl
                        border border-gray-100 dark:border-gray-700">
              <div class="relative">
                <img src="{{ Helpers::image($data->main_image, 'products/') }}"
                     class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-105"
                     alt="{{ $data->name }}" />
                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/50 to-transparent"></div>
                @if($data->discount_price && $data->discount_price < $data->paypostage_price)
                  <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    {{ round((($data->paypostage_price - $data->discount_price) / $data->paypostage_price) * 100) }}% OFF
                  </span>
                @endif
              </div>
              <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-bold mb-3 dark:text-white">{{ $data->name }}</h2>
                @if(isset($data->approved_reviews_avg_rating) && $data->approved_reviews_avg_rating > 0)
                  <div class="flex items-center mb-3">
                    <div class="flex gap-1">
                      @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $data->approved_reviews_avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                      @endfor
                    </div>
                    <span class="text-xs text-gray-500 ml-2">({{ $data->approved_reviews_count ?? 0 }})</span>
                  </div>
                @endif
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">
                  {{ Str::limit(strip_tags($data->description), 80) }}
                </p>
                <div class="flex justify-between items-center mt-auto">
                  <!-- <div>
                    @if($data->discount_price && $data->discount_price < $data->paypostage_price)
                      <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ Helpers::setCurrency($data->discount_price) }}
                      </span>
                      <span class="text-sm text-gray-500 line-through">
                        {{ Helpers::setCurrency($data->paypostage_price) }}
                      </span>
                    @else
                      <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ Helpers::setCurrency($data->paypostage_price) }}
                      </span>
                    @endif
                  </div> -->
                  <a href="{{ route('front.postage.show', $data->slug) }}"
                     class="bg-primary-gradient text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition-all duration-300 group">
                    <span>Details</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>