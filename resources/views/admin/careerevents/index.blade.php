@extends('admin.layouts.master')
@section('title')Career Event @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
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

@slot('li_1') <a href="{{ route('admin.career.event.index') }}"> Career Event</a> @endslot
@slot('title')Management @endslot
@endcomponent


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header ">
                

                <div class="row align-items-center gy-3">
                    <div class="col-sm">
                            <button type="button" data-bs-target="#BulkActionModal" data-bs-toggle="modal" class="btn btn-soft-info" id="bulk-actions-btn" disabled> Bulk Action (<span id="selectedrows">0</span>)</button> 
                    </div>

                    <div class="col-sm-auto">
                          <a href="{{ route('admin.career.event.create') }}" class="btn btn-primary waves-effect waves-light"><i class="ri-add-line align-bottom me-1"></i>Career Event</a>
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
                                    <input class="form-check-input"  type="checkbox" id="master_select">
                                    <label class="form-check-label" for="master_select">
                                     #
                                    </label>
                                </div>
                            </th>                          
                            <th data-ordering="false">Events</th>

                            <th>Date & Time</th>
                            
                            <th>Location</th>
                            <th>Host</th>
                            <th>Permission</th>
                            <th>Reg Participants</th>
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


@include('admin.careerevents.partials.bulk-edit-modal')
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
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>


<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- DATA TABLE --}}

    <script type="text/javascript">
        var url='{!! route('admin.career.event.datatables') !!}';
        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: url,
               columns: [
                        // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'select', name: 'select', orderable: false, searchable: false },
                        { data: 'name', name: 'name'},

                        { data: 'event_date_time', name: 'event_date_time'},
                        { data: 'meeting_id', name: 'meeting_id'},
                        { data: 'host_name', name: 'host_name'},
                        { data: 'permission', name: 'permission'},
                        { data: 'registered_users', name: 'registered_users'},

                        { data: 'status', searchable: false, orderable: false},
                        { data: 'action', searchable: false, orderable: false }
                     ],
                language : {
                   
                },
                drawCallback: function (oSettings) {
                        $("#master_select").prop('checked', false); 
                        $('#bulk-actions-btn').attr('disabled',true);
                        $('#selectedrows').text('0');


                        $('.sub_select').on('click', function(e) {
                          var numberOfChecked = $('.sub_select:checked').length;
                          var totalCheckboxes = $('.sub_select:checkbox').length;
                          $('#selectedrows').text(numberOfChecked);
                          if(numberOfChecked>0){
                            $('#bulk-actions-btn').attr('disabled',false);
                          }
                          else{
                             $('#bulk-actions-btn').attr('disabled',true);
                          }

                          if(numberOfChecked==totalCheckboxes){
                            $("#master_select").prop('checked', true); 
                          }
                          else{
                            $("#master_select").prop('checked', false); 
                          }

                        });


                 },
            });

        $('#master_select').on('click', function(e) {   
             if($(this).is(':checked',true))  
             {
                $(".sub_select").prop('checked', true);
                var numberOfChecked = $('.sub_select:checked').length;
                $('#selectedrows').text(numberOfChecked);
                if(numberOfChecked>0){

                    $('#bulk-actions-btn').attr('disabled',false);
                }
                else{
                    $('#bulk-actions-btn').attr('disabled',true);
                 
                }

             } else {  
                $(".sub_select").prop('checked',false);  
                var numberOfChecked = $('.sub_select:checked').length;
                $('#selectedrows').text(numberOfChecked);
                if(numberOfChecked>0){
                   $('#bulk-actions-btn').attr('disabled',false);
                }
                else{
                  $('#bulk-actions-btn').attr('disabled',true);
                }
             }  
        });
    </script>


@endsection
