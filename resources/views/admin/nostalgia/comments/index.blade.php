@extends('admin.layouts.master')
@section('title', 'Products')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') <a href="{{ route('admin.product.index') }}">Products</a> @endslot
        @slot('title') Product List @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row ms-2">
                        <div class="col-lg-12 col-md-4 m-auto text-end">
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary waves-effect waves-light">Add Product</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="geniustable" class="table dt-responsive nowrap table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Product Details</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="https://images.unsplash.com/photo-1621259182978-fbf93132d53d?q=80&w=1632&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded" width="50" height="50" alt="Xbox Series X">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1">Xbox Series X</h6>
                                        <small class="text-muted">Type: Console</small>
                                        <small class="text-muted">SKU: XBX-001</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Microsoft</span>
                                        <small class="text-muted">Xbox Series X</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>$499.99</span>
                                        <small class="text-danger">$449.99</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">In Stock</span>
                                    <small class="d-block">15 units</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-warning"><i class="ri-pencil-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-danger"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <img src="https://images.unsplash.com/photo-1606144042614-b2417e99c4e3" class="rounded" width="50" height="50" alt="PS5">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1">PlayStation 5 Digital Edition</h6>
                                        <small class="text-muted">Type: Console</small>
                                        <small class="text-muted">SKU: PS5-002</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Sony</span>
                                        <small class="text-muted">PS5</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>$399.99</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-danger">Out of Stock</span>
                                    <small class="d-block">0 units</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning">Out of Stock</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-warning"><i class="ri-pencil-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-danger"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <img src="https://images.unsplash.com/photo-1680007966627-d49ae18dbbae?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded" width="50" height="50" alt="Switch">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1">Nintendo Switch OLED</h6>
                                        <small class="text-muted">Type: Console</small>
                                        <small class="text-muted">SKU: NSW-003</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Nintendo</span>
                                        <small class="text-muted">Switch</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>$349.99</span>
                                        <small class="text-danger">$329.99</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">In Stock</span>
                                    <small class="d-block">8 units</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-warning"><i class="ri-pencil-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-danger"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <img src="https://images.unsplash.com/photo-1600080972464-8e5f35f63d08" class="rounded" width="50" height="50" alt="Xbox Controller">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1">Xbox Wireless Controller</h6>
                                        <small class="text-muted">Type: Accessory</small>
                                        <small class="text-muted">SKU: XBC-004</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Microsoft</span>
                                        <small class="text-muted">Xbox Series X</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>$59.99</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">In Stock</span>
                                    <small class="d-block">25 units</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-warning"><i class="ri-pencil-line"></i></a>
                                        <a href="#" class="btn btn-sm btn-soft-danger"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="confirm-delete" aria-hidden="true" aria-labelledby="..." tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-3">
                    <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px"></lord-icon>
                    <h4>Uh oh, You are about to delete this Data!</h4>
                    <p class="text-muted">Do you want to proceed?</p>
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <a href="" class="btn btn-danger btn-ok">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

@endsection
