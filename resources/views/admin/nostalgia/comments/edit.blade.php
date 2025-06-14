@extends('admin.layouts.master')
@section('title')
   Create Product
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.product.index') }}">  Products </a>
        @endslot
        @slot('title')
            Create Product
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="live-preview">
                <form action="{{ route('admin.product.update',$data->id) }}" method="post" enctype="multipart/form-data" id="geniusform">
                    @csrf
                    @include('admin.includes.ajax-alerts')
                    @include('admin.products.partials.inputs')
                    <div class="col-12 mb-4 ">
                        <button class="btn btn-primary submit-btn" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/separateplugins.min.js')}}"></script>
@endsection
