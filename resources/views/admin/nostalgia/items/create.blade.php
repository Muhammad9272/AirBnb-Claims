@extends('admin.layouts.master')
@section('title', 'Add Nostalgia Item')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.nostalgia.item.index') }}">Nostalgia Items</a>
        @endslot
        @slot('title')
            Add New Item
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.nostalgia.item.store') }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf

                @include('admin.includes.ajax-alerts')
                @include('admin.nostalgia.items.partials.inputs')

                <div class="col-12 mb-4">
                    <button class="btn btn-primary submit-btn" type="submit">Create Item</button>
                </div>
            </form>
        </div>
    </div>
@endsection
