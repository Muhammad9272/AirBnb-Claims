<!-- Navigation -->
    <nav id="main-nav" class="fixed top-0 w-full z-50 {{ Route::is('front.index') ? 'glass-effect' : 'bg-primary shadow-lg' }} transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <a href="{{ route('front.index') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-accent to-accent-light rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-white font-bold text-xl">ClaimPilot<span class="text-accent">+</span></span>
              </a>
            </div>
          </div>
          
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-8">
              <a href="{{ route('front.about') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">About</a>
              <a href="{{ route('front.how-it-works') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">How It Works</a>
              <a href="{{route('front.pricing')}}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Pricing</a>
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
          <a href="#about" class="text-white block px-3 py-2 text-base font-medium">About</a>
          <a href="#how-it-works" class="text-white block px-3 py-2 text-base font-medium">How It Works</a>
          <a href="#pricing" class="text-white block px-3 py-2 text-base font-medium">Pricing</a>
          <a href="#contact" class="text-white block px-3 py-2 text-base font-medium">Contact</a>
          
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