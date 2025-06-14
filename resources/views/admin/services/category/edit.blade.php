@extends('admin.layouts.master')
@section('title', 'Edit Service Category')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.service.category.index') }}">Service Categories</a>
        @endslot
        @slot('title')
            Edit Category
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="live-preview">
                <form action="{{ route('admin.service.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="geniusform">
                    @csrf
                    @include('admin.includes.ajax-alerts')
                    @include('admin.services.category.partials.inputs')
                    
                    <div class="col-12 mb-4">
                        <button class="btn btn-primary submit-btn" type="submit">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection