<footer class="relative bg-gray-900 text-white py-16 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full -translate-x-1/2 -translate-y-1/2 dark:from-blue-500/20 dark:to-purple-500/20"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-500/10 to-pink-500/10 rounded-full translate-x-1/3 translate-y-1/3 dark:from-indigo-500/20 dark:to-pink-500/20"></div>
  
    <div class="container mx-auto px-6 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        
        <!-- Brand & Social -->
        <div>
          {{-- Logo - With Dark/Light Mode Support --}}
          <div class="mb-4">
            <!-- We always use the dark logo in footer since footer has dark background -->
            <img
              src="{{ asset('assets/logo/logo-dark.png') }}"
              alt="{{ $gs->name }} Logo"
              class="h-10 md:h-20 block " />
            
         
            
          </div>
          {{-- Slogan --}}
          <p class="text-gray-300">{{ $gs->slogan }}</p>
  
          <ul class="list-inline mt-4 footer-social-link flex space-x-3">
            @if($sociallinks->facebook)
              <li class="list-inline-item">
                <a href="{{ $sociallinks->facebook }}" class="avatar-xs d-block">
                  <div class="avatar-title rounded-circle">
                    <i class="ri-facebook-fill"></i>
                  </div>
                </a>
              </li>
            @endif
            @if($sociallinks->twitter)
              <li class="list-inline-item">
                <a href="{{ $sociallinks->twitter }}" class="avatar-xs d-block">
                  <div class="avatar-title rounded-circle">
                    <i class="ri-twitter-fill"></i>
                  </div>
                </a>
              </li>
            @endif
            @if($sociallinks->instagram)
              <li class="list-inline-item">
                <a href="{{ $sociallinks->instagram }}" class="avatar-xs d-block">
                  <div class="avatar-title rounded-circle">
                    <i class="ri-instagram-fill"></i>
                  </div>
                </a>
              </li>
            @endif
            @if($sociallinks->youtube)
              <li class="list-inline-item">
                <a href="{{ $sociallinks->youtube }}" class="avatar-xs d-block">
                  <div class="avatar-title rounded-circle">
                    <i class="ri-youtube-fill"></i>
                  </div>
                </a>
              </li>
            @endif
            @if($sociallinks->tiktok)
              <li class="list-inline-item">
                <a href="{{ $sociallinks->tiktok }}" class="avatar-xs d-block">
                  <div class="avatar-title rounded-circle">
                    <i class="bx bxl-tiktok"></i>
                  </div>
                </a>
              </li>
            @endif
          </ul>
        </div>
  
        <!-- Quick Links -->
        <div>
          <h3 class="text-xl font-semibold">Quick Links</h3>
          <ul class="mt-4 space-y-2">
            <li><a href="{{ route('front.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">Home</a></li>
            <li><a href="{{ route('front.blog.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">News</a></li>
            <li><a href="{{ route('front.products.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">Store</a></li>
            <li><a href="{{ route('front.nostalgia.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">NostalgiaBase</a></li>
            <li><a href="{{ route('front.services.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">Services</a></li>
            <li><a href="{{ route('user.bit-tasks.index') }}" class="text-gray-300 hover:text-white transition-all duration-300">Bit Logs</a></li>
          </ul>
        </div>
  
        <!-- Support Pages -->
        <div>
          <h3 class="text-xl font-semibold">Support</h3>
          <ul class="mt-4 space-y-2">
            <li><a href="{{ route('front.help.overview') }}" class="text-gray-300 hover:text-white transition-all duration-300">Overview</a></li>
            <li><a href="{{ route('front.help.faqs') }}" class="text-gray-300 hover:text-white transition-all duration-300">FAQs</a></li>
            <li><a href="{{ route('front.help.guides') }}" class="text-gray-300 hover:text-white transition-all duration-300">Guides</a></li>
            <li><a href="{{ route('front.help.terms') }}" class="text-gray-300 hover:text-white transition-all duration-300">Terms & Conditions</a></li>
            <li><a href="{{ route('front.help.privacy') }}" class="text-gray-300 hover:text-white transition-all duration-300">Privacy Policy</a></li>
          </ul>
        </div>
      </div>
  
      <!-- Copyright -->
      <div class="mt-12 text-center text-gray-400 border-t border-gray-700 pt-6">
        <p>&copy; {{ now()->year }} {{ $gs->name }}. All rights reserved.</p>
      </div>
    </div>
  </footer>
  