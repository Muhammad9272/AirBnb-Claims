@extends('admin.layouts.master')
@section('title', 'Product Categories')

@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        table.dataTable td,
        table.dataTable th {
            word-wrap: break-word;
        }

        #geniustable_processing {
            background-color: #2425812b;
            position: fixed;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.product-categories.index') }}">Product Categories</a>
        @endslot
        @slot('title')
            Category List
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header ">



                    <div class="row ms-2">
                        <div class="col-lg-12 col-md-4 m-auto text-end">
                            <a href="{{ route('admin.product-categories.create') }}"
                                class="btn btn-primary waves-effect waves-light">Add Category
                            </a>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <table id="geniustable" class="table  dt-responsive align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Parent Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>

             var table= $('#geniustable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.product-categories.datatables') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'parent_category', name: 'parent_category' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ]
            });
     
    </script>
@endsection
