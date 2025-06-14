@extends('admin.layouts.master')
@section('title', 'Edit Blog Post')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.blog.index') }}">Blog Posts</a>
        @endslot
        @slot('title')
            Edit Post
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.blog.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf
                @include('admin.includes.ajax-alerts')
                @include('admin.blog.posts.partials.inputs')
                
                <div class="col-12 mb-4">
                    <button class="btn btn-primary submit-btn" type="submit">Update Post</button>
                </div>
            </form>
        </div>
    </div>
@endsection
