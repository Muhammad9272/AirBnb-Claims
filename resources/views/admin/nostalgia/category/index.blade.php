@extends('admin.layouts.master')
@section('title', 'Nostalgia Categories')

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

        .level-badge {
            min-width: 100px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.nostalgia.category.index') }}">Nostalgia Categories</a>
        @endslot
        @slot('title')
            Category Management
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Categories</h4>
                            <p class="text-muted mb-0">Manage main, sub, and child categories</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.nostalgia.category.create') }}"
                                class="btn btn-primary waves-effect waves-light">
                                <i class="fas fa-plus me-2"></i>Add Category
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="geniustable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Parent</th>
                                
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

<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.nostalgia.category.datatables") }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'level_name', name: 'level_name'},
            {data: 'parent_name', name: 'parent_name'},
            
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
        
    });


</script>
@endsection
