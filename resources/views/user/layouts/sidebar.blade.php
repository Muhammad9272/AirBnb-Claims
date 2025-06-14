<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu ts-user-navbar-menu top-0 border-0">
    <!-- LOGO -->
    <div class="navbar-brand-box d-block  mt-4 bg-transparent  border-0">
        <!-- Dark Logo-->
        <a href="{{ route('user.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm-white.svg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-lg.svg') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('user.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm-white.svg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-lg.svg') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav user-dashboard__sidebar" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>

                <li id="homeSidebar" class="nav-item  ">
                    <a class="nav-link   menu-link  text-uppercase " href="{{ route('user.dashboard') }}">
                        <i class="ri-home-7-line"></i> <span class="fw-semibold">Home</span>
                    </a>
                </li>
                <li id="employGuideSidebar" class="nav-item {{ (request()->is('user/employ-guide/*')) ? 'ts-nav-active' : '' }}">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.company.index') }}">
                        <i class=" ri-book-2-line"></i> <span class="fw-semibold">Employer Guide</span>
                    </a>
                </li>

                <li  id="jobListingSidebar" class="nav-item {{ (request()->is('user/job-listing/*')) ? 'ts-nav-active' : '' }}">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.joblisting.index') }}">
                        <i class="ri-briefcase-2-line"></i> <span class="fw-semibold">Job Portal</span>
                    </a>
                </li>

                <li  id="quizPracticeSidebar" class="nav-item {{ (request()->is('user/quiz-bank/*')) || (request()->is('user/quiz-practice/*')) || (request()->is('user/quiz-test/*')) ? 'ts-nav-active' : '' }}">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.quiz.management.index') }}">
                        <i class="ri-file-edit-line"></i> <span class="fw-semibold">Quiz Practice</span>
                    </a>
                </li>


                <li class="nav-item {{ (request()->is('user/interview-coaching*')) || (request()->is('user/tutorial-booking/*')) || (request()->is('user/events*')) || (request()->is('user/event/*')) ? 'ts-nav-active' : '' }}">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.mylearning.index') }}">
                        <i class="ri-play-circle-line"></i> <span class="fw-semibold">Interview Learning</span>
                    </a>
                </li>
                @if ($gs->is_affilate == 1)
                    <li class="nav-item" id="earningsSidebar">
                        <a class="nav-link menu-link text-uppercase" href="{{ route('user.earnings') }}">
                            <i class="ri-coins-line"></i> <span class="fw-semibold">Earnings </span>
                        </a>
                    </li>
                @endif

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.live.chat') }}">
                        <i class="ri-message-2-line"></i> <span class="fw-semibold">Live Chat</span>
                    </a>
                </li>
 --}}

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link  text-uppercase" href="{{ route('user.forum') }}">
                        <i class="ri-message-2-line11"></i> <span class="fw-semibold">Forum</span>
                    </a>
                </li> --}}


                <li class="nav-item">
                    <a href="{{ route('user.pricing') }}" class="nav-link ">
                        <span class="btn btn-warning text-white rounded-3 w-100 fw-bold text-uppercase">
                            UPGRADE NOW
                        </span>
                    </a>
                </li>
                <li class="nav-item px-3 mt-5">
                    <div class="ts-nav-help p-3">

                        <a href="{{ route('user.help') }}"
                            class=" d-flex flex-column align-items-start  text-start pt-0 ">
                            <h4 class="text-white ts-layout-vertical-none">
                                HELP
                            </h4>

                            <p class="text-white ts-layout-vertical-none">
                                Any questions? Please click here for more information and contact us.
                            </p>

                            <div class="btn btn-light text-primary fw-bold py-1 px-3">HELP</div>
                        </a>

                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
