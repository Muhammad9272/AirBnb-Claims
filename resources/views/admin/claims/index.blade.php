@extends('admin.layouts.master')
@section('title') Claims Management @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('title') Claims Management @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 flex-grow-1">Claims Overview</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Claims</p>
                                        <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="ri-file-list-3-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Pending</p>
                                        <h4 class="mb-0">{{ $statistics['pending'] }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-info">
                                        <span class="avatar-title">
                                            <i class="ri-time-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Approved</p>
                                        <h4 class="mb-0">{{ $statistics['approved'] }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="ri-check-double-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium mb-2">Total Amount</p>
                                        <h4 class="mb-0">${{ number_format($statistics['amount_claimed'], 2) }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-warning">
                                        <span class="avatar-title">
                                            <i class="ri-money-dollar-circle-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Claims List</h5>
                <div>
                    <a href="{{ route('admin.claims.export', ['status' => $status]) }}" class="btn btn-success btn-sm">
                        <i class="ri-file-download-line align-bottom me-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.claims.index', ['status' => 'all']) }}" class="btn {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                            <a href="{{ route('admin.claims.index', ['status' => 'pending']) }}" class="btn {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
                            <a href="{{ route('admin.claims.index', ['status' => 'under_review']) }}" class="btn {{ $status == 'under_review' ? 'btn-primary' : 'btn-outline-primary' }}">Under Review</a>
                            <a href="{{ route('admin.claims.index', ['status' => 'approved']) }}" class="btn {{ $status == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">Approved</a>
                            <a href="{{ route('admin.claims.index', ['status' => 'rejected']) }}" class="btn {{ $status == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">Rejected</a>
                        </div>
                    </div>
                </div>
                
                <table id="claims-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Claim #</th>
                            <th>User</th>
                            <th>Title</th>
                            <th>Amount Requested</th>
                            <th>Amount Approved</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable content will be loaded here -->
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
    $(document).ready(function() {
        var table = $('#claims-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.claims.datatables') }}",
                data: function (d) {
                    d.status = "{{ $status }}";
                }
            },
            columns: [
                { data: 'claim_number', name: 'claim_number' },
                { data: 'user_info', name: 'user_info' },
                { data: 'title', name: 'title' },
                { data: 'amount_requested', name: 'amount_requested' },
                { data: 'amount_approved', name: 'amount_approved' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data, type, row) {
                        var badgeClass = '';
                        switch(data) {
                            case 'pending':
                                badgeClass = 'badge bg-info';
                                break;
                            case 'under_review':
                                badgeClass = 'badge bg-primary';
                                break;
                            case 'approved':
                                badgeClass = 'badge bg-success';
                                break;
                            case 'rejected':
                                badgeClass = 'badge bg-danger';
                                break;
                            default:
                                badgeClass = 'badge bg-secondary';
                        }
                        
                        return '<span class="' + badgeClass + '">' + data.replace('_', ' ').charAt(0).toUpperCase() + data.replace('_', ' ').slice(1) + '</span>';
                    }
                },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[6, 'desc']]
        });
    });
</script>
@endsection
