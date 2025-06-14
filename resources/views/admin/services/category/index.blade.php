@extends('admin.layouts.master')
@section('title', 'Service Categories')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style>
        .category-image { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Service Categories
        @endslot
        @slot('title')
            Category List
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Categories</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.service.category.create') }}" class="btn btn-primary">
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
                                <th>Photo</th>
                                <th>Name</th>
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
        ajax: '{{ route("admin.service.category.datatables") }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'photo', name: 'photo', orderable: false},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    // Handle delete action
    $(document).on('click', '.delete-item', function() {
        if (confirm('Are you sure you want to delete this category?')) {
            $.get($(this).data('href'), function(data) {
                table.ajax.reload();
                alert('Category deleted successfully!');
            });
        }
    });
</script>
@endsection
