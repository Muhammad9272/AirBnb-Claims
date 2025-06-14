@extends('admin.layouts.master')
@section('title', 'Edit Nostalgia Category')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.nostalgia.category.index') }}">Nostalgia Categories</a>
        @endslot
        @slot('title')
            Edit Nostalgia Category
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="live-preview">
                <form action="{{ route('admin.nostalgia.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="geniusform">
                    @csrf
                    @method('POST')

                    @include('admin.includes.ajax-alerts')
                    @include('admin.nostalgia.category.partials.inputs')

                    <div class="col-12 mb-4 ">
                        <button class="btn btn-primary submit-btn" type="submit">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection