@extends('admin.layouts.master')
@section('title', 'Nostalgia Items')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        table.dataTable td {
            vertical-align: middle;
        }
        .droplinks {
            border-radius: 3px;
            background: #f3f3f3;
            width: 100px;
            padding: 2px 0;
        }
        #geniustable_processing {
            background-color: #2425812b;
            position: fixed;
        }
        .action-list {
            display: flex;
            gap: 6px;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.nostalgia.item.index') }}">Nostalgia Items</a>
        @endslot
        @slot('title')
            Items Management
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Nostalgia Items</h4>
                            <p class="text-muted mb-0">Manage your nostalgic collection</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.nostalgia.item.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Item
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="geniustable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Release Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.confirm-modal')
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.nostalgia.item.datatables") }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'photo', name: 'photo', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'category_info', name: 'category_info'},
            {data: 'release_year', name: 'release_year'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    // Delete confirmation handler
    $(document).on('click', '.delete-item', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this item?')) {
            $.get("{{ url('management0712/nostalgia/item/delete') }}/" + id, function(data) {
                if (data.success) {
                    table.ajax.reload();
                }
            });
        }
    });

    // Status change handler
    $(document).on('change', '.droplinks', function() {
        var url = $(this).val();
        var warning = $(this).find(':selected').data('val') == 1 ? 
            'Activate this item?' : 
            'Deactivate this item?';
        
        if (confirm(warning)) {
            $.get(url, function(data) {
                if (data.success) {
                    table.ajax.reload();
                }
            });
        }
    });
</script>
@endsection
