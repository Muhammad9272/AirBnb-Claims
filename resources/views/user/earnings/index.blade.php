@extends('user.layouts.master')
@section('meta_title')Earnings  @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
 <link href="{{ URL::asset('assets/libs/shepherd.js/shepherd.js.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"> Earnings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li> 
                                       
                    <li class="breadcrumb-item active">Earnings</li>
                    
                </ol>
            </div>

        </div>
    </div>
</div>    

<div id="EarningsCardd">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-2" >
                <div class="card-body text-center">
                    @if(Auth::user()->stripe_connect_key && Auth::user()->stripe_connect_status=='completed')
                    <h4 class="mb-4">Your available balance is 
                        {{  Auth::user()->userbalance(1)}}.
                        @if(Auth::user()->userbalance()>0)Click the button below to withdraw
                        @endif
                    </h4>
                    <div>
                        @if(Auth::user()->userbalance()>0)
                        <a href="{{ route('user.sendpayment') }}" class="btn btn-primary waves-effect waves-light">
                             <i class="ri-bank-card-fill align-middle me-1"></i>
                            <span> Withdraw </span>
                        </a>
                        @else
                            <a href="javascript:;" class="btn btn-primary waves-effect waves-light disabled">
                                 <i class="ri-bank-card-fill align-middle me-1"></i>
                                <span> Withdraw </span>
                            </a>
                        @endif
                        {{-- <a href="" class="btn btn-primary waves-effect waves-light">
                            <i class="ri-eye-fill align-middle me-1"></i>
                            <span> View Transaction History </span>
                        </a> --}}
                    </div>
                    @else
                     <h4>Add Your payment details by clicking on the below button. </h4>
                      <a href="{{ route('user.addpayment.gateway') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ri-add-line align-middle me-1"></i>
                            Add Payment Gateway 
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header end-t-end">
                    <h5 class="card-title mb-0">Earnings </h5>
                    {{-- <a href="" class="btn btn-primary waves-effect waves-light">Add Listing</a> --}}
                </div>
                <div class="card-body">
                    @include('user.includes.alerts')
                    <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                
                                <th data-ordering="false">ID</th>                           
                                <th data-ordering="false">Amount</th>
                                {{-- <th>Price</th>                           
                                <th>Create Date</th>
                                <th>Status</th> --}}
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                       
                    </table>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
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

@php
$tourSteps = [
 
    'title' => 'Welcome Back!',
    'text' => 'It is financially rewarded if you share or recommend our services to others. Please check the referral rule in your profile page.',
    'element' => '#EarningsCardd',
    'sidebarElement' => '#earningsSidebar',
    'position' => 'top',
    'prev_button'=> route('user.mylearning.index'),
    'finish_button'=> route('user.dashboard'),
    // 'next_button'=> route('user.earnings'),
    'buttons' => [
      // Specify the buttons for this step
    ],
 
  // Add more steps as needed...
];
@endphp
@include('user.includes.tour', ['step' => $tourSteps])

@endsection
@section('script')

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('user.earnings.datatables') }}',
               columns: [
                        // { data: 'id', name: 'id'},
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'earning_net_user', name: 'earning_net_user'},
                        { data: 'status', searchable: false, orderable: false},
                        { data: 'date', searchable: false, orderable: false }
                     ],
                language : {
                   
                }
            });
                      

{{-- DATA TABLE ENDS--}}


</script>


@endsection
