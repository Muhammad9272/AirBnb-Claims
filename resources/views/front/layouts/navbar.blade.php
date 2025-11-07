<!-- Navigation -->
    <nav id="main-nav" class="fixed top-0 w-full z-50 {{ Route::is('front.index') ? 'glass-effect' : 'bg-primary shadow-lg' }} transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <a href="{{ route('front.index') }}" class="flex items-center space-x-3">
                <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" width="190">
               {{--  <div class="w-10 h-10 bg-gradient-to-r from-accent to-accent-light rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-white font-bold text-xl">ClaimPilot<span class="text-accent">+</span></span> --}}
              </a>
            </div>
          </div>
          
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-3">
              <a href="{{ route('front.about') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">About</a>
               <a href="{{ route('front.blog.index') }}" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Blog</a>
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
                      <i class="las la-user-circle text-lg mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-gray-800 hover:bg-accent/5 hover:text-accent">
                      <i class="las la-cog text-lg mr-2"></i> Settings
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form action="{{ route('user.logout') }}" method="POST" class="block">
                      @csrf
                      <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                        <i class="las la-sign-out-alt text-lg mr-2"></i> Logout
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
            <button id="mobile-menu-btn" class="text-white hover:text-accent transition-colors p-2">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300 md:hidden"></div>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-drawer" class="fixed top-0 right-0 h-full w-80 max-w-[85vw] bg-gradient-to-br from-primary via-secondary to-primary shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 ease-in-out md:hidden overflow-y-auto">
      <!-- Drawer Header -->
      <div class="flex items-center justify-between p-6 border-b border-white/10">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-r from-accent to-accent-light rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <span class="text-white font-bold text-lg">Menu</span>
        </div>
        <button id="mobile-menu-close" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- User Profile Section (if logged in) -->
      @auth
        <div class="p-6 border-b border-white/10">
          <div class="flex items-center space-x-4 mb-4">
            @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
              <img src="{{ Helpers::image(auth()->user()->photo, 'user/avatar/', 'user.png') }}" 
                   alt="{{ auth()->user()->name }}"
                   class="h-14 w-14 rounded-full object-cover border-2 border-accent shadow-lg">
            @else
              <div class="h-14 w-14 rounded-full bg-gradient-to-r from-accent to-accent-light flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                {{ substr(auth()->user()->name, 0, 1) }}
              </div>
            @endif
            <div>
              <p class="text-white font-semibold text-base">{{ auth()->user()->name }}</p>
              <p class="text-white/60 text-sm">{{ auth()->user()->email }}</p>
            </div>
          </div>
          
          <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('user.dashboard') }}" class="flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-lg transition-all text-sm font-medium">
              <i class="las la-th-large text-lg"></i>
              <span>Dashboard</span>
            </a>
            <a href="{{ route('user.profile') }}" class="flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-lg transition-all text-sm font-medium">
              <i class="las la-user-cog text-lg"></i>
              <span>Settings</span>
            </a>
          </div>
        </div>
      @endauth

      <!-- Menu Items -->
      <div class="p-6 space-y-1">
        <a href="{{ route('front.about') }}" class="mobile-menu-item group flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-all">
          <i class="las la-info-circle text-xl group-hover:text-accent transition-colors"></i>
          <span class="font-medium">About</span>
        </a>
        
        <a href="{{ route('front.blog.index') }}" class="mobile-menu-item group flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-all">
          <i class="las la-newspaper text-xl group-hover:text-accent transition-colors"></i>
          <span class="font-medium">Blog</span>
        </a>
        
        <a href="{{ route('front.how-it-works') }}" class="mobile-menu-item group flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-all">
          <i class="las la-cogs text-xl group-hover:text-accent transition-colors"></i>
          <span class="font-medium">How It Works</span>
        </a>
        
        <a href="{{ route('front.pricing') }}" class="mobile-menu-item group flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-all">
          <i class="las la-tags text-xl group-hover:text-accent transition-colors"></i>
          <span class="font-medium">Pricing</span>
        </a>
        
        <a href="{{ route('front.contact') }}" class="mobile-menu-item group flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-all">
          <i class="las la-envelope text-xl group-hover:text-accent transition-colors"></i>
          <span class="font-medium">Contact</span>
        </a>
      </div>

      <!-- Bottom Action Buttons -->
      <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-primary/90 to-transparent border-t border-white/10">
        @auth
          <form action="{{ route('user.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/50 text-red-300 hover:text-red-200 px-6 py-3 rounded-lg font-medium transition-all">
              <i class="las la-sign-out-alt text-xl"></i>
              <span>Logout</span>
            </button>
          </form>
        @else
          <div class="space-y-3">
            <a href="{{ route('user.login') }}" class="mobile-menu-item w-full flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition-all">
              <i class="las la-sign-in-alt text-xl"></i>
              <span>Sign In</span>
            </a>
            <a href="{{ route('user.register') }}" class="mobile-menu-item w-full flex items-center justify-center space-x-2 bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-6 py-3 rounded-lg font-medium transition-all shadow-accent">
              <i class="las la-user-plus text-xl"></i>
              <span>Get Started</span>
            </a>
          </div>
        @endauth
      </div>
    </div>
