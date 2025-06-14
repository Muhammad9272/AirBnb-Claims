@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Roles
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="">  Edit Role  </a>
        @endslot
        @slot('title')
            Edit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">


                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.role.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('admin.role.partials.inputs')
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary" type="submit">Update</button>
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




@endsection
