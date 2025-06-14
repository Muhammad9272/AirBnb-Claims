<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Job Information</h5>
            </div>
            <div class="card-body mb-4">
                    <div class="row gy-4 ">
                            <div class="col-md-12">
                                    <label class="form-label">Job Title/Position</label>
                                    <input type="text" name="title" class="form-control"   placeholder="Enter Job Title/Position" value="{{ isset($data->title)?$data->title:'' }}" required>
                            </div>
                            <div class="col-md-6">
                                    <label class="form-label">Company </label>
                                    <select class="form-control" name="company_id" required>
                                        <option selected disabled>Select Company</option>
                                        @foreach($companies as $company)
                                         <option value="{{$company->id}}" {{(isset($data->company_id) && $data->company_id==$company->id)?'selected':''}}>{{$company->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-md-6 ">
                                    <label class="form-label">Service Line</label>
                                    <input type="text" name="service_line" class="form-control"   placeholder="Enter Service Line" value="{{ isset($data->service_line)?$data->service_line:'' }}" required>
                            </div>
                            
                            <div class="col-md-6 ">
                                    <label class="form-label">Program</label>
                                    <input type="text" name="program" class="form-control"   placeholder="Enter Program " value="{{ isset($data->program)?$data->program:'' }}" required>                               
                            </div>
                            <div class="col-md-6 ">
                                    <label class="form-label">Job/Position Link</label>
                                    <input type="text" name="job_link" class="form-control"   placeholder="Enter Job/Position Link " value="{{ isset($data->job_link)?$data->job_link:'' }}" required>                               
                            </div>
                           
                          {{--   <div class="col-md-12">
                                <label class="form-label" for="meta-description-input"> Details</label>
                                <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                                @include('includes.inputs.texteditor')
                            </div> --}}
                        

                    </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Location & Expiry</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                     <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control"   placeholder="Enter Region/Location " value="{{ isset($data->location)?$data->location:'' }}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                             <div class="mb-3">
                                <label class="form-label">Last Date </label>
                                <small class="d-block">Leave empty if you dont want to set Last date</small>
                                <input type="date" name="last_date" class="form-control" value="{{ isset($data->last_date) ? $data->last_date : date('m-d-Y') }}">
                            </div>
                        </div>
                    </div>
            </div>
        </div>
     

    </div>
</div>


@push('partial_script') 
<script>
    
</script>
@endpush               