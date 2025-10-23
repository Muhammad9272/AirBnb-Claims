
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