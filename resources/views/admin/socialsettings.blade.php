@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Social Settings
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.password') }}"> Social  </a>
        @endslot
        @slot('title')
              Settings
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Social Settings </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.social.update') }}" method="post" >
                           @csrf
                           <div class="row g-2">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 bg-primary">
                                                        <i class="ri-facebook-fill"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="" placeholder="Facebook"
                                                    value="{{$data->facebook}}" name="facebook">
                                            </div>
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                                        <i class="ri-twitter-fill"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="websiteInput" placeholder="Twitter"
                                                    value="{{$data->twitter}}" name="twitter">
                                            </div>
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 instagram-bg">
                                                        <i class="ri-instagram-fill"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="dribbleName" placeholder="instagram"
                                                    value="{{$data->instagram}}" name="instagram">
                                            </div>
                                            <div class="d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 youtube-bg">
                                                        <i class="ri-youtube-fill"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="pinterestName" placeholder="youtube"
                                                    value="{{$data->youtube}}" name="youtube">
                                            </div>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                        </form>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
