@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Video
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.course.gallery.index') }}"> Video </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Create Video </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.course.gallery.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Video Title</label>
                                        <input type="text" name="name" class="form-control" id="basiInput" required>

                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Select Module</label>
                                        <select class="form-control" name="module_id" required>
                                            <option selected disabled>Select Module</option>
                                            @foreach($modules as $module)
                                            <option value="{{$module->id}}">{{$module->name}}</option>
                                            @endforeach
                                         </select>

                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <div class="form-check form-switch form-switch-right">
                                            <label class="form-check-label" for="video_switch">Video Link</label>
                                            <input class="form-check-input " type="checkbox" role="switch" name="video_type" id="video_switch" checked value="1">   
                                        </div>
                                        
                                        <div id="video_linkss">
                                            <small class="text-muted mb-2 d-block">If file size is large, its good to add video name from s3 ike video.mp4 </small>
                                            <input type="text" name="video_link" class="form-control" placeholder="video.mp4" >
                                        </div>
                                        
                                        <div id="video_uploadss" style="display:none;">
                                             <small class="text-muted mb-2 d-block">Good to upload small size videos</small>
                                            <input type="file" name="video" class="form-control" id="basiInput" >
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class=" mb-3">Video Image </h5>                       
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
                                        <label for="basiInput" class="form-label">Details Details</label>
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
    <script type="text/javascript">
        $(document).ready(function() {
              $("#video_switch").change(function() {
                if ($(this).is(":checked")) {
                  $("#video_linkss").show();
                  $("#video_uploadss").hide();
                } else {
                  $("#video_linkss").hide();
                  $("#video_uploadss").show();
                }
              });
        });

    </script>
@endsection
