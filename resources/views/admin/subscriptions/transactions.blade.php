@extends('admin.layouts.master')
@section('title') User Subscriptions @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.subplan.index') }}">Subscriptions</a> @endslot
@slot('title') User Transactions @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Subscription Transactions</h5>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-2-line"></i> Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('admin.subscriptions.transactions', ['status' => 'all']) }}">All</a>
                            <a class="dropdown-item" href="{{ route('admin.subscriptions.transactions', ['status' => 'active']) }}">Active</a>
                            <a class="dropdown-item" href="{{ route('admin.subscriptions.transactions', ['status' => 'canceled']) }}">Canceled</a>
                            <a class="dropdown-item" href="{{ route('admin.subscriptions.transactions', ['status' => 'problem']) }}">Problem</a>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-soft-primary btn-sm" id="export-csv">
                        <i class="ri-file-download-line"></i> Export CSV
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="subscriptions-table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Detail Modal -->
<div class="modal fade" id="subscription-detail-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="subscription-detail-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#subscriptions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.subscriptions.transactions.datatables", ["status" => request("status", "all")]) }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user', name: 'user', orderable: false },
                { data: 'plan', name: 'plan', orderable: false },
                { data: 'price', name: 'price' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'ends_at', name: 'ends_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });
        
        // Export to CSV
        $('#export-csv').on('click', function() {
            window.location.href = '{{ route("admin.subscriptions.transactions.export") }}' + 
                window.location.search;
        });
        
        // View subscription details
        $(document).on('click', '.view-subscription', function() {
            var id = $(this).data('id');
            
            $('#subscription-detail-modal').modal('show');
            
            $.ajax({
                url: `/management0712/subscriptions/${id}/detail`,
                type: 'GET',
                success: function(response) {
                    $('#subscription-detail-content').html(response);
                },
                error: function(xhr) {
                    $('#subscription-detail-content').html(`
                        <div class="alert alert-danger">
                            Error loading subscription details: ${xhr.responseJSON?.message || 'Unknown error'}
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endsection
