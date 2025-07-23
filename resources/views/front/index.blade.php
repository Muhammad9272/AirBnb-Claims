@extends('front.layouts.app')


@section('meta_title', "Home" )

@section('css')
@endsection
@section('content')
   
      <!-- Hero Section -->
    <section class="hero-gradient relative py-20 lg:py-32 overflow-hidden">
      <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-accent/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-light/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in">
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-8 leading-tight">
            Your White-Glove
            <span class="gradient-text block"> Airbnb Claims Management</span>
            <span class="text-white">Partner</span>
          </h1>
          <p class="text-xl md:text-2xl text-white/80 mb-12 max-w-4xl mx-auto leading-relaxed">
            At ClaimPilot+, we believe that managing claims shouldn't be another headache for busy Airbnb hosts. Focus on growing your business while we expertly navigate the complexities of Airbnb's claims system.
          </p>
          <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
            <a href="{{ route('front.pricing') }}" class="bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-2xl hover:shadow-accent/25">
              View Pricing
              <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
            </a>
            <button id="watchDemoBtn" class="border-2 border-white/30 hover:border-white/50 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all hover:bg-white/10 group">
              <svg class="inline-block mr-2 h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Watch Demo
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
      <div class="relative max-w-4xl w-full">
        <!-- Close Button -->
        <button id="closeModal" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <!-- Video Container -->
        <div class="relative aspect-video bg-black rounded-lg overflow-hidden shadow-2xl">
          <!-- YouTube Video Embed -->
          {{-- <iframe 
            id="demoVideo"
            class="w-full h-full" 
            src="" 
            data-src="https://www.youtube.com/embed/YOUR_VIDEO_ID?rel=0&showinfo=0&modestbranding=1&autoplay=1"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe> --}}
          
          <!-- Alternative: Self-hosted Video (uncomment to use instead of YouTube) -->
          
          <video 
            id="demoVideo"
            class="w-full h-full object-cover" 
            controls 
            
            poster="https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1950&q=80"
          >
            <source src="{{ asset('assets/front/demo/demo2.mp4') }}" type="video/mp4">
            {{-- <source src="{{ asset('videos/claimpilot-demo.webm') }}" type="video/webm"> --}}
            Your browser does not support the video tag.
          </video>
         
        </div>
      </div>
    </div>


