<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quiz Bank Information</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                            <div class="col-md-8 ">
                                    <label class="form-label">Quiz Name</label>
                                    <input type="text" name="name" class="form-control"   placeholder="Enter Quiz Name" value="{{ isset($data->name)?$data->name:'' }}" required>
                            </div>
                            <div class="col-md-4 ">
                                    <label class="form-label">Company </label>
                                    <select class="form-control" name="company_id" required>
                                        <option selected disabled>Select Company</option>
                                        @foreach($companies as $company)
                                         <option value="{{$company->id}}" {{(isset($data->company_id) && $data->company_id==$company->id)?'selected':''}}>{{$company->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-md-4 ">
                                    <label class="form-label">Postition</label>
                                    <input type="text" name="position" class="form-control"   placeholder="Enter Position" value="{{ isset($data->position)?$data->position:'' }}" required>
                            </div>
                            <div class="col-md-4 ">
                                    <label class="form-label">Assessment Stage</label>
                                    <input type="text" name="assessment_stage" class="form-control"   placeholder="Enter Assessment stage" value="{{ isset($data->assessment_stage)?$data->assessment_stage:'' }}" required>
                            </div>
                            <div class="col-md-4 ">
                                    <label class="form-label">Program</label>
                                    <input type="text" name="program" class="form-control"   placeholder="Enter Program " value="{{ isset($data->program)?$data->program:'' }}" required>                               
                            </div>
                            <div class="col-md-12">
                                    <label class="form-label">Region</label>
                                    <input type="text" name="location" class="form-control"   placeholder="Enter Region/Location " value="{{ isset($data->location)?$data->location:'' }}" required>                               
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
                            {{-- <div class="mb-3">
                                    <label class="form-label">MemberShip Level </label>
                                    <select data-choices name="subplan_ids[]" multiple="multiple" >
                                        @foreach($subplans as $subplan)
                                            <option value="{{$subplan->id}}" @if(isset($data->subplan_ids)){{ in_array($subplan->id, json_decode($data->subplan_ids)) ? 'selected' : '' }}@endif>{{$subplan->name }} ({{$subplan->interval}})</option>
                                        @endforeach

                                    </select>
                            </div> --}}
                            <div class="mb-3">
                                    <label class="form-label">Assessment Type </label>
                                    <select class="form-control" name="assessment_type" required>
                                        <option selected disabled>Select Assessment Type</option>
                                        @foreach(Helpers::getQuestionTypes() as $question_type)
                                         <option value="{{$question_type}}" @if(isset($data->assessment_type)){{$question_type==$data->assessment_type?'selected':''}} @endif>{{$question_type}}</option>
                                        @endforeach
                                       {{--  @foreach(Helpers::getAssessmentType() as $assessment_type)
                                         <option value="{{$assessment_type}}" @if(isset($data->assessment_type)){{$assessment_type==$data->assessment_type?'selected':''}} @endif>{{$assessment_type}}</option>
                                        @endforeach --}}
                                    </select>


                            </div>
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
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Introduction Video</label>
                                    <input type="file" name="intro_video" class="form-control" >
                                    @if(isset($data->intro_video))
                                    <div class="mt-2">
                                        <a href="{{Helpers::image($data->intro_video, 'quiz/intro_videos/')}}" class="mb-2" download="">View Last added Video</a>
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
<div class="row gy-4">
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
</div>

@push('partial_script') 
<script>
    // Generate default quiz name based on the selected values
    $(document).ready(function() {
        $('select[name="company_id"], input[name="position"], input[name="assessment_stage"], input[name="program"]').change(function() {
            var companyName = $('select[name="company_id"] option:selected').text();
            var position = $('input[name="position"]').val();
            var assessmentStage = $('input[name="assessment_stage"]').val();
            var program = $('input[name="program"]').val();
            
            var quizName = companyName + '-' + position + '-' + assessmentStage + '(' + program + ')';
            $('input[name="name"]').val(quizName);
        });
    });
</script>
@endpush               