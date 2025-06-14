@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Import Quiz Bank
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.quizbank.import.create') }}"> Import Quiz Bank </a>
        @endslot
        @slot('title')
            Import
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Import Quiz Bank </h4>
                    <div class="flex-shrink-0">
                       <a href="{{asset('assets/quizbank-csv-format.csv?version='.$gs->app_file_version)}}" download="sampleFile.csv" class="btn btn-success" > 
                         <i class="ri-download-2-fill me-1 align-bottom"></i>
                       Download sample file</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.quizbank.import.store') }}" enctype="multipart/form-data" method="post" >
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Select Quiz Management </label>
                                        <select class="form-select js-example-basic-single" id="quizbankmanagement_id1" name="quizbankmanagement_id" required>
                                            {{-- <option value="" >Select Quiz Management</option> --}}
                                            @foreach($quizbankmanagements as $quizbankmanagements)
                                            <option value="{{$quizbankmanagements->id}}" >{{$quizbankmanagements->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                {{-- <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="location_id" class="form-label"> Location </label>
                                        <select class="form-select js-example-basic-single" id="location_id" name="location_id" required>
                                            <option value="" >Select Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}" >{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}

                                
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
                               {{--  <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-title flex-grow-1 mb-0">Documents</h5>
                                    <div class="flex-shrink-0">
                                        <input class="form-control d-none" type="file" id="formFile">
                                        <label for="formFile" class="btn btn-danger"><i
                                                class="ri-upload-2-fill me-1 align-bottom"></i> Upload
                                            File</label>
                                    </div>
                                </div> --}}
  
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/select2.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
