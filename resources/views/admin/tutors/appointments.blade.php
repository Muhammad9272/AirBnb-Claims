@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Tutor Appointments
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
              <a href="{{ route('admin.dashboard') }}"> You Appointments Calender </a>
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

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header p-3 bg-soft-info">
                            <h5 class="modal-title" id="modal-title">Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                               <div id="appointment-content">
                                    @include('admin.tutors.partials.add-appointment-form')
                               </div>                                 
                                <div class="hstack gap-2 justify-content-end showones">
                                    <a data-href="{{route('admin.tutor.appointment.delete',$tutor_id)}}" data-id="" class="btn btn-sm btn-soft-danger" id="delete-event-btn" onclick="deleteAppointment(this)" ><i class="ri-close-line align-bottom"></i> Delete</button>

                                    <a href="#" class="btn btn-sm btn-soft-success" id="add-event-btn" onclick="addAppointment(this)" role="button">Create Appointment</a>

                                    {{-- <button type="button" class="btn btn-success" id="btn-create-appointment" addAppointment>Create Appontment</button> --}}
                                </div>
                           
                        </div>
                    </div> <!-- end modal-content-->
                </div> <!-- end modal dialog-->
            </div> <!-- end modal-->





@endsection
@section('script')
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ URL::asset('assets/libs/fullcalendar/fullcalendar.min.js') }}"></script>


{{-- <script src="{{ URL::asset('assets/js/pages/calendar.init.js') }}"></script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay', // daily view by default
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'//dayGridMonth
            },
            allDaySlot: false, // hide the all-day section
            selectable: true,
           select: function(info) {
                // handle selection of a time slot
                var selectedDate = info.startStr; // selected date and time
                var startTime = moment(info.start).format('HH:mm:ss'); // selected start time
                var endTime = moment(info.end).format('HH:mm:ss'); // selected end time
                var modal = document.getElementById('event-modal'); 
                // open modal
                var modal = new bootstrap.Modal(modal);
                modal.show();
                addAppointment(selectedDate, startTime, endTime);
            },

            events: {!! json_encode($events) !!},
            eventClick: function(info) {
                var appointmentid = info.event.id;
                var editBtn = document.getElementById('edit-event-btn');
                editBtn.dataset.id = appointmentid;
                var deleteBtn = document.getElementById('delete-event-btn');
                deleteBtn.dataset.id = appointmentid;        
                // populate form with event details
                var modal = document.getElementById('event-modal'); 
                // open modal
                var modal = new bootstrap.Modal(modal);
                modal.show();
                editAppointment(appointmentid,1);
            }
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

<script type="text/javascript">

const appointform = document.getElementById('appointmentForm');    
// Function to display the appointment form for editing
function editAppointment(id,show=null) {
    // Get the appointment data from the HTML elements
    // let id = element.dataset.id;
    document.getElementById('appointment-id').value = id;
    let url = "{{ route('admin.tutor.appointment.edit', [$tutor_id, ':id']) }}".replace(':id', id);
    $('#appointment-content').load(url, function() {
        flatPickrInit(); 
        dataChoicesInit();  

        // show the appointment form and hide the event-details section
        if(show==1){
            $('.showones').show();
            document.querySelector('.event-form').style.display = 'none';
        }else{
            $('.showones').hide();
            document.querySelector('.event-form').style.display = 'block';
        }
       
    });
}

function addAppointment(selectedDate, startTime, endTime) {
    // reset form
    $('#appointtitle').val('');
    // show the appointment form and hide the event-details section
    $('.showones').hide();
    document.querySelector('.event-form').style.display = 'block';
    // pre-fill the date field with the selected date
    document.getElementById('date').value = moment(selectedDate).format('D MMM, YYYY');
}

function deleteAppointment(element) {
    // Get the appointment ID from the HTML element
    let appointment_idd = element.dataset.id;
    let delete_url = element.dataset.href;
    // Display confirmation alert before proceeding with delete request
    if (confirm("Are you sure you want to delete this appointment?")) {
        $.ajax({
            url: delete_url,
            type: 'GET',
            data: { appointment_id: appointment_idd },
            success: function (data) {
                // Reload the current page on success
                toastr.success('Appointment Deleted Successfully !');
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }
}

</script>
@endsection
