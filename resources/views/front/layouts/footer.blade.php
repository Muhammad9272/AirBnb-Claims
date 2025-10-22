<!-- Navigation -->
<nav id="main-nav" class="fixed top-0 w-full z-50 {{ Route::is('front.index') ? 'glass-effect' : 'bg-primary shadow-lg' }} transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <a href="{{ route('front.index') }}" class="flex items-center space-x-3">
            <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" width="190">
          </a>
        </div>
      </div>
      
      <div class="hidden md:block">
        <div class="ml-10 flex items-baseline space-x-3">
          <a href="{{ route('front.about') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">About</a>
          <a href="{{ route('front.blog.index') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Blog</a>
          <a href="{{ route('front.how-it-works') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">How It Works</a>
          <a href="{{ route('front.pricing') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Pricing</a>
          <a href="{{ route('front.contact') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Contact</a>
          
          @auth
            <!-- User dropdown menu when logged in -->
            <div class="relative group">
              <button class="flex items-center space-x-2 text-white hover:text-accent transition-colors">
                @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
                    <img src="{{ Helpers::image(auth()->user()->photo, 'user/avatar/', 'user.png') }}" 
                         alt="{{ auth()->user()->name }}"
                         class="h-8 w-8 rounded-full object-cover border-2 border-accent">
                @else
                    <div class="h-8 w-8 rounded-full bg-accent/20 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <span class="font-medium">{{ auth()->user()->name }}</span>
              </button>
              
              <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-accent/5 hover:text-accent">
                  <i class="fas fa-user-circle mr-2"></i> Dashboard
                </a>
                <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-gray-800 hover:bg-accent/5 hover:text-accent">
                  <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="border-t border-gray-200 my-1"></div>
                <form action="{{ route('user.logout') }}" method="POST" class="block">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          @else
            <!-- Login/Register buttons when not logged in -->
            <a href="{{ route('user.login') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Sign In</a>
            <a href="{{ route('user.register') }}" class="bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-6 py-2 rounded-lg font-medium transition-all transform hover:scale-105">
              Get Started
            </a>
          @endauth
        </div>
      </div>

      <div class="md:hidden">
        <button id="mobile-menu-btn" class="text-white hover:text-accent transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="md:hidden hidden bg-primary/95 backdrop-blur-md">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <a href="{{ route('front.about') }}" class="text-white/80 hover:text-white block px-3 py-2 text-sm font-medium transition-colors">About</a>
      <a href="{{ route('front.blog.index') }}" class="text-white/80 hover:text-white block px-3 py-2 text-sm font-medium transition-colors">Blog</a>
      <a href="{{ route('front.how-it-works') }}" class="text-white/80 hover:text-white block px-3 py-2 text-sm font-medium transition-colors">How It Works</a>
      <a href="{{ route('front.pricing') }}" class="text-white/80 hover:text-white block px-3 py-2 text-sm font-medium transition-colors">Pricing</a>
      <a href="{{ route('front.contact') }}" class="text-white/80 hover:text-white block px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                
      @auth
        <!-- Mobile User Menu when logged in -->
        <div class="pt-4 border-t border-white/20">
          <div class="flex items-center space-x-3 mb-4 px-3">
            @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
                <img src="{{ Helpers::image(auth()->user()->photo, 'user/avatar/', 'user.png') }}" 
                     alt="{{ auth()->user()->name }}"
                     class="h-10 w-10 rounded-full object-cover border-2 border-accent">
            @else
                <div class="h-10 w-10 rounded-full bg-accent/20 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <span class="font-medium text-white">{{ auth()->user()->name }}</span>
          </div>
          <div class="space-y-2 px-3">
            <a href="{{ route('user.dashboard') }}" class="block text-white hover:text-accent">
              <i class="fas fa-user-circle mr-2"></i> Dashboard
            </a>
            <a href="{{ route('user.profile') }}" class="block text-white hover:text-accent">
              <i class="fas fa-cog mr-2"></i> Settings
            </a>
            <form action="{{ route('user.logout') }}" method="POST">
              @csrf
              <button type="submit" class="w-full text-left text-red-400 hover:text-red-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </button>
            </form>
          </div>
        </div>
      @else
        <!-- Login/Register links when not logged in -->
        <a href="{{ route('user.login') }}" class="text-white block px-3 py-2 text-base font-medium">Sign In</a>
        <a href="{{ route('user.register') }}" class="bg-gradient-to-r from-accent to-accent-light text-white block w-full text-left px-3 py-2 text-base font-medium rounded-lg mt-4">
          Get Started
        </a>
      @endauth
    </div>
  </div>
