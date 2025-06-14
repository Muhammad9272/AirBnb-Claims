@extends('admin.layouts.master')
@section('title', 'Blog Posts')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Blog Posts
        @endslot
        @slot('title')
            Blog List
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Posts</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Post
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
                                <th>Title</th>
                                <th>Category</th>
                                {{-- <th>Featured</th> --}}
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
    var table = $('#geniustable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.blog.datatables") }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'photo', name: 'photo'},
            {data: 'title', name: 'title'},
            {data: 'category', name: 'category'},
            {{-- {data: 'featured', name: 'featured'}, --}}
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
        ]
    });

     // Handle delete action
    $(document).on('click', '.delete-item', function() {
        if (confirm('Are you sure you want to delete this category?')) {
            $.get($(this).data('href'), function(data) {
                table.ajax.reload();
                
            });
        }
    });
</script>
@endsection
