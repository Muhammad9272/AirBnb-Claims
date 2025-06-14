@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Subscription Features
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.subfeature.index') }}"> Subscription Features </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Create Subscription Features </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.subfeature.store') }}" method="post" >
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label"> Name</label>

                                        <input class="form-control" data-choices data-choices-multiple-remove="true" placeholder="Enter Names" name="name" type="text" value="" />



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
   <script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
   <script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>
@endsection
