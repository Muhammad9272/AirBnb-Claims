@extends('admin.layouts.master')

@section('title')
    Edit Bit Task
@endsection

@section('css')
    <!-- Summernote css -->
    <link href="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Bit Task</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bit-tasks.index') }}">Bit Tasks</a></li>
                        <li class="breadcrumb-item active">Edit Task</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.bit-tasks.update', $data->id) }}" id="geniusform">
                        @csrf
                         @include('admin.includes.ajax-alerts')
                        
                        @include('admin.bits-scheme.tasks.partials.inputs')
                        
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Update Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Summernote js -->
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
