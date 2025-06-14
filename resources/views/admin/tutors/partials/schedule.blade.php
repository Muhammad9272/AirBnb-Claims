<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add working hours.</h5>
                <p class="text-muted">Set Tutor availability to take bookings</p>
            </div>
            <div class="card-body">
                <input type="hidden" name="schedule_id" value="{{isset($data->id)?$data->id:null}}">
                <div class="row gy-4">
                    <div class="col-md-6 mb-3">
                        <label data-bs-toggle="tooltip" data-bs-placement="top" title="Select the start date for your availability">Start date <small class="text-muted">(Start date must be of {{$daySelected}})</small></label>
                        <div class="input-group">
                            <input type="text" name="start_date" class="form-control" data-provider="flatpickr"
                                data-date-format="d M, Y" value="{{ isset($data->start_date) ? \Carbon\Carbon::parse($data->start_date)->format('d M, Y')  : '' }}"
                                data-minDate="{{ isset($data->start_date)?'':\Carbon\Carbon::now()->format('d M, Y') }}"
                                >
                            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label>End date <small class="text-muted">(Leave Empty for unlimited time)</small> </label>
                       
                        <div class="input-group">
                            <input type="text" name="end_date" class="form-control" data-provider="flatpickr"
                                data-date-format="d M, Y"  value="{{ isset($data->end_date) ? \Carbon\Carbon::parse($data->end_date)->format('d M, Y')  : '' }}"
                                data-minDate="{{ isset($data->end_date)?'':\Carbon\Carbon::now()->format('d M, Y') }}"
                                >
                            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                        </div>
                    </div>

                </div>
                <div class="row gy-4">
                    <div class="col-md-6 mb-3">
                        <label>Repeats</label>
                        <select name="repeat_interval" class="form-select">
                            <option value="weekly" {{ isset($data) && $data->repeat_interval == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="biweekly" {{ isset($data) && $data->repeat_interval == 'biweekly' ? 'selected' : '' }}>Every 2 weeks</option>
                            <option value="triweekly" {{ isset($data) && $data->repeat_interval == 'triweekly' ? 'selected' : '' }}>Every 3 weeks</option>
                            <option value="quadweekly" {{ isset($data) && $data->repeat_interval == 'quadweekly' ? 'selected' : '' }}>Every 4 weeks</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Days</label>
                        <!-- Outlined Styles -->
                       <div class="hstack gap-1 flex-wrap">
                      @php
                      $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                      @endphp
                      @foreach ($daysOfWeek as $day)
                        @php
                        $dayAbbreviation = substr($day, 0, 1);
                        $dayNameLowercase = strtolower($day);
                        // $selectedDay=isset($data)?$data->day_of_week:$daySelected;
                        $selectedDay=$daySelected;
                        $isChecked = $dayNameLowercase == $selectedDay;
                        // $isDisabled = isset($data) && !$isChecked;
                        @endphp
                        <input type="checkbox" name="available_days[]" value="{{$dayNameLowercase}}" class="btn-check" id="btn-check-{{$dayNameLowercase}}-outlined" {{ $isChecked ? 'checked' : 'disabled' }} {{ $selectedDay ? '' : '' }} >

                        <label class="btn btn-outline-secondary" for="btn-check-{{$dayNameLowercase}}-outlined" title="{{$day}}">{{$dayAbbreviation }}</label>   
                      @endforeach
                    </div>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Start From</label>
                        <select name="start_time" data-choices class="form-select" required>
                            @for ($hour = 0; $hour < 24; $hour++) 
                                @for ($minute=0; $minute < 60; $minute +=30) 
                                    @php
                                    $time = sprintf('%02d:%02d:00', $hour, $minute);

                                    @endphp 


                                    <option value="{{ $time }}" {{isset($data) && $data->start_time==$time?'selected':'' }}>{{
                                    date('h:i A', strtotime($time)) }}</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>To</label>
                        <select name="end_time" data-choices class="form-select" required>
                            @for ($hour = 0; $hour < 24; $hour++) 
                                @for ($minute=0; $minute < 60; $minute +=30) 
                                    @php
                                   $time = sprintf('%02d:%02d:00', $hour, $minute); 
                                    @endphp 
                                    <option value="{{ $time }}" {{isset($data) && $data->end_time==$time?'selected':'' }}>{{
                                    date('h:i A', strtotime($time)) }}</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Meeting Link</label>
                        <input type="text" name="meeting_id" class="form-control" placeholder="Enter Meeting Link" value="{{isset($data)?$data->meeting_id:''}}"
                            required>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($edit_form))
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>
@else
 @push('partial_script')
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>
@endpush
@endif

