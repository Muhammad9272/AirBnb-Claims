@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
  Career Events Calender
@endsection
@section('css')
 <style type="text/css">
    .AppointmentsCalender .fc-event-title.fc-sticky{
        font-size: unset;
        position: fixed;
     }
     .fc-prev-button:before,.fc-next-button:before{
        content:unset !important;
     }
 </style>
 <link href="{{ URL::asset('/assets/libs/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.dashboard') }}">   Career Events Calender </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent



    <div class="row">
        <div class="col-lg-12 ">
            <div class="row">
                <div class="col-xl-3">
                    <div>
                        <input type="text" id="selectCCDate" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                            data-deafult-date="today" data-inline-date="true">                       
                    </div> 
                </div> <!-- end col-->
                <div class="col-xl-9">
                    <div class="card card-h-100">
                        <div class="card-body AppointmentsCalender">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!--end col-->
    </div>
    <!--end row-->



    <div style='clear:both'></div>

           




@endsection
@section('script')
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ URL::asset('assets/libs/fullcalendar/fullcalendar.min.js') }}"></script>


{{-- <script src="{{ URL::asset('assets/js/pages/calendar.init.js') }}"></script> --}}

<script>
        document.addEventListener('DOMContentLoaded', function () {
           

                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'timeGridDay', // daily view by default
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'//dayGridMonth
                        },
                        allDaySlot: false, // hide the all-day section
                        selectable: false,
                        events: {!! json_encode($formattedEvents) !!}, // Pass events data from Laravel controller to JavaScript
                });
                calendar.render();

                // update the calendar view when a date is selected
                $(document).on('change', '#selectCCDate', function() {
                    var selectedDate = $(this).val();
                    var formattedDate = moment(selectedDate, 'D MMM, YYYY').format('YYYY-MM-DD');
                    calendar.gotoDate(formattedDate);
                    calendar.changeView('timeGridDay');
                });
        });
</script>

@endsection