<!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-slide-up">
          <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">
            Why Choose <span class="gradient-text">ClaimPilot+</span>
          </h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
          <p class="text-xl text-gray-600 max-w-4xl mx-auto">
            Our subscription-based model provides you with unlimited claims support for a predictable, low monthly fee. 
            We integrate cutting-edge technology with a personal touch to deliver efficient, transparent, and stress-free claims management.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <?php
            $aboutFeatures = [
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>',
                'title' => 'Predictable Pricing',
                'description' => 'Low monthly subscription with transparent commission structure. No hidden fees or surprise charges.'
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                'title' => 'Lightning Fast',
                'description' => 'Quick response times and streamlined processes ensure your claims are handled efficiently and professionally.'
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                'title' => 'Maximum Recovery',
                'description' => 'Our expertise ensures you receive the maximum reimbursement for every valid claim submitted.'
              ]
            ];

            foreach ($aboutFeatures as $feature) {
              echo '
              <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="bg-gradient-to-r from-accent to-accent-light rounded-xl p-4 w-fit mb-6">
                  <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . $feature['icon'] . '
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">' . $feature['title'] . '</h3>
                <p class="text-gray-600 leading-relaxed">' . $feature['description'] . '</p>
              </div>';
            }
          ?>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 hero-gradient">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">How It Works</h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
          <p class="text-xl text-white/70 max-w-3xl mx-auto">
            Simple, streamlined process from submission to payout
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <?php
            $steps = [
              [
                'number' => '1',
                'color' => 'from-green-500 to-green-600',
                'title' => 'Submit',
                'description' => 'You report the damage through our simple submission process. We handle all the documentation and evidence gathering.'
              ],
              [
                'number' => '2',
                'color' => 'from-accent to-accent-light',
                'title' => 'We Handle',
                'description' => 'Our expert team manages the entire claims process, from filing to follow-ups, ensuring maximum recovery potential.'
              ],
              [
                'number' => '3',
                'color' => 'from-purple-500 to-purple-600',
                'title' => 'You Get Paid',
                'description' => 'Receive your reimbursement quickly and hassle-free. We only succeed when you do.'
              ]
            ];

            foreach ($steps as $step) {
              echo '
              <div class="text-center glass-effect rounded-2xl p-8">
                <div class="bg-gradient-to-r ' . $step['color'] . ' rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-6 text-white font-bold text-2xl">
                  ' . $step['number'] . '
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4">' . $step['title'] . '</h3>
                <p class="text-white/70 text-lg">' . $step['description'] . '</p>
              </div>';
            }
          ?>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">
            Everything You Need to <span class="gradient-text">Manage Claims</span>
          </h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            From damage documentation to reimbursement tracking, we've got your Airbnb hosting covered.
          </p>
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php
            $features = [
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>',
                'iconBg' => 'bg-accent/10',
                'iconColor' => 'text-accent',
                'title' => 'Easy Claim Submission',
                'description' => 'Submit damage or reimbursement claims with photo uploads, detailed descriptions, and supporting documentation.',
                'features' => [
                  'Drag & drop photo uploads',
                  'Smart form auto-fill',
                  'Document organization'
                ]
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
                'iconBg' => 'bg-blue-100',
                'iconColor' => 'text-blue-600',
                'title' => 'Real-time Tracking',
                'description' => 'Monitor claim status, view progress updates, and get notifications throughout the entire process.',
                'features' => [
                  'Live status updates',
                  'Push notifications',
                  'Progress timeline'
                ]
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                'iconBg' => 'bg-green-100',
                'iconColor' => 'text-green-600',
                'title' => 'Fast Approvals',
                'description' => 'Streamlined workflow ensures quick claim processing and faster reimbursements for approved claims.',
                'features' => [
                  'AI-powered review',
                  'Automated workflows',
                  'Express processing'
                ]
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>',
                'iconBg' => 'bg-purple-100',
                'iconColor' => 'text-purple-600',
                'title' => 'Smart Dashboard',
                'description' => 'View all your claims, analytics, property performance, and financial summaries in one intuitive place.',
                'features' => [
                  'Interactive analytics',
                  'Custom reports',
                  'Performance insights'
                ]
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>',
                'iconBg' => 'bg-orange-100',
                'iconColor' => 'text-orange-600',
                'title' => 'Bank-Grade Security',
                'description' => 'Safely store and organize all claim documentation, photos, and important files with enterprise security.',
                'features' => [
                  '256-bit encryption',
                  'SOC 2 compliance',
                  'Automated backups'
                ]
              ],
              [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                'iconBg' => 'bg-indigo-100',
                'iconColor' => 'text-indigo-600',
                'title' => '24/7 Expert Support',
                'description' => 'Get help when you need it with our dedicated support team of Airbnb hosting experts available around the clock.',
                'features' => [
                  'Live chat support',
                  'Video consultations',
                  'Knowledge base'
                ]
              ]
            ];

            foreach ($features as $feature) {
              echo '
              <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 group">
                <div class="w-16 h-16 ' . $feature['iconBg'] . ' rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                  <svg class="h-8 w-8 ' . $feature['iconColor'] . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . $feature['icon'] . '
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">' . $feature['title'] . '</h3>
                <p class="text-gray-600 mb-4">' . $feature['description'] . '</p>
                <ul class="space-y-2 text-sm text-gray-500">';
                
                foreach ($feature['features'] as $subfeature) {
                  echo '
                  <li class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ' . $subfeature . '
                  </li>';
                }
                
                echo '
                </ul>
              </div>';
            }
          ?>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">Simple, Transparent Pricing</h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Choose the plan that fits your hosting business. No hidden fees, no surprises.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
          @foreach($subscriptionPlans as $plan)
          <!-- {{ $plan->name }} Plan -->
          <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 {{ $plan->is_featured ? 'border-accent' : 'border-transparent hover:border-accent/20' }} relative">
            @if($plan->display_label)
            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
              <span class="bg-gradient-to-r from-accent to-accent-light text-white px-6 py-2 rounded-full text-sm font-semibold">
                {{ $plan->display_label }}
              </span>
            </div>
            @endif
            
            <div class="text-center mb-8">
              <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
              <div class="flex items-baseline justify-center mb-4">
                <span class="text-5xl font-bold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                <span class="text-gray-600 ml-2">/{{ str_replace(['unlimited', 'yearly', 'monthly', 'biannually', 'quarterly', 'weekly'], ['lifetime', 'year', 'month', '6 months', '3 months', 'week'], $plan->interval) }}</span>
              </div>
              <p class="text-accent font-semibold">+ {{ $plan->commission_percentage }}% of all approved claim payouts</p>
            </div>
            
            <div class="mb-8">
              <h4 class="font-semibold text-gray-900 mb-4 text-center">- Features -</h4>
              <ul class="space-y-3">               
                @foreach($plan->feature_list as $feature)
                <li class="flex items-center text-gray-600">
                  <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ $feature }}
                </li>
                @endforeach
              </ul>
            </div>
            
            <a href="{{ route('user.register') }}?plan={{ $plan->slug }}" class="w-full bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 block text-center">
              Subscribe Now
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 hero-gradient">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">What Our Hosts Say</h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <?php
            $testimonials = [
              [
                'quote' => 'ClaimPilot+ recovered $3,400 from damages I never thought I\'d see again. Their team is professional and gets results!',
                'name' => 'Sarah Martinez',
                'position' => 'Superhost, 8 properties'
              ],
              [
                'quote' => 'The peace of mind knowing experts are handling my claims is invaluable. I can focus on my guests instead of paperwork.',
                'name' => 'David Chen',
                'position' => 'Property Manager, 15 listings'
              ],
              [
                'quote' => 'Their success rate is incredible. They recovered damages from situations I thought were hopeless. Highly recommended!',
                'name' => 'Jennifer Thompson',
                'position' => 'Airbnb Host, 3 properties'
              ]
            ];

            foreach ($testimonials as $testimonial) {
              echo '
              <div class="glass-effect rounded-2xl p-8">
                <div class="flex mb-4">
                  <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                  <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                  <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                  <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                  <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                </div>
                <p class="text-white/80 mb-6 italic">"' . $testimonial['quote'] . '"</p>
                <div>
                  <p class="text-white font-semibold">' . $testimonial['name'] . '</p>
                  <p class="text-white/60 text-sm">' . $testimonial['position'] . '</p>
                </div>
              </div>';
            }
          ?>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fade-in">
          <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">
            Frequently Asked <span class="gradient-text">Questions</span>
          </h2>
          <div class="section-divider w-24 mx-auto mb-8"></div>
          <p class="text-xl text-gray-600">
            Everything you need to know about ClaimPilot+
          </p>
        </div>

        <div class="space-y-6">
          <?php
            $faqs = [
              [
                'question' => 'How does ClaimPilot+ work?',
                'answer' => 'ClaimPilot+ streamlines your Airbnb claims process by providing an easy-to-use platform where you can submit damage claims, track their progress, and receive reimbursements. Simply upload photos and documentation, and our expert team helps process your claim quickly for maximum recovery.'
              ],
              [
                'question' => 'Why do you need co-host access?',
                'answer' => 'Airbnb only allows the listing owner or co-host to file official AirCover claims. We use this access exclusively to manage your claims, and you can remove us at any time. This ensures we can act quickly on your behalf to secure the compensation you deserve.'
              ],
              [
                'question' => 'Will you message guests or change my calendar?',
                'answer' => 'No — we only touch what\'s necessary to file your claim. We never interfere with bookings, guest communications, or your property operations. Your hosting experience remains completely under your control.'
              ],
              [
                'question' => 'What types of claims can I submit?',
                'answer' => 'You can submit various types of claims including property damage, theft, cleaning fees, maintenance costs, and any other expenses related to guest stays. Our platform handles both minor incidents and major damage claims with the same level of attention to detail.'
              ],
              [
                'question' => 'Can I remove your team after the claim is processed?',
                'answer' => 'Yes — co-host access can be revoked at any time via your Airbnb settings. You have complete control over our access and can remove us whenever you choose. Many clients keep us on for ongoing claim management, but the choice is entirely yours.'
              ],
              [
                'question' => 'How fast are claims processed?',
                'answer' => 'Our average response time is 24 hours. Simple claims with clear documentation are often processed within 48 hours, while more complex cases may take 3-5 business days. You\'ll receive real-time updates throughout the process, and our team works diligently to secure the maximum reimbursement possible.'
              ],
              [
                'question' => 'Is my data secure?',
                'answer' => 'Absolutely. We use bank-level 256-bit encryption, are SOC 2 compliant, and store all data in secure, encrypted databases. Your personal information and claim documentation are completely protected with enterprise-grade security measures. All our actions as co-hosts are logged in your Airbnb account for complete transparency.'
              ],
              [
                'question' => 'What exactly can you access as a co-host?',
                'answer' => 'As a co-host, we can only access the specific listings you add us to. We can view booking details, submit claims, and communicate with Airbnb support about claims. We cannot change pricing, availability, house rules, or any other property settings.'
              ],
              [
                'question' => 'Can I cancel my subscription anytime?',
                'answer' => 'Yes, you can cancel your subscription at any time with no cancellation fees. You\'ll continue to have access to your account until the end of your current billing period, and we\'ll handle any claims that were submitted prior to cancellation. You can also remove our co-host access instantly if needed.'
              ]
            ];
            foreach ($faqs as $index => $faq) {
              echo '
              <div class="faq-item bg-white rounded-xl p-6 shadow-xl transition-all duration-300 hover:shadow-2xl cursor-pointer">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-semibold text-gray-900">' . $faq['question'] . '</h3>
                  <svg class="faq-icon h-6 w-6 text-accent transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
                <div class="faq-answer hidden mt-4 text-gray-600">
                  ' . $faq['answer'] . '
                </div>
              </div>';
            }
          ?>
        </div>
      </div>
    </section>


    <!-- Contact/CTA Section -->
    <section id="contact" class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">
          Ready to Protect Your <span class="gradient-text">Investment?</span>
        </h2>
        <div class="section-divider w-24 mx-auto mb-8"></div>
        <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto">
          Join ClaimPilot+ today and experience a seamless, worry-free way to protect your property and your bottom line. 
          Let us handle the claims so you can concentrate on what matters most—delighting your guests and expanding your business.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
          <a href="{{ route('user.register') }}" class="bg-gradient-to-r from-accent to-accent-light hover:from-accent-light hover:to-accent text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-2xl hover:shadow-accent/25">
            Get Started Today
            <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
          </a>
          <a href="{{ route('front.contact') }}" class="border-2 border-accent hover:border-accent-light text-accent hover:text-accent-light px-10 py-4 rounded-xl font-semibold text-lg transition-all hover:bg-accent/5">
            Contact Us
          </a>
        </div>
      </div>
    </section>
@endsection
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const watchDemoBtn = document.getElementById('watchDemoBtn');
    const videoModal = document.getElementById('videoModal');
    const closeModal = document.getElementById('closeModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const demoVideo = document.getElementById('demoVideo');
    
    // Open modal
    watchDemoBtn.addEventListener('click', function() {
        videoModal.classList.remove('hidden');
        videoModal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        
        // Load video when modal opens (for YouTube)
        if (demoVideo.tagName === 'IFRAME') {
            demoVideo.src = demoVideo.getAttribute('data-src');
        }
        
        // Auto-play for self-hosted video
        if (demoVideo.tagName === 'VIDEO') {
            demoVideo.play();
        }
    });
    
    // Close modal functions
    function closeVideoModal() {
        videoModal.classList.add('hidden');
        videoModal.classList.remove('flex');
        document.body.style.overflow = 'auto'; // Restore scrolling
        
        // Stop video when modal closes
        if (demoVideo.tagName === 'IFRAME') {
            demoVideo.src = ''; // This stops the YouTube video
        }
        
        if (demoVideo.tagName === 'VIDEO') {
            demoVideo.pause();
            demoVideo.currentTime = 0;
        }
    }
    
    // Close modal event listeners
    closeModal.addEventListener('click', closeVideoModal);
    closeModalBtn.addEventListener('click', closeVideoModal);
    
    // Close modal when clicking outside
    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            closeVideoModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
            closeVideoModal();
        }
    });
    
    // Video analytics (optional)
    watchDemoBtn.addEventListener('click', function() {
        // Track demo video views
        if (typeof gtag !== 'undefined') {
            gtag('event', 'video_play', {
                event_category: 'engagement',
                event_label: 'demo_video'
            });
        }
        
        // Or use your preferred analytics
        console.log('Demo video opened');
    });
});
</script>
@endsection