</nav>

<!-- Footer -->
<footer class="bg-primary border-t border-white/20 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <div class="col-span-1 md:col-span-2">
        <div class="flex items-center space-x-3 mb-4">
           <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" width="190">
        </div>
        <p class="text-white/70 mb-6 max-w-md">
          {{ $gs->slogan ?? 'Your white-glove Airbnb claims management partner. We handle the complexities so you can focus on growing your business.' }}
        </p>
        <div class="flex space-x-4">
          @if(isset($sociallinks))
            @if($sociallinks->facebook)
              <a href="{{ $sociallinks->facebook }}" class="text-white/60 hover:text-white transition-colors">
                <i class="fab fa-facebook-f"></i>
              </a>
            @endif
            @if($sociallinks->twitter)
              <a href="{{ $sociallinks->twitter }}" class="text-white/60 hover:text-white transition-colors">
                <i class="fab fa-twitter"></i>
              </a>
            @endif
            @if($sociallinks->instagram)
              <a href="{{ $sociallinks->instagram }}" class="text-white/60 hover:text-white transition-colors">
                <i class="fab fa-instagram"></i>
              </a>
            @endif
            @if($sociallinks->youtube)
              <a href="{{ $sociallinks->youtube }}" class="text-white/60 hover:text-white transition-colors">
                <i class="fab fa-youtube"></i>
              </a>
            @endif
          @else
            @if(Route::has('front.privacy'))
              <a href="{{ route('front.privacy') }}" class="text-white/60 hover:text-white transition-colors">Privacy</a>
            @endif
            @if(Route::has('front.terms'))
              <a href="{{ route('front.terms') }}" class="text-white/60 hover:text-white transition-colors">Terms</a>
            @endif
            @if(Route::has('front.contact'))
              <a href="{{ route('front.contact') }}" class="text-white/60 hover:text-white transition-colors">Support</a>
            @endif
          @endif
        </div>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="{{ route('front.index') }}" class="text-white/70 hover:text-white transition-colors">Home</a></li>
          <li><a href="{{ route('front.about') }}" class="text-white/70 hover:text-white transition-colors">About Us</a></li>
          <li><a href="{{ route('front.how-it-works') }}" class="text-white/70 hover:text-white transition-colors">How It Works</a></li>
          <li><a href="{{ route('front.pricing') }}" class="text-white/70 hover:text-white transition-colors">Pricing</a></li>
          @if(Route::has('front.blog.index'))
          <li><a href="{{ route('front.blog.index') }}" class="text-white/70 hover:text-white transition-colors">Blog</a></li>
          @endif
          <li><a href="{{ route('front.contact') }}" class="text-white/70 hover:text-white transition-colors">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-4">Support</h4>
        <ul class="space-y-2">
          @auth
            <li><a href="{{ route('user.dashboard') }}" class="text-white/70 hover:text-white transition-colors">Dashboard</a></li>
            <li><a href="{{ route('user.profile') }}" class="text-white/70 hover:text-white transition-colors">My Account</a></li>
            @if(Route::has('user.subscription.plans'))
              <li><a href="{{ route('user.subscription.plans') }}" class="text-white/70 hover:text-white transition-colors">Plans & Pricing</a></li>
            @endif
          @else
            <li><a href="{{ route('user.login') }}" class="text-white/70 hover:text-white transition-colors">Sign In</a></li>
            <li><a href="{{ route('user.register') }}" class="text-white/70 hover:text-white transition-colors">Register</a></li>
          @endauth
          @if(Route::has('front.help.faqs'))
            <li><a href="{{ route('front.help.faqs') }}" class="text-white/70 hover:text-white transition-colors">FAQ</a></li>
          @endif
          @if(Route::has('front.help.guides'))
            <li><a href="{{ route('front.help.guides') }}" class="text-white/70 hover:text-white transition-colors">Help Guides</a></li>
          @endif
          @if(Route::has('user.tickets.create'))
            <li><a href="{{ route('user.tickets.create') }}" class="text-white/70 hover:text-white transition-colors">Submit a Ticket</a></li>
          @endif
          @if(Route::has('front.contact'))
            <li><a href="{{ route('front.contact') }}" class="text-white/70 hover:text-white transition-colors">Contact Us</a></li>
          @endif
        </ul>
      </div>
    </div>
    <div class="border-t border-white/20 mt-12 pt-8 text-center">
      <p class="text-white/60">&copy; {{ date('Y') }} {{ $gs->name ?? 'ClaimPilot+' }}. All rights reserved.</p>
    </div>
  </div>
</footer>