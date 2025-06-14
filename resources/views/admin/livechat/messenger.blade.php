@extends('admin.layouts.master')
@section('title') Live Chat @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')

@slot('li_1') <a href="{{ route('admin.courses.index') }}">  Live Chat</a> @endslot
@slot('title')  Live Chat @endslot

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/glightbox/glightbox.min.css')}}">

@endsection
@endcomponent

        <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1 ap_messenger__card">            
            @include('vendor.Chatify.pages.app')
        </div>


@endsection
@section('script')

@endsection
