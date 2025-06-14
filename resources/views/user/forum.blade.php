@extends('user.layouts.master')
@section('title')
    @lang('translation.home')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"> Forum</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li> 
                                       
                    <li class="breadcrumb-item active">Forum</li>
                    
                </ol>
            </div>

        </div>
    </div>
</div> 

    <div class="row">
        <div class="col-lg-12">

            <div class="ts-top-banner mb-4">
                <div class="row align-items-center">
                    <div class=" col-lg-7 col-xxl-8 ">
                        <h1 class="fw-semibold text-white">{{$banner->title}}</h1>
                        <p class=" mb-3 mb-lg-4 fs-4 text-white ">
                            {{$banner->details}}
                        </p>
                        <div class="text-lg -end">
                            {{-- <button class="btn btn-primary px-4 fs-4">Buy Now</button> --}}
                            {{-- <button class="btn btn-primary px-4 ">Buy Now</button> --}}
                        </div>
                    </div>
                    <div class="col-lg-5 col-xxl-4">
                        <div class="ratio ratio-16x9 rounded">
                            <video width="320" height="240" class="video-element" poster="@isset($banner->file){!! Helpers::image($banner->file, 'banners/') !!}@endif" controls>
                               <source src="{!! Helpers::image($banner->video, 'banners/video/') !!}" type="video/mp4">
                               
                               Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->

            <div class=" justify-content-evenly">
                <a class="card border border-1" href="#">
                    <div class=" ts-forum-card  d-flex align-items-sm-center flex-column flex-sm-row  gap-3 p-3">
                        <img class="ts-forum-card__img" src="assets/images/small/img-1.jpg" alt="Card image cap">
                        <div>
                            <div>
                                <h4 class="card-title mb-2">General Discussion</h4>
                                <p class="card-text mb-4 mb-sm-0">
                                    Share stories, ideas, pictures and more!
                                </p>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </a>
                <a class="card border border-1" href="/faq">
                    <div class=" ts-forum-card  d-flex align-items-sm-center flex-column flex-sm-row  gap-3 p-3">
                        <img class="ts-forum-card__img" src="assets/images/small/img-1.jpg" alt="Card image cap">
                        <div>
                            <div>
                                <h4 class="card-title mb-2">FAQ</h4>
                                <p class="card-text mb-4 mb-sm-0">
                                    Private category for admins and moderators.
                                </p>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </a>
                <a class="card border border-1" href="/help">
                    <div class=" ts-forum-card  d-flex align-items-sm-center flex-column flex-sm-row  gap-3 p-3">
                        <img class="ts-forum-card__img" src="assets/images/small/img-1.jpg" alt="Card image cap">
                        <div>
                            <div>
                                <h4 class="card-title mb-2">HELP</h4>
                                <p class="card-text mb-4 mb-sm-0">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laborum ea amet, illum
                                    obcaecati perferendis sit,
                                </p>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </a>


            </div>
        </div>
        <!--end col-->
    </div>
    {{-- <div>

        <h1>Getting Started</h1>
        <ol class="fs-5 d-flex flex-column gap-1">
            <li>
                <span>Identify the Assessments you need to prepare for. You can do this in several ways:</span>

                <ul class="mb-1">
                    <li>Information provided directly from the employer
                    </li>
                    <li>GF Employer Guides
                    </li>
                    <li>Be Proactive by practicing assessments ahead of time
                    </li>
                    <li>Online Self-learning
                    </li>

                </ul>

            </li>
            <li>Use GF video and text resources to learn about assessments
            </li>
            <li>Navigate through the range of tests you need to practice via the tabs on the left-hand side. These include;
                Game
                Assessments, Psychometric Tests, and Video Interviews
            </li>
            <li>Use Performance charts and instant feedback reports, instantly generated after a test is completed. These
                include; worked solutions, performance infographics, and tips to improve.
            </li>
        </ol>
        <h1>How to Use Our Tests
        </h1>
        <ul class="fs-5 mb-1">
            <li>Simply navigate through the test tabs on the left hand side of the portal. Click on a test of your choice
                and the test player will launch

            </li>
            <li>The first page will provide you with some instructions about the test. Once ready, please press the ‘next’
                button and the test will begin.

            </li>
            <li>Once you have completed the test, you will automatically receive a report.

            </li>
            <li>The first part of the report tells you how well you have done compared to others. This is a normed score and
                given in percentiles. The report is self-explanatory.
            </li>
            <li>The next section provides an overview of the questions you attempted, and which ones you answer correctly or
                incorrectly.

            </li>
            <li>You can click on any answers you got incorrect to reveal the question, step-by-step solution, and answer.

            </li>
            <li>Other information about the type of items you are performing well on, or struggling with, are provided. E.g.
                it may be you have a strength in percentage-based items, but struggle with items that involve charts. This
                can help you focus your preparation. Accuracy and Speed of completing the test is also considered and
                guidance provided.

            </li>
            <li>Finally, tips on doing your best on the day are given.

            </li>
            <li>You can click on ‘view progress’ on the Home page and see how well you are doing across the many tests you
                have taken.
            </li>
            <li>You can copy your unique ID presented at the top of the Home page and log in anytime in the future to access
                your reports or practice tests. Your unique ID will have a shelf-life of 3 months.

            </li>

        </ul>
    </div> --}}
@endsection




@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
