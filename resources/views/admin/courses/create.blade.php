@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Courses
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.courses.index') }}"> Courses </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Create Courses </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.courses.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Course Name</label>
                                        <input type="text" name="name" class="form-control" id="basiInput" required>

                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Course Author</label>
                                        <input type="text" name="author" class="form-control" id="basiInput" required>

                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class=" mb-3">Courses Image </h5>                       
                                     <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{{URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class=" avatar-xl img-thumbnail user-profile-image"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="photo" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <small>Preferred Size : 289 * 312</small>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">Course Details</label>
                                        <textarea type="text" name="details" class="form-control" id="basiInput" ></textarea>

                                    </div>
                                </div>
  
                                <!--end col-->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Submit form</button>
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
<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
