 <div class="row gy-4 stage">
    <div class="col-md-6">
        <label class="form-label">Stage Name</label>
        <input type="text" name="stage_name[]" class="form-control" placeholder="Stage Name" required value="{{ $stage['stage_name'] ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select class="form-select" name="status[{{ isset($index)?$index:0 }}]">
            @php
                $statuses = ['Await', 'Initiate', 'Pending', 'Pass', 'Fail'];
            @endphp
            @foreach ($statuses as $status)
                <option value='{{$status}}' {{ isset($stage['status']) && $stage['status'] == $status ? 'selected' : '' }}>{{$status}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="last_date[{{ isset($index)?$index:0 }}]" class="form-control" value="{{ $stage['last_date'] ?? '' }}">
            <small class="d-block">Leave empty if you don't want to set Deadline</small>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label">Documents</label>
            <input type="file" name="admin_docs[{{ isset($index)?$index:0 }}][]" class="form-control" multiple>
            @if(isset($stage->admin_docs))
                <ul class="list-inline list-auto docs-to-download">
                    @foreach(json_decode($stage->admin_docs, true) as $key=>$file)
                        <li class="list-inline-item">
                            <a download="true" href="{!! Helpers::image($file, 'job/applicationsfiles/') !!}">{{$key+1}}-Download File</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label">User Documents Required?</label>
            <small class="d-block">Leave uncheck if you don't want any doc from user</small>
            <div class="form-check mb-2">
                <input class="form-check-input user_docs_re_checkbox" name="user_docs_required[{{ isset($index)?$index:0 }}]" type="checkbox" value="1" {{ isset($stage['user_docs_required']) && $stage['user_docs_required'] == 1 ? 'checked' : '' }}>
                <label class="form-check-label">
                    Documents Allowed
                </label>
            </div>
            @if(isset($stage->user_docs))
                <ul class="list-inline list-auto docs-to-download">
                    @foreach(json_decode($stage->user_docs, true) as $key=>$file)
                        <li class="list-inline-item">
                            <a download="true" href="{!! Helpers::image($file, 'job/applicationsfiles/') !!}">{{$key+1}}-Download File</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="col-lg-12 mt-0">
        <div class="mb-3">
            <label class="form-label">Details</label>
            <textarea class="form-control" placeholder="Add Details" name="details[]">{{ $stage['details'] ?? '' }}</textarea>
        </div>
    </div>
   
    <div class="col-lg-12 action-btn text-end">
         @if(isset($index) && $index!=0 )
        <button type="button" class="btn btn-danger removestage">Remove</button>
        @endif
    </div>
    
    <hr style="border-style: dashed;">
</div>