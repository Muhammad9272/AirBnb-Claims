@extends('admin.layouts.master')
@section('title', 'Create Service')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.service.item.index') }}">Services</a>
        @endslot
        @slot('title')
            Create Service
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.service.item.store') }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf
                @include('admin.includes.ajax-alerts')
                @include('admin.services.items.partials.inputs')

                <div class="col-12 mb-4">
                    <button class="btn btn-primary submit-btn" type="submit">Create Service</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('admin/assets/libs/tinymce/tinymce.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initTinyMCE('.tinymce-editor');
    });
</script>
@endsection
