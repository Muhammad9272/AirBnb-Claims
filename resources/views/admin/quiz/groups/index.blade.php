@extends('admin.layouts.master')
@section('title')Quiz Groups @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    table.dataTable td, table.dataTable th{
        word-wrap: break-word;
    }
        #geniustable_processing{
        background-color: #2425812b;
        position:fixed;
    }
</style>
@endsection
@section('content')
@component('components.breadcrumb')

@slot('li_1') <a href="{{ route('admin.quiz.group.index') }}"> Quiz Groups</a> @endslot
@slot('title')Groups @endslot
@endcomponent


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header ">
                
               

                <div class="row ms-2">
                    <div class="col-lg-12 col-md-4 m-auto text-end">
                         <a href="{{ route('admin.quiz.group.create') }}" class="btn btn-primary waves-effect waves-light"><i class="ri-add-line align-bottom me-1"></i>Quiz Groups</a>
                    </div>
                </div>

            </div>
            
            {{-- <hr> --}}

            <div class="card-body">
                <table id="geniustable" class="table  dt-responsive align-middle" style="width:100%">
                    <thead>
                        <tr>                            
                            <th>
                                <div class=" ">
                                    <label class="form-check-label" >
                                     #
                                    </label>
                                </div>
                            </th>                          
                            <th data-ordering="false">Name</th>

                            <th>Company</th>
                            
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                   
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->



<!-- Delete modal -->
<div class="modal fade" id="confirm-delete" aria-hidden="true" aria-labelledby="..." tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-3">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                    colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                </lord-icon>
                <div class="{{-- mt-4 pt-4 --}}">
                    <h4>Uh oh, You are about to delete this Data!</h4>
                    <p class="text-muted"> Do you want to proceed?</p>
                    <!-- Toogle to second dialog -->
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                             <a href="" class="btn btn-danger btn-ok" >
                                Delete
                            </a>                           
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete modal ends-->



@endsection
@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- DATA TABLE --}}

    <script type="text/javascript">
        var url='{!! route('admin.quiz.group.datatables') !!}';
        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: url,
               columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name'},

                        { data: 'company_id', name: 'company_id'},
                        { data: 'price', name: 'price'},

                        { data: 'status', searchable: false, orderable: false},
                        { data: 'action', searchable: false, orderable: false }
                     ],
                language : {
                   
                },
                drawCallback: function (oSettings) {
                   
                 },
            });




</script>


@endsection
