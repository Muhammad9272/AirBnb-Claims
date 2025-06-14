@extends('admin.layouts.master')
@section('title', 'Create Blog Post')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.blog.index') }}">Blog Posts</a>
        @endslot
        @slot('title')
            Create Post
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf
                @include('admin.includes.ajax-alerts')
                @include('admin.blog.posts.partials.inputs')
                
                <div class="col-12 mb-4">
                    <button class="btn btn-primary submit-btn" type="submit">Create Post</button>
                </div>
            </form>
        </div>
    </div>
@endsection
