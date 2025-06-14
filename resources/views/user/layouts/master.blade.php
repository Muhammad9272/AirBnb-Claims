<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-layout-style="detached" data-layout-mode="{{Auth::user()->dark_mode < 1 ? 'light' : 'dark'}}">

<head>
    <!-- Google tag (gtag.js) -->
    {!! $gs->google_analytics_code !!}

    <meta charset="utf-8" />
    <title>@yield('title')| {{$gs->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="maalik9272@gmail.com" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    @include('user.layouts.head-css')
</head>

@section('body')
    @include('user.layouts.body')
@show
<!-- Begin page -->
<div class="ts-layout-wrapper-outer userdashboard_layout_outer">
    <div id="layout-wrapper" class="position-relative overflow-hidden">

        <div class="ts-circle ts-circle-1 ts-circle--xl"></div>
        <div class="ts-circle ts-circle-2 ts-circle--lg"></div>
        {{-- @include('user.layouts.topbar') --}}
        @include('user.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content ts-mh-100 py-4 px-0">
                <div class="position-relative ts-page-content-inner ts-mh-negative-padding pb-5">

                    @include('user.layouts.topbar')
                    <div class="container-fluid">
                        @yield('content')
                    </div>

                    @include('user.layouts.footer')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            {{-- @include('user.layouts.footer') --}}
        </div>
        <!-- end main content-->
    </div>
</div>

<!-- Your usual HTML content -->

<!-- Begin Tawk.to Script Integration -->
<script type="text/javascript">
    window.Tawk_API = window.Tawk_API || {};
    
    // Inserting the main Tawk.to script.
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/64b8a96fcc26a871b02982bc/1h5olbn60'; // Your specific Tawk.to URL here
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();

    // If user is authenticated, we'll set their attributes in Tawk.to
    @if(Auth::check())
        window.Tawk_API.onLoad = function() {
            window.Tawk_API.setAttributes({
                'id': '{{ Auth::user()->affiliate_code }}',
                'name': '{{ Auth::user()->name }}-{{ Auth::user()->affiliate_code }}',
                'email': '{{ Auth::user()->email }}',
                'hash': '{{ hash_hmac('sha256', Auth::user()->email, '2b8a2fe7294a6712f60b299b29e1486c43e7a436') }}' // Ensure you change 'YOUR_SECRET_KEY' to your actual secret key.
            }, function(error) {
                if (error) console.error("Tawk.to error:", error);
            });
        };
    @endif
</script>
<!-- End Tawk.to Script -->


<!-- END layout-wrapper -->

@include('user.layouts.customizer')

<!-- JAVASCRIPT -->
@include('user.layouts.vendor-scripts')
</body>

</html>
