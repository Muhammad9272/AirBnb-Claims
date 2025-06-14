@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Import locations Files
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.location.import.create') }}"> Import Data Files </a>
        @endslot
        @slot('title')
            Import
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Import Data Files </h4>
                    <div class="flex-shrink-0">
                       <a href="{{asset('assets/location-csv-format.csv?version='.$gs->app_file_version)}}" class="btn btn-success" > 
                         <i class="ri-download-2-fill me-1 align-bottom"></i>
                       Download sample file</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.location.import.store') }}" enctype="multipart/form-data" method="post" >
                            @csrf
                            <div class="row gy-4">

                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> CSV File </label>
                                        <div class="flex-shrink-0">
                                            <input class="form-control d-none"  name="csvfile" type="file" id="formFile">
                                            <label for="formFile" class="btn btn-danger"><i
                                                    class="ri-upload-2-fill me-1 align-bottom"></i> Upload
                                                File</label>
                                        </div>

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
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
