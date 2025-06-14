@extends('admin.layouts.master')

@section('title')
    Product Reviews
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
                <h4 class="mb-sm-0">Product Reviews</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Product Reviews</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Reviews</h5>
                    <div>
                        <button class="btn btn-success btn-sm" id="bulk-approve">
                            <i class="ri-check-line me-1"></i> Approve Selected
                        </button>
                        <button class="btn btn-danger btn-sm" id="bulk-reject">
                            <i class="ri-close-line me-1"></i> Reject Selected
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="reviews-table" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be filled by DataTables -->
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
                    <h5 class="modal-title">Delete Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this review? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="delete-btn" class="btn btn-danger">Delete</a>
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
        var table = $('#reviews-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.reviews.datatables') }}",
           columns: [
            {
                data: 'id',
                name: 'id',
                render: function(data) {
                    return '<input type="checkbox" class="review-checkbox" value="' + data + '">';
                },
                orderable: false,
                searchable: false
            },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'product', name: 'product' },
            { data: 'customer', name: 'customer' },
            { data: 'rating', name: 'rating' },
            { data: 'status', name: 'status' },
            { data: 'verified', name: 'verified' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],

            order: [[1, 'desc']]
        });
        
        // Select all functionality
        $('#select-all').on('click', function() {
            $('.review-checkbox').prop('checked', this.checked);
        });
        
        // Bulk approve
        $('#bulk-approve').on('click', function() {
            var ids = [];
            $('.review-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                toastr.info('Please select at least one review to approve');
                return;
            }
            
            if (confirm('Are you sure you want to approve ' + ids.length + ' reviews?')) {
                $.ajax({
                    url: "{{ route('admin.reviews.bulk-approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success('Reviews approved successfully');
                    }
                });
            }
        });
        
        // Bulk reject
        $('#bulk-reject').on('click', function() {
            var ids = [];
            $('.review-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                toastr.info('Please select at least one review to reject');
                return;
            }
            
            if (confirm('Are you sure you want to reject and delete ' + ids.length + ' reviews? This action cannot be undone.')) {
                $.ajax({
                    url: "{{ route('admin.reviews.bulk-reject') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success('Reviews rejected and deleted successfully');
                    }
                });
            }
        });
        
        // Delete modal
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var href = button.data('href');
            $('#delete-btn').attr('href', href);
        });
    });
</script>
@endsection
