@extends('admin.layouts.master')
@section('title', 'Create Blog Category')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.blog.category.index') }}">Blog Categories</a>
        @endslot
        @slot('title')
            Create Category
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.blog.category.store') }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf
                @include('admin.includes.ajax-alerts')
                @include('admin.blog.category.partials.inputs')
                
                <div class="col-12 mb-4">
                    <button class="btn btn-primary submit-btn" type="submit">Create Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection
