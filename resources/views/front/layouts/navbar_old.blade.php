<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<nav
  class="bg-white dark:bg-gray-900 px-4 py-3 shadow-sm transition-colors duration-300">
  <div class="container mx-auto">
    <!-- Desktop & Mobile Top Bar -->
    <div class="flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="/" class="flex items-center">
          <!-- Light mode logo (hidden in dark mode) -->
          <img
            src="{{ asset('assets/logo/logo-light.png') }}"
            alt="{{ $gs->name }} Logo"
            class="h-10 md:h-14 block dark:hidden" />
          
          <!-- Dark mode logo (hidden in light mode) -->
          <img
            src="{{ asset('assets/logo/logo-dark.png') }}"
            alt="{{ $gs->name }} Logo"
            class="h-10 md:h-16 hidden dark:block" />
        </a>
      </div>

      <!-- Desktop Navigation Links - Hidden on Mobile -->
      <div class="hidden md:flex space-x-4 lg:space-x-8">
        <a href="{{ route('front.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">Home</a>
        <a href="{{ route('front.blog.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">News</a>
        <a href="{{ route('front.products.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">Store</a>
        <a href="{{ route('front.postage.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">JPP</a>
        <a href="{{ route('front.nostalgia.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">NostalgiaBase</a>
        
        <a href="{{ route('front.services.index') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">Services</a>
        <a href="{{ route('front.bit.ledger') }}" 
           class="text-gray-800 dark:text-gray-300 font-medium text-sm lg:text-base">Bit Logs</a>
      </div>

      <!-- Right Side Elements -->
      <div class="flex items-center space-x-2 md:space-x-4">
        <!-- Search Bar -->
        <div class="relative">
          <div
            class="flex w-40 md:w-[253px] items-center bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
            <div class="flex items-center pl-2 md:pl-3">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 md:h-5 md:w-5 text-gray-500 dark:text-gray-300"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="text"
              placeholder="Search"
              class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white py-1 md:py-2 px-1 md:px-2 focus:outline-none w-16 sm:w-24 md:w-32 text-sm" />
            <div>
              <button
                class="bg-primary-gradient text-white px-2 ml-4 md:px-4 py-1 rounded-lg text-xs md:text-sm">
                Search
              </button>
            </div>
          </div>
        </div>

        <!-- Dark Mode Toggle - Visible on All Screens -->
        <button id="theme-toggle" class="p-1 md:p-2 rounded-md">
          <img
            id="theme-icon"
            src="{{asset('assets/front/images/togglingIcon.png')}}"
            alt="Theme Toggle"
            class="w-5 h-5 md:w-6 md:h-6 transition-all duration-300 dark:invert" />
        </button>

        <!-- Desktop - Cart Icon, User Icon and Login/Profile Button -->
        <div class="hidden md:flex items-center space-x-4">
          <!-- Shopping Cart Icon with Dropdown -->
          <div class="relative">
            <a href="{{ route('front.cart.index') }}" class="relative group">
              <i class="fas fa-shopping-cart text-xl"></i>
              <span id="cart-count" 
                    class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $CartLogics::getOrCreateCart()->items->sum('quantity') }}
              </span>
            </a>

            <div id="cart-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50">
              <div class="p-4 text-left">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shopping Cart</h3>
                  <button id="close-cart" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                <div class="space-y-4 my-4 max-h-80 overflow-y-auto">
                    @forelse($CartLogics::getOrCreateCart()->items as $item)
                        <div class="flex items-center space-x-4">
                            <img src="{{ Helpers::image($item->product->main_image, 'products/') }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 rounded object-cover bg-gray-200 dark:bg-gray-700" />
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $item->quantity }}</span>
                                    <span class="mx-1">x</span>
                                    <span>{{ Helpers::setCurrency($item->price) }}</span>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-red-500" 
                                    onclick="removeCartItem({{ $item->id }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            Your cart is empty
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between mb-4 text-gray-900 dark:text-white">
                        <span>Subtotal</span>
                        <span>{{ Helpers::setCurrency($CartLogics::getOrCreateCart()->subtotal) }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('front.cart.index') }}" 
                           class="text-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 
                                  text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            View Cart
                        </a>
                        <a href="{{ route('front.checkout.index') }}" 
                           class="text-center bg-purple-600 hover:bg-purple-700 text-white 
                                  font-medium py-2 px-4 rounded-lg transition-colors">
                            Checkout
                        </a>
                    </div>
                </div>
              </div>
            </div>
          </div>

          @auth
            <!-- User Dropdown -->
            <div class="relative group">
              <button class="flex items-center space-x-2 text-gray-800 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">

                @if(auth()->user()->photo && auth()->user()->photo != 'user.png')
                    <img src="{{ Helpers::image(auth()->user()->photo, 'user/avatar/', 'user.png') }}" 
                         alt="{{ auth()->user()->name }}"
                         class="h-10 w-10 rounded-full object-cover border-2 border-purple-500">
                @else
                    <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold text-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                {{-- <img src="{{ Auth::user()->photo ? Helpers::image(Auth::user()->photo, 'users/avatars') : asset('assets/front/images/default-user.png') }}" 
                     alt="Profile" 
                     class="w-8 h-8 rounded-full object-cover border-2 border-purple-500"> --}}
                <span class="font-medium">{{ Auth::user()->name }}</span>
              </button>
              
              <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-gray-800 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-700">
                  <i class="fas fa-user-circle mr-2"></i> Dashboard
                </a>
                <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-gray-800 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-700">
                  <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                <form action="{{ route('user.logout') }}" method="POST" class="block">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          @else
            <a href="{{ route('user.login') }}" 
               class="bg-primary-gradient hover:opacity-80 text-white px-4 lg:px-6 py-1 lg:py-2 rounded-lg font-medium transition-opacity duration-300">
              <span class="w-16 lg:w-[96px] text-sm lg:text-base">Login</span>
            </a>
          @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button
          id="menu-toggle"
          class="md:hidden text-gray-800 dark:text-white p-1 focus:outline-none">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu - Hidden by Default -->
    <div id="mobile-menu" class="mobile-menu md:hidden mt-2">
      <div class="flex flex-col space-y-4 py-4 px-2 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('front.index') }}" class="text-purple-600 font-medium dark:text-purple-400">Home</a>
        <a href="{{ route('front.blog.index') }}" class="text-gray-800 dark:text-gray-300 font-medium">News</a>
        <a href="{{ route('front.products.index') }}" class="text-gray-800 dark:text-gray-300 font-medium">Store</a>
        <a href="{{ route('front.postage.index') }}" class="text-gray-800 dark:text-gray-300 font-medium">JPP</a>
        <a href="{{ route('front.nostalgia.index') }}" class="text-gray-800 dark:text-gray-300 font-medium">NostalgiaBase</a>
        
        <a href="{{ route('front.services.index') }}" class="text-gray-800 dark:text-gray-300 font-medium">Services</a>
        <a href="{{ route('front.bit.ledger') }}" class="text-gray-800 dark:text-gray-300 font-medium">Bit Logs</a>

        @auth
          <!-- Mobile User Menu -->
          <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3 mb-4">
              <img src="{{ Auth::user()->photo ? Helpers::image(Auth::user()->photo, 'users/') : asset('assets/front/images/default-user.png') }}" 
                   alt="Profile" 
                   class="w-10 h-10 rounded-full object-cover border-2 border-purple-500">
              <span class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
            </div>
            <div class="space-y-2">
              <a href="{{ route('user.dashboard') }}" class="block text-gray-800 dark:text-gray-300">
                <i class="fas fa-user-circle mr-2"></i> Dashboard
              </a>
              <a href="{{ route('user.profile') }}" class="block text-gray-800 dark:text-gray-300">
                <i class="fas fa-cog mr-2"></i> Settings
              </a>
              <form action="{{ route('user.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left text-red-600 dark:text-red-400">
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('user.login') }}" 
             class="bg-primary-gradient hover:opacity-80 text-white px-4 py-2 rounded-lg font-medium transition-opacity duration-300 text-center">
            Login
          </a>
        @endauth
      </div>
    </div>
  </div>
</nav>

<script src="{{asset('assets/front/js/drop-downs-js/cart-dropdown.js')}}"></script>