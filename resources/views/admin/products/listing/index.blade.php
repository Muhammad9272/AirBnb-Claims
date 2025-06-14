@extends('admin.layouts.master')
@section('title', 'Products')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        table.dataTable td {
            vertical-align: middle;
        }
        .droplinks {
            border-radius: 3px;
            background: #f3f3f3;
            width: 100px;
            padding: 2px 0;
        }
        #geniustable_processing {
            background-color: #2425812b;
            position: fixed;
        }
        .action-list {
            display: flex;
            gap: 6px;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Products
        @endslot
        @slot('title')
            Product List
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Products</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Product
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
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteConfirm">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
       var table= $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.product.datatables") }}',
            columns: [
                {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                {
                    data: 'photo', 
                    name: 'photo', 
                    searchable: false, 
                    orderable: false,
                    
                },
                {data: 'name', name: 'name'},
                {data: 'category', name: 'category'},
                {
                    data: 'price', 
                    name: 'price',
                   
                },
                {data: 'quantity', name: 'quantity'},
                {
                    data: 'status', 
                    name: 'status',
                    
                },
                {
                    data: 'action', 
                    name: 'action', 
                    searchable: false, 
                    orderable: false
                }
            ]
        });

    $(function() {
    
        // Delete Handler
        let deleteId = null;
        $(document).on('click', '.delete-product', function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        $('#deleteConfirm').on('click', function() {
            if (deleteId) {
                $.get("{{ url('management0712/product/delete') }}/" + deleteId, function(data) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                });
            }
        });

       
    });
</script>
@endsection
