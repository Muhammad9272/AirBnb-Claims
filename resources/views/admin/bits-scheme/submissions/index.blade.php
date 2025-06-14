@extends('admin.layouts.master')

@section('title')
    Bit Submissions
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
                <h4 class="mb-sm-0">Bit Submissions</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bit Submissions</li>
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
                        <h4 class="card-title mb-0">All Submissions</h4>
                        <div>
                            {{-- <a href="{{ route('admin.bit-submissions.pending') }}" class="btn btn-warning waves-effect waves-light me-2">
                                <i class="ri-time-line align-middle me-1"></i> Pending Submissions
                            </a> --}}
                            <a href="{{ route('admin.bit-tasks.index') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ri-list-check align-middle me-1"></i> Manage Tasks
                            </a>
                        </div>
                    </div>

                   <table id="geniustable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Task</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Date</th>
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
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>

        var table = $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.bit-submissions.datatables") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

                { data: 'user', name: 'user' },
                { data: 'task', name: 'task' },
                { data: 'value', name: 'value' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
   
</script>
@endsection
