@pushOnce('partial_css')
<link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
@endPushOnce


                <div class="swiper-wrapper">
                    @foreach(json_decode($data->external_gallery) as $key=>$ex_gallery)
                    <div class="swiper-slide">
                          @if(strpos($ex_gallery, 'youtube') !== false)
                          <div class="ratio ratio-16x9">
                            <iframe src="{{ $ex_gallery }}" frameborder="0" allowfullscreen></iframe>
                          </div>
                          @else
                          <img src="{{$ex_gallery}}" class="img-fluid" alt="...">
                          @endif
                    </div>
                    @endforeach
                    
                </div>


@pushOnce('partial_script')
    <script src="{{ URL::asset('/assets/libs/swiper/swiper.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/swiper.init.js') }}"></script>  --}}
    <script type="text/javascript">
        swiperInit();
        function swiperInit(){
            var swiper = new Swiper(".navigation-swiper", {
              loop: true,
              autoplay: false,
              navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
              },
              pagination: {
                clickable: true,
                el: ".swiper-pagination"
              }
            }); //Pagination Dynamic Swiper
        }
        
    </script>
@endPushOnce  