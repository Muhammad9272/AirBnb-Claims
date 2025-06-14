@extends('admin.layouts.master')
@section('title', 'Create Nostalgia Category')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.nostalgia.category.index') }}">Nostalgia Categories</a>
        @endslot
        @slot('title')
            Create Nostalgia Category
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="live-preview">
                <form action="{{ route('admin.nostalgia.category.store') }}" method="POST" enctype="multipart/form-data" id="geniusform">
                    @csrf

                    @include('admin.includes.ajax-alerts')
                    @include('admin.nostalgia.category.partials.inputs')

                    <div class="col-12 mb-4 ">
                        <button class="btn btn-primary submit-btn" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
