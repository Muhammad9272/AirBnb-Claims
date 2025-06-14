@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Edit Tutor Management 
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.product.index') }}">  Tutor Management  </a>
        @endslot
        @slot('title')
            Edit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">


                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.tutor.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('admin.tutors.partials.inputs')
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            </div>
                        </form>
                        <!--end row-->
                    </div>
                
            
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    {{-- Tutors Schedule --}}
    @if(isset($data))
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Working hours</h5>
                    <p class="text-muted">See the days & times  this staff member is available for bookings.</p>
                </div>
                <div class="card-body"> 
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Days</th>
                                <th scope="col">Hours</th>
                                <th scope="col">Repeats</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php
                          $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                          @endphp
                          @foreach ($daysOfWeek as $day)
                            @php
                                $dayNameLowercase = strtolower($day); 
                                $schedule = App\Models\Schedule::where('user_id', $data->id)->where('day_of_week',$dayNameLowercase)->first();
                            @endphp
                            <tr>
                                <th scope="row"><a href="#" class="fw-semibold">{{$day}}</a></th>
                                <td>
                                    @if($schedule)
                                      {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                    @endif  
                                </td>
                                <td>{{ $schedule->repeat_interval ?? null}}</td>
                                <td>
                                    <div class="hstack gap-3 flex-wrap">
                                        {{-- <a href="javascript:void(0);" class="link-success fs-15"><i class="ri-edit-2-line"></i></a> --}}
                                        @if($schedule)
                                        <a href="javascript:;" data-bs-target="#ScheduleActionModal" data-bs-toggle="modal" class="btn btn-soft-info editdata_in_modal" data-selected-day="{{ $dayNameLowercase}}" data-href="{{route('admin.tutor.schedule.edit',$schedule->id)}}"  > <i class="ri-edit-2-line"></i> </a>
                                        <a href="{{route('admin.tutor.schedule.delete',$schedule->id)}}" class="link-danger fs-15"><i class="ri-delete-bin-line"></i></a>
                                        @else
                                        <a href="javascript:;" data-bs-target="#ScheduleActionModal" data-selected-day="{{ $dayNameLowercase}}" data-bs-toggle="modal" class="btn btn-soft-info editdata_in_modal" data-href="{{route('admin.tutor.schedule.edit')}}"  > <i class="ri-add-line"></i> </a>
                                        @endif

                                        
                                    </div>
                                </td>
                            </tr>
                           @endforeach

                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Schedule Model-->
    <div class="modal fade  modal-lg" id="ScheduleActionModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Bulk Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" id="close-modal"></button>
                </div> --}}
                
                <form action="{{ route('admin.tutor.schedule.store',$data->id) }}" method="post" enctype="multipart/form-data" id="ItemsScheduleForm" class="p-2">
                    @csrf
                   
                    @include('includes.alerts')

                    <div class="modal-body dynamic__content">
                         {{-- @include('admin.tutors.partials.schedule') --}}
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            
                            <button type="submit" class="btn btn-success submit-btn"
                                id="edit-btn">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Schedule Model ends-->

@endsection
@section('script')
<script type='text/javascript' src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>


<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>


<script>


$(document).on('click','.editdata_in_modal',function(){

        var selectedday = $(this).attr('data-selected-day');
        var url = $(this).attr('data-href');
         $.ajax({
            url: url,
            type: 'GET',
            data: { selectedday: selectedday },
            dataType: 'html',
            success: function (data) {
                $('#ScheduleActionModal .dynamic__content').html(data);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

        
});
// $('#ScheduleActionModal .dynamic__content').html('').load($(this).attr('data-href'),function(response, status, xhr){
//           if(status == "success")
//           {
//             $('.submit-loader').hide();
//           }

//         });

 // $(document).on('change', '#appointment_date', function() {
 //        var date = $(this).val();
 //        var tutor_id = {{ $data->id }};
 //        var url = "{{ route('user.get.ScheduleForDate', ':date') }}".replace(':date', date);
 //        $.ajax({
 //            url: url,
 //            type: 'GET',
 //            data: { tutor_id: tutor_id },
 //            dataType: 'html',
 //            success: function (data) {
 //                $('#appointment-slots').html(data);
 //            },
 //            error: function (xhr, status, error) {
 //                console.log(error);
 //            }
 //        });
        
 //    });

</script>

@endsection
