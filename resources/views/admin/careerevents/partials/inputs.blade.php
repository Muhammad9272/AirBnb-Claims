<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Event Information</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                            <div class="col-md-12">
                                    <label class="form-label">Event Name</label>
                                    <input type="text" name="name" class="form-control"   placeholder="Enter Event Name" value="{{ isset($data->name)?$data->name:'' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Event Type </label>
                                <select class="form-control" id="event_type"
                                 name="event_type" required>
                                    <option selected disabled value="">Select Event Type</option>
                                    @foreach(Helpers::getEventTypes() as $event_type)
                                     <option value="{{$event_type}}" @if(isset($data->event_type)){{$event_type==$data->event_type?'selected':''}} @endif>{{$event_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                    <label class="form-label">Host Name</label>
                                    <input type="text" name="host_name" class="form-control"   placeholder="Enter Host Name" value="{{ isset($data->host_name)?$data->host_name:'' }}" >
                            </div>
                            <div class="col-md-6">
                                    <label class="form-label">Meeting Link</label>
                                    <input type="text" name="meeting_id" class="form-control"   placeholder="Enter Meeting Link" value="{{ isset($data->meeting_id)?$data->meeting_id:'' }}" required>
                            </div>
                           <div class="col-md-6">
                                <label class="form-label">Launch Date & Time </label>
                                <input type="text" data-provider="flatpickr" data-date-format="d M, Y" data-enable-time name="event_date_time" class="form-control" value="{{ isset($data->event_date_time)? \Carbon\Carbon::parse($data->event_date_time)->format('d M, Y H:i:s'):'' }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="meta-description-input"> Details</label>
                                <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                                @include('includes.inputs.texteditor')
                            </div>
                        

                    </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pricing </h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="text" name="price" class="form-control"   placeholder="Enter Price" value="{{ isset($data->price)?$data->price:'' }}" required>
                                </div>
                            </div>
                           {{--  <div class="mb-3">
                                    <label class="form-label">MemberShip Level </label>
                                    <select data-choices name="subplan_ids[]" multiple="multiple" >
                                        @foreach($subplans as $subplan)
                                            <option value="{{$subplan->id}}" @if(isset($data->subplan_ids)){{ in_array($subplan->id, json_decode($data->subplan_ids)) ? 'selected' : '' }}@endif>{{$subplan->name }} ({{$subplan->interval}})</option>
                                        @endforeach

                                    </select>
                            </div> --}}

                        </div>
                    </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Media </h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                         <div class="col-lg-12">
                            <div class="mb-3 text-center">
                                <label class="form-label d-block text-start">Cover Photo</label>
                                @include('includes.inputs.media-input', [
                                    'inputName' => 'photo',
                                    'imagePath' => isset($data->photo) ? Helpers::image($data->photo, 'events/') : null
                                ])

                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Introduction Video</label>
                                    <input type="file" name="intro_video" class="form-control" >
                                    @if(isset($data->intro_video))
                                    <div class="mt-2">
                                        <a href="{{Helpers::image($data->intro_video, 'events/intro_videos/')}}" class="mb-2" download="">View Last added Video</a>
                                    </div>
                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

    </div>
</div>
{{-- <div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quiz Groups</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                        <div class="hstack gap-3 align-items-start">
                            <div class="flex-grow-1">
                                <input class="form-control" data-choices data-choices-multiple-remove="true" placeholder="Enter Quiz Group Names" name="quiz_group_names" type="text"
                            value="{{ isset($data->quiz_group_names)?$data->quiz_group_names:'' }}" />
                            </div>
                        </div>    
                    </div>
            </div>
        </div>
    </div>
</div> --}}