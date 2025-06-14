@extends('layouts.master')
@section('title')
    @lang('translation.home')
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/shepherd.js/shepherd.js.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Home
        @endslot
    @endcomponent

    <div class="row ">
        <div class="col-xxl-8  mb-4 ">
            <div class="card mb-4 ">
                <div class="card-body p-0">
                    <!-- Swiper -->
                    <div class="swiper effect-fade-swiper rounded w-100 ts-home-swiper">
                        <div class="swiper-wrapper">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="swiper-slide p-3 text-white">
                                    <div class="row">

                                        <div class="col-md-8">

                                            <h1 class="text-white mb-3">Practice increases job test success by up to 84%
                                                {{ $i }}</h1>
                                            <p class="mb-3">
                                                Use <b> GFD30</b> at checkout to get an additional 30% off
                                            </p>
                                            <button class="btn btn-success mb-3 ">Clain 30% offer now</button>

                                            <p>* Bradley et al . 2019 - Top 100% Employes</p>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
            <!--end col-->
            <div>

                <div id="tsStepGroup" class="ts-step-group d-flex justify-content-center flex-wrap gap-4">
                    <div class="ts-step">
                        <p class="text-center fs-4 fw-medium mb-2">
                            IDEA
                        </p>
                        <div class="ts-step__main fs-4 fw-medium bg-danger py-4 px-5 text-white">
                            <i class="fs-1 ri-search-line"></i>
                        </div>
                    </div>
                    <div class="ts-step">
                        <p class="text-center fs-4 fw-medium mb-2">
                            ASPIRATION
                        </p>
                        <div class="ts-step__main fs-4 fw-medium bg-primary py-4 px-5 text-white">
                            <i class="fs-1  ri-radar-line"></i>
                        </div>
                    </div>
                    <div class="ts-step">
                        <p class="text-center fs-4 fw-medium mb-2">
                            MARKET
                        </p>
                        <div class="ts-step__main fs-4 fw-medium bg-warning py-4 px-5 text-white">
                            <i class="fs-1  ri-numbers-line"></i>
                        </div>
                    </div>
                    <div class="ts-step">
                        <p class="text-center fs-4 fw-medium mb-2">
                            PROCESS
                        </p>
                        <div class="ts-step__main fs-4 fw-medium bg-info py-4 px-5 text-white">
                            <i class="fs-1  ri-folder-settings-line"></i>
                        </div>
                    </div>
                    <div class="ts-step">
                        <p class="text-center fs-4 fw-medium mb-2">
                            INCOME
                        </p>
                        <div class="ts-step__main fs-4 fw-medium bg-success py-4 px-5 text-white">
                            <i class="fs-1  ri-money-dollar-box-line"></i>
                        </div>
                    </div>
                </div>
                {{-- <div>
                    <div class="card card-warning d-flex flex-row  align-items-center justify-content-between gap-2 p-3">
                        <div>
                            <h3 class="fw-semibold">Practice Now</h3>
                            <p class="mb-0">Over 1300 questions</p>
                        </div>
                        <div>
                            <i class="ts-play-btn  cursor-pointer text-primary ri-play-circle-fill"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card d-flex flex-row  align-items-center justify-content-between gap-2 p-3">
                        <div>
                            <h3 class="fw-semibold">Practice Now</h3>
                            <p class="mb-0">Over 1300 questions</p>
                        </div>
                        <div>
                            <i class="ts-play-btn cursor-pointer text-primary ri-play-circle-fill"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card d-flex flex-row  align-items-center justify-content-between gap-2 p-3">
                        <div>
                            <h3 class="fw-semibold">Practice Now</h3>
                            <p class="mb-0">Over 1300 questions</p>
                        </div>
                        <div>
                            <i class="ts-play-btn cursor-pointer text-primary ri-play-circle-fill"></i>
                        </div>
                    </div>
                </div> --}}


            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card mb-4">
                <!-- Base Example -->
                <div class="accordion" id="default-accordion-example">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                My Favrourites Companies
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#default-accordion-example">
                            <div data-simplebar style="height: 262px;" class="accordion-body">
                                <div class="d-flex flex-wrap gap-2 gap-sm-3 justify-content-center mb-3">
                                    @for ($i = 0; $i < 20; $i++)
                                        <div class="px-0">
                                            <a class="d-inline-block rounded-circle p-1 p-sm-2  shadow border border-gray"
                                                href="#">
                                                <img width="60"
                                                    src="{{ URL::asset('assets/images/svg/crypto-icons/bcn.svg') }}"
                                                    alt="...">
                                            </a>
                                        </div>
                                    @endfor
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a class="text-center" href="#">Load More <i class=" ri-refresh-line"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                My Favrourites Courses
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#default-accordion-example">
                            <div data-simplebar style="height: 262px;" class="accordion-body">
                                <div class="row row-cols-lg-1 justify-content-evenly">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div>
                                            <div class="card border border-gray">
                                                <img class="card-img-top img-fluid"
                                                    src="https://images.unsplash.com/photo-1633279309534-f761427548b6?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1470&amp;q=80"
                                                    alt="Card image cap">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between gap-2">

                                                        <h4 class="card-title mb-2">Faye Xu</h4>
                                                        <h4 class="card-title mb-2 d-flex align-items-center gap-1">
                                                            <i class="ri-star-fill text-warning"></i>
                                                            <span>4.9</span>
                                                        </h4>
                                                    </div>
                                                    <p class="card-text mb-3">
                                                        Finding and expertise 1 to 1 help you modify your CV, resume,
                                                        motivation
                                                        letter, etc which
                                                        can help you get the cahnce to pass the assessment and get
                                                        offer.
                                                    </p>
                                                    <hr>

                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <i class="bx bx-globe fs-1"></i>
                                                        <p class="card-text mb-0">
                                                            English , Chineese
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <i class="bx bx-shopping-bag fs-1"></i>
                                                        <p class="card-text mb-0">
                                                            Finding and expertise 1 to 1 help you modify your CV,
                                                            resume,
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="d-flex align-items-center justify-content-between gap-2 mt-4">
                                                        <h4 class="card-title mb-0">Starting at 199</h4>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-dark  rounded-pill ">Book
                                                            Now</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                My Quiz List
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#default-accordion-example">
                            <div data-simplebar style="height: 262px;" class="accordion-body">
                                @for ($i = 0; $i < 2; $i++)
                                    <div class="card  gap-2 p-3 py-2 shadow-lg border border-1">
                                        <div class="d-flex flex-row  align-items-center justify-content-between ">
                                            <div>
                                                <h2 class="fw-semibold mb-0">Company Name</h2>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="ri-trophy-line display-6 text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="card-body p-0 mb-2">
                                            <p class="fs-5 mb-0">Lorem ipsum dolor sit amet consectetur adipisicing
                                                Ullam in mollitia, corrupti
                                                ex
                                                sunt
                                                necessitatibus assumenda alias dignissimos, consectetur ab eligendi
                                                maxime, distinctio illo
                                                laboriosam magnam dolores sint expedita.</p>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-primary">
                                                Take Assessments
                                            </button>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Upcoming Schedules</h4>
                </div><!-- end cardheader -->
                <div class="card-body pt-0">
                    <div class="upcoming-scheduled">
                        <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                            data-deafult-date="today" data-inline-date="true">
                    </div>
                    <div class="">
                        <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Events:</h6>
                        <div class="mini-stats-wid d-flex align-items-center mt-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                    09
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Development planning</h6>
                                <p class="text-muted mb-0">iTest Factory </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-muted mb-0">9:20 <span class="text-uppercase">am</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mini-stats-wid d-flex align-items-center mt-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                    12
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Design new UI and check sales</h6>
                                <p class="text-muted mb-0">Meta4Systems</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-muted mb-0">11:30 <span class="text-uppercase">am</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mini-stats-wid d-flex align-items-center mt-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                    25
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Weekly catch-up </h6>
                                <p class="text-muted mb-0">Nesta Technologies</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-muted mb-0">02:00 <span class="text-uppercase">pm</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mini-stats-wid d-flex align-items-center mt-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                    27
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">James Bangs (Client) Meeting</h6>
                                <p class="text-muted mb-0">Nesta Technologies</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-muted mb-0">03:45 <span class="text-uppercase">pm</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mt-3 text-center">
                            <a href="javascript:void(0);" class="text-muted text-decoration-underline">View
                                all Events</a>
                        </div>
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card --> --}}
        </div><!-- end col -->
    </div>


    <div class="row row-cols-lg-2 mb-4">
        <div>
            <div class="card p-3">
                <h2>Progress</h2>

                <hr />
                <div>
                    <h3>Grant Thorton</h3>
                    <div class="d-flex align-items-center mb-1 gap-3">

                        <div class="progress   w-100">
                            <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <button class="btn btn-soft-primary fs-3 d-flex align-items-center ">
                            <i class=" ri-arrow-right-s-line d-flex"></i>
                        </button>
                    </div>
                    <p>You have not taken any assessments</p>
                    <a class="btn btn-primary btn-lg rounded-pill">Start Practice</a>
                </div>


            </div>
        </div>
        <div>
            <div class="card p-3">
                <h2>Progress</h2>
                <div>
                    <p>You have not taken any assessments</p>
                    <a class="btn btn-primary btn-lg rounded-pill">My Learning</a>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('script')
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/swiper/swiper.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/swiper.init.js') }}"></script>


    <script src="{{ URL::asset('assets/js/app.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/shepherd.js/shepherd.js.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/tour/tour.init.js') }}"></script> --}}
    <script>
        var tourHome = new Shepherd.Tour({
            defaultStepOptions: {
                cancelIcon: {
                    enabled: true,
                },

                classes: "shadow-md bg-purple-dark",
                scrollTo: {
                    behavior: "smooth",
                    block: "center",
                },
            },
            useModalOverlay: {
                enabled: true,
            },
        });

        if (document.querySelector("#navbar-nav"))
            tourHome.addStep({
                title: "Welcome Back !",
                text: "Welcome to thte tutorial for the Assessment Pass Education System. <br/> <br/>  This tutorial will show you everything you need to finding your target job.",
                attachTo: {
                    element: "#navbar-nav",
                    on: "right",
                },
                buttons: [{
                        text: "Exit Tutorial",
                        classes: "btn btn-danger",
                        action: tourHome.complete,
                    },
                    {
                        text: "Next",
                        classes: "btn btn-warning text-white ms-auto",
                        action() {
                            window.location.href = "/employ-guide";
                            return this.next();
                        },
                    },
                ],
            });

        tourHome.start();
    </script>
@endsection
