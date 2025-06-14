@extends('admin.layouts.master')

@section('title')
    Bit Tasks
@endsection

@section('css')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Bit Tasks</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bit Tasks</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title mb-0">Bit Tasks List</h4>
                        <a href="{{ route('admin.bit-tasks.create') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ri-add-line align-middle me-1"></i> Add New Task
                        </a>
                    </div>

                    <table id="geniustable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Bit Value</th>
                                <th>Status</th>
                                <th>Submissions</th>
                                <th>Pending</th>
                                {{-- <th>Created</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this task? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form id="delete-form" action="" method="GET">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>

        // Initialize DataTable
        var table = $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.bit-tasks.datatables") }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'title', name: 'title' },
                { data: 'bit_value', name: 'bit_value' },
                { data: 'status', name: 'status' },
                { data: 'submissions_count', name: 'submissions_count' },
                { data: 'pending_count', name: 'pending_count' },
                {{-- { data: 'created_at', name: 'created_at' }, --}}
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Delete confirmation
        $(document).on('click', '.delete-item', function() {
            var href = $(this).data('href');
            $('#delete-form').attr('action', href);
        });

</script>
@endsection
