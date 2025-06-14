@extends('admin.layouts.master')

@section('title')
    Orders
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
                <h4 class="mb-sm-0">Orders</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Orders List</h4>
                        
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="filterDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter: <span id="currentFilterLabel">All</span>
                        </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item order-filter" href="#" data-status="all">All Orders</a>
                                <a class="dropdown-item order-filter" href="#" data-status="pending">Pending</a>
                                <a class="dropdown-item order-filter" href="#" data-status="processing">Processing</a>
                                <a class="dropdown-item order-filter" href="#" data-status="completed">Completed</a>
                                <a class="dropdown-item order-filter" href="#" data-status="cancelled">Cancelled</a>
                                <a class="dropdown-item order-filter" href="#" data-status="declined">Declined</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="geniustable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Order Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table data will be loaded by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this order? This action cannot be undone.</p>
                    <p class="text-warning"><small>Note: If bits were used for this order, they will be refunded to the user.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="delete-btn" class="btn btn-danger">Delete Order</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    
       {{--  var table = $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.orders.datatables') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'order_number', name: 'order_number'},
                {data: 'date', name: 'date'},
                {data: 'user', name: 'user'},
                {data: 'total', name: 'total'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'order_status', name: 'order_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[0, 'desc']]
        }); --}}

    let selectedStatus = 'all';

    var table = $('#geniustable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.orders.datatables') }}",
            data: function(d) {
                d.status = selectedStatus;
            }
        },
        columns: [
             {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'order_number', name: 'order_number'},
            {data: 'date', name: 'date'},
            {data: 'user', name: 'user'},
            {data: 'total', name: 'total'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'order_status', name: 'order_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']]
    });

    $('.order-filter').on('click', function(e) {
        e.preventDefault();

        selectedStatus = $(this).data('status'); // e.g. 'pending', 'completed'
        let label = $(this).text().trim();       // e.g. 'Pending', 'Completed'

        $('#currentFilterLabel').text(label);    // update button text
        table.ajax.reload();                     // reload with new backend filter
    });



    $(document).ready(function() {  
        // Order Status Change
        $(document).on('change', '.order-status-select', function() {
            var status = $(this).val();
            var orderId = $(this).data('id');
            
            // Confirmation for cancellation
            if (status === 'cancelled' && !confirm('Are you sure you want to cancel this order? This will refund any bits used.')) {
                $(this).val($(this).find('option[selected]').val());
                return;
            }
            
            $.ajax({
                url: '{{ route('admin.orders.update-status') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Order status updated successfully');
                    } else {
                        toastr.error('Failed to update order status');
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating status');
                }
            });
        });
       
        // Filter by status
       {{--  $('.order-filter').on('click', function(e) {
            e.preventDefault();
            const status = $(this).data('status');
            
            if (status === 'all') {
                table.column(6).search('').draw();
            } else {
                table.column(6).search(status).draw();
            }
        }); --}}
        
        // Delete modal
        $('#deleteModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const href = button.data('href');
            $('#delete-btn').attr('href', href);
        });
    });
</script>
@endsection
