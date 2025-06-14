<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3">
                <a href="{{ route('admin.quizbank.index',$quizbankmanagement->slug) }}" class="btn btn-soft-primary float-start d-flex justify-content-center align-items-center p-1  ">
                    <i class=" ri-arrow-left-s-line lh-1 fs-4"></i>
                </a>
                <h5 class="card-title mb-0">Quiz Groups of (<a class="" href="{{route('admin.quiz.management.edit',$quizbankmanagement->id)}}"> {{$quizbankmanagement->name}} </a>)</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                           {{--  <div class="col-md-4 ">
                                    <label class="form-label">Quiz Management </label>
                                    <select class="form-control" name="company_id" required>
                                        <option selected disabled>Select Quiz Management</option>
                                        @foreach($quizbankmanagements as $quizbankmanagement)
                                         <option value="{{$quizbankmanagement->id}}" {{(isset($data->quizbankmanagement_id) && $data->quizbankmanagement_id==$quizbankmanagements->id)?'selected':''}}>{{$quizbankmanagement->name}}</option>
                                        @endforeach
                                    </select>
                            </div> --}}
                            <input type="hidden" name="quizbankmanagement_id" value="{{$quizbankmanagement->id}}">
                            <div class="col-md-6">
                                    <label class="form-label">Select Quiz Group</label>
                                     <select class="form-control" name="quiz_group" required>
                                        <option selected disabled>Select Quiz Group </option>
                                        @if($quizbankmanagement->quiz_group_names)
                                            @foreach(explode(',',$quizbankmanagement->quiz_group_names) as $quizgroup)
                                             <option value="{{$quizgroup}}" {{(isset($data->quiz_group) && $data->quiz_group==$quizgroup)?'selected':''}}>{{$quizgroup}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Question Type </label>
                                <select class="form-control" id="question_type"
                                 name="question_type" required>
                                    <option selected disabled>Select Question Type</option>
                                    @foreach(Helpers::getQuestionTypes() as $question_type)
                                     <option value="{{$question_type}}" @if(isset($data->question_type)){{$question_type==$data->question_type?'selected':''}} @endif>{{$question_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="" id="quizbankContainer">
    @if(isset($data->question_type) || isset($data->id))
       @include('admin.quiz.quizbank.partials.quizbank',['question_type' => $data->question_type])
    @endif
</div>
