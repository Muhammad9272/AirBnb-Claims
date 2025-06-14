@extends('admin.layouts.master')
@section('title') Stripe Webhook Management @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    .webhook-status {
        width: 12px;
        height: 12px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 5px;
    }
    .status-enabled {
        background-color: #0ab39c;
    }
    .status-disabled {
        background-color: #f06548;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.subplan.index') }}">Subscriptions</a> @endslot
@slot('title') Stripe Webhooks @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header end-t-end">
                <h5 class="card-title mb-0">Stripe Webhook Endpoints</h5>
                <a href="{{ route('admin.stripe.webhooks.create') }}" class="btn btn-primary waves-effect waves-light">
                    <i class="ri-add-line align-middle me-1"></i> Add New Webhook
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Webhook endpoints enable Stripe to push real-time event notifications to your application when events occur, 
                    such as successful payments or subscription updates.
                </p>
                
                <table id="webhooks-datatable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Events</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-webhook-modal" tabindex="-1" aria-labelledby="delete-webhook-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-webhook-modal-label">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mt-2">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this webhook endpoint? This cannot be undone.</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-webhook-confirm">Delete</button>
                </div>
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
        // Initialize DataTable
        var table = $('#webhooks-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.stripe.webhooks.datatables') }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                { data: 'url', name: 'url' },
                { data: 'status', name: 'status' },
                { data: 'events', name: 'events' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });
        
        // Handle webhook delete
        let webhookToDelete = null;
        
        $(document).on('click', '.delete-webhook', function() {
            webhookToDelete = $(this).data('id');
            $('#delete-webhook-modal').modal('show');
        });
        
        $('#delete-webhook-confirm').on('click', function() {
            if (webhookToDelete) {
                $.ajax({
                    url: `/management0712/stripe/webhooks/${webhookToDelete}/destroy`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#delete-webhook-modal').modal('hide');
                            table.ajax.reload();
                            toastr.success('Webhook endpoint deleted successfully!');
                        } else {
                            toastr.error('Failed to delete webhook endpoint.');
                        }
                    },
                    error: function(error) {
                        toastr.error('An error occurred while deleting webhook endpoint.');
                    }
                });
            }
        });
    });
</script>
@endsection
