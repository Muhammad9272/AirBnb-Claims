@extends('admin.layouts.master')

@section('title')
    Pending Submissions
@endsection

@section('css')
<!-- DataTables -->
<link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pending Submissions</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bit-submissions.index') }}">Bit Submissions</a></li>
                        <li class="breadcrumb-item active">Pending</li>
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
                        <h4 class="card-title mb-0">Pending Submissions</h4>
                        <div>
                            <a href="{{ route('admin.bit-submissions.index') }}" class="btn btn-secondary waves-effect waves-light me-2">
                                <i class="ri-list-check align-middle me-1"></i> All Submissions
                            </a>
                            <a href="{{ route('admin.bit-tasks.index') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ri-list-check align-middle me-1"></i> Manage Tasks
                            </a>
                        </div>
                    </div>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Task</th>
                                <th>Value</th>
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
<!-- Required datatable js -->
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.bit-submissions.datatables", ["status" => "pending"]) }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user', name: 'user' },
                { data: 'task', name: 'task' },
                { data: 'value', name: 'value' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
