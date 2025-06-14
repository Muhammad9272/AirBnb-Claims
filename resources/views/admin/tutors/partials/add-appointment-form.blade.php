

    <div class="text-end showones">
        <a href="#" class="btn btn-sm btn-soft-primary" id="edit-event-btn" data-id="{{isset($appointmentdata)?$appointmentdata->id:null }}" onclick="editAppointment({{isset($appointmentdata)?$appointmentdata->id:null }})" role="button">Edit</a>
    </div>
    <div class="event-details showones" style="display: block;">
       <h4>{{isset($appointmentdata)?$appointmentdata->title:null }}</h4>
        <div class="d-flex mb-2">
            <div class="flex-grow-1 d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="ri-user-line text-muted fs-16"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="d-block fw-semibold mb-0" id="event-title"> {{isset($appointmentdata) && $appointmentdata->student?$appointmentdata->student->name.' Email :'.($appointmentdata->student->email):null }}</h6>
                </div>
            </div>
        </div>


        <div class="d-flex mb-2">
            <div class="flex-grow-1 d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="ri-calendar-event-line text-muted fs-16"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="d-block fw-semibold mb-0" id="event-start-date-tag">
                     {{ isset($appointmentdata->start_date) ? \Carbon\Carbon::parse($appointmentdata->start_date)->format('d M, Y')  : '' }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2">
            <div class="flex-shrink-0 me-3">
                <i class="ri-time-line text-muted fs-16"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="d-block fw-semibold mb-0"><span id="event-timepicker1-tag">{{isset($appointmentdata->start_time)? \Carbon\Carbon::parse($appointmentdata->start_time)->format('h:i A'):null }}</span> - <span id="event-timepicker2-tag">{{isset($appointmentdata->end_time)?\Carbon\Carbon::parse($appointmentdata->end_time)->format('h:i A'):null  }}</span></h6>
            </div>
        </div> 
    </div>
    <div class="row event-form" style="display: none;">
      <form id="appointmentForm" method="post" action="{{route('admin.tutor.appointment.store',$tutor_id)}}" >
        @csrf
        <div class="mb-3">
          <label for="title" class="form-label">Status</label>   
          <select class="form-select" name="status">
              <option value="pending" {{isset($appointmentdata->status) && $appointmentdata->status=='pending'?'selected':'' }}>Pending</option>
              <option value="completed" {{isset($appointmentdata->status) && $appointmentdata->status=='completed'?'selected':'' }}>Completed</option>
           </select>
        </div>

        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input type="text" class="form-control" id="appointtitle" name="title" value="{{isset($appointmentdata)?$appointmentdata->title:null }}" required>
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Meeting Link</label>
          <input type="text" class="form-control" id="" name="meeting_id" value="{{isset($appointmentdata)?$appointmentdata->meeting_id:null }}" required>
        </div>

        
        <div class="mb-3">
          <label for="date" class="form-label">Date</label>
          <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-deafult-date="today"  id="date" name="start_date" required value="{{ isset($appointmentdata->start_date) ? \Carbon\Carbon::parse($appointmentdata->start_date)->format('d M, Y')  : '' }}" >
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
              <label>Start Time</label>
              <select name="start_time" id="start-time" data-choices class="form-select" required>
                  @for ($hour = 0; $hour < 24; $hour++) 
                      @for ($minute=0; $minute < 60; $minute +=30) 
                          @php
                          $time = sprintf('%02d:%02d:00', $hour, $minute);

                          @endphp 


                          <option value="{{ $time }}" {{isset($appointmentdata) && $appointmentdata->start_time==$time?'selected':'' }}>{{
                          date('h:i A', strtotime($time)) }}</option>
                      @endfor
                  @endfor
              </select>
          </div>
          <div class="col-md-6 mb-3">
              <label>End Time</label>
              <select name="end_time" id="end-time" data-choices class="form-select" required>
                  @for ($hour = 0; $hour < 24; $hour++) 
                      @for ($minute=0; $minute < 60; $minute +=30) 
                          @php
                         $time = sprintf('%02d:%02d:00', $hour, $minute); 
                          @endphp 
                          <option value="{{ $time }}" {{isset($appointmentdata) && $appointmentdata->end_time==$time?'selected':'' }}>{{
                          date('h:i A', strtotime($time)) }}</option>
                      @endfor
                  @endfor
              </select>
          </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="event-description" placeholder="Enter a description" name="details" rows="3" spellcheck="false">{!! isset($appointmentdata)?$appointmentdata->details:null !!}</textarea>
            </div>
        </div><!--end col-->
        <input type="hidden" id="appointment-id" name="id" value="{{isset($appointmentdata)?$appointmentdata->id:null }}">
         <button type="submit" class="btn btn-success" id="btn-save-event">{{isset($appointmentdata)?"Update":"Submit" }} Appointment</button>
      </form>
    </div>





