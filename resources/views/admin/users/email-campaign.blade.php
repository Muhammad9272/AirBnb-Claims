@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Email Campiagn
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href=""> Email campaign  </a>
        @endslot
        @slot('title')
            Export User
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

                <div class="card p-4">
                    <div class="live-preview">
                        @include('admin.includes.alerts')

                        @if (session('error'))
                         <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show " role="alert">
                            <i class="ri-error-warning-line label-icon"></i>
                             <p class="mb-0"> {{ session('error') }}</p> 
                         </div>
                        @endif
                        @if (session('success'))
                         <div class="alert alert-success alert-dismissible alert-label-icon rounded-label fade show " role="alert">
                            <i class="ri-error-warning-line label-icon"></i>
                             <p class="mb-0"> {{ session('success') }}</p> 
                         </div>
                        @endif

                        <form action="{{ route('admin.users.email.campaign.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <h5>Click The Button below to export latest contacts to mailchimp</h5>
                            <div class="col-12 mb-4 mt-5">
                                <button class="btn btn-primary" type="submit">Export</button>
                            </div>
                        </form>
                        <a href="https://us17.admin.mailchimp.com/" class="btn btn-primary btn-success" target="_blank">Go to Mailchimp</a>
                        <!--end row-->
                    </div>
                </div>
                
            
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')


<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>




@endsection
