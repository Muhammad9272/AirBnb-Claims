@extends('admin.layouts.master')
@section('title', 'Edit Category')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.product-categories.index') }}">Product Categories</a>
        @endslot
        @slot('title')
            Edit Category
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.product.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="geniusform">
                @csrf
              
                @include('admin.includes.ajax-alerts')
                @include('admin.products.listing.partials.inputs')

                  <div class="col-12 mb-4 ">
                                <button class="btn btn-primary submit-btn" type="submit">Submit form</button>
                            </div>
            </form>
        </div>
    </div>
@endsection
