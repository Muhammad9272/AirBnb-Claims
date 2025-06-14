@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Tutor Management
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.product.index') }}">  Tutor Management  </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">


                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.tutor.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('admin.tutors.partials.inputs')
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary" type="submit">Save & Continue</button>
                            </div>
                        </form>
                        <!--end row-->
                    </div>
                
            
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')



<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>





</script>

@endsection
