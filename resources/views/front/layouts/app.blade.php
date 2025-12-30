<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

    <!-- Dynamic Meta Tags for SEO -->
  <title>@yield('meta_title') | {{$gs->name}}</title>
  <meta name="description" content="@yield('meta_description', $gs->slogan)">
  
  <link rel="canonical" href="@yield('canonical', url()->current())">

  @hasSection('meta')
    @yield('meta')
  @else
   <meta name="keywords" content="@yield('meta_keywords', $gs->keywords ?? '')">
  @endif

 

    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Config -->
   <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#00031D',
          secondary: '#001133',
          accent: '#4F46E5',
          'accent-light': '#6366F1',
        },
        fontFamily: {
          'sans': ['Inter', 'system-ui', 'sans-serif'],
        },
        animation: {
          'fade-in': 'fadeIn 0.6s ease-out',
          'slide-up': 'slideUp 0.8s ease-out',
          'float': 'float 6s ease-in-out infinite',
        },
        boxShadow: {
          'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
          'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 15px -3px rgba(0, 0, 0, 0.05)',
          'strong': '0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
          'accent': '0 4px 20px -2px rgba(79, 70, 229, 0.3)',
          'accent-light': '0 4px 20px -2px rgba(99, 102, 241, 0.3)',
          'primary': '0 8px 30px -5px rgba(0, 3, 29, 0.4)',
          'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
          'card-hover': '0 4px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
          'inner-light': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
          'glow': '0 0 20px rgba(79, 70, 229, 0.4), 0 0 40px rgba(79, 70, 229, 0.2)',
        }
      }
    }
  }
</script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Animations -->
    <style>
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes slideUp {
        from { 
          opacity: 0;
          transform: translateY(30px);
        }
        to { 
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
      }
      
      .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .nav-scrolled {
        background-color: rgba(0, 3, 29, 0.95) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      }
      
      .gradient-text {
        background: linear-gradient(135deg, #6366F1, #8B5CF6, #EC4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      
      .hero-gradient {
        background: linear-gradient(135deg, #00031D 0%, #001133 50%, #000829 100%);
      }
      
      .section-divider {
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.5), transparent);
        height: 1px;
      }

      /* Add content padding for non-home pages */
      .main-content {
        padding-top: 4rem;
      }

      /* No extra padding for homepage */
      .home-content {
        padding-top: 0;
      }
    </style>

    <style>
      .auth-card {
          background: white;
          border-radius: 16px;
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      }
      
      .accent-gradient {
          background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
      }
      
      .decorative-blob {
          animation: float 6s ease-in-out infinite;
          opacity: 0.15;
      }
      
      @keyframes float {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(-20px); }
      }
    </style>



   
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

  <!-- Alpine.js -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Line Awesome -->
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  @yield('css')
</head>
@if(auth()->check() && (!auth()->user()->stripe_customer_id || !auth()->user()->stripe_payment_method_id))
    <script>
        window.location.href = "{{ route('user.card.index') }}";
    </script>
@endif
@if(auth()->check() && !auth()->user()->survey_completed)
    <script>
        window.location.href = "{{ route('survey.index') }}";
    </script>
@endif
<body class="font-sans">
  <!-- Navigation Bar -->
  @include('front.layouts.navbar')


  <!-- Main Content -->
  <div class="{{ Route::is('front.index') ? 'home-content' : 'main-content' }}">
    @yield('content')
  </div>

 @include('front.partials.lead_funnel_popup')
  @include('front.layouts.footer')

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
  

  <!-- Add AOS Animation Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

  


  <!-- JavaScript for mobile menu, scrolling nav and FAQs -->
    <script>
      // Mobile drawer menu functionality
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const mobileMenuClose = document.getElementById('mobile-menu-close');
      const mobileDrawer = document.getElementById('mobile-drawer');
      const mobileOverlay = document.getElementById('mobile-menu-overlay');
      const body = document.body;

      // Open mobile drawer
      function openMobileDrawer() {
        mobileDrawer.classList.remove('translate-x-full');
        mobileDrawer.classList.add('translate-x-0');
        mobileOverlay.classList.remove('invisible', 'opacity-0');
        mobileOverlay.classList.add('visible', 'opacity-100');
        body.style.overflow = 'hidden'; // Prevent body scroll
      }

      // Close mobile drawer
      function closeMobileDrawer() {
        mobileDrawer.classList.remove('translate-x-0');
        mobileDrawer.classList.add('translate-x-full');
        mobileOverlay.classList.remove('visible', 'opacity-100');
        mobileOverlay.classList.add('invisible', 'opacity-0');
        body.style.overflow = ''; // Restore body scroll
      }

      // Event listeners
      if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openMobileDrawer);
      }

      if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileDrawer);
      }

      if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileDrawer);
      }

      // Close drawer when clicking on menu items
      document.querySelectorAll('.mobile-menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
          // Don't close immediately for forms
          if (!this.closest('form')) {
            setTimeout(closeMobileDrawer, 200);
          }
        });
      });

      // Close drawer on escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !mobileDrawer.classList.contains('translate-x-full')) {
          closeMobileDrawer();
        }
      });

      // Add scroll event for navbar background change
      window.addEventListener('scroll', function() {
        const nav = document.getElementById('main-nav');
        if (window.scrollY > 10) {
          nav.classList.add('nav-scrolled');
          if (nav.classList.contains('glass-effect')) {
            nav.classList.remove('glass-effect');
          }
        } else {
          nav.classList.remove('nav-scrolled');
          if ({{ Route::is('front.index') ? 'true' : 'false' }} && !nav.classList.contains('glass-effect')) {
            nav.classList.add('glass-effect');
          }
        }
      });

      // Smooth scrolling for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
      
      // FAQ toggle functionality
      document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', function() {
          const answer = this.querySelector('.faq-answer');
          const icon = this.querySelector('.faq-icon');
          
          // Close other FAQs
          document.querySelectorAll('.faq-answer').forEach(otherAnswer => {
            if (otherAnswer !== answer && !otherAnswer.classList.contains('hidden')) {
              otherAnswer.classList.add('hidden');
              otherAnswer.closest('.faq-item').querySelector('.faq-icon').style.transform = 'rotate(0deg)';
            }
          });
          
          // Toggle current FAQ
          answer.classList.toggle('hidden');
          icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
      });
    </script>
  @yield('script')
  @stack('scripts')
  @stack('script')



</body>

</html>