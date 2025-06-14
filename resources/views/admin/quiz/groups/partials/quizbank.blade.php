

@if($question_type=="Self-Recorded")
<div class="row gy-4 quizbankdiv">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center gy-3">
                    <div class="col-sm d-flex align-items-center">
                        <h5 class="card-title mb-0">Add Quiz</h5>
                        
                    </div>
                    {{-- <div class="col-sm-auto">
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="javascript:;" class="btn btn-success add-btn addQuizBankdiv" ><i class="ri-add-line align-bottom me-1"></i> Add More</a>
                            <a href="javascript:;" class="btn btn-soft-danger removeQuizBankdiv"><i
                                    class="ri-delete-bin-2-line"></i></a>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="row">
                                
                                <div class="col-md-6">
                                        <label class="form-label"> Resourses</label>
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <div class="position-absolute top-100 start-100 translate-middle">
                                                    <label for="resourses-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none img-file-input" value="" name="resourses" id="resourses-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg" >
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="@isset($data->resourses){!! Helpers::image($data->resourses, 'quiz/media/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6 ">
                                        <label class="form-label"> Media</label>
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <div class="position-absolute top-100 start-100 translate-middle">
                                                    <label for="media-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none img-file-input" value="" name="media" id="media-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg" >
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="@isset($data->media){!! Helpers::image($data->media, 'quiz/media/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                </div>
                                <div class="col-md-12 mt-5">
                                    <label class="form-label" for="meta-description-input">Question Description</label>
                                    <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-10 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Prepare Time (mints)</label>
                                        <input type="number" name="prepare_time" class="form-control"   placeholder="Enter Prepare Time" value="{{ isset($data->prepare_time)?$data->prepare_time:'' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Response Time (mints)</label>
                                        <input type="number" name="response_time" class="form-control"   placeholder="Enter Response Time" value="{{ isset($data->response_time)?$data->response_time:'' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"> Promotion Photo</label>
                                        <div class="text-start">
                                            <div class="position-relative d-inline-block">
                                                <div class="position-absolute top-100 start-100 translate-middle">
                                                    <label for="promotion-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none img-file-input" value="" name="promotion_photo" id="promotion-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg" >
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="@isset($data->promotion_photo){!! Helpers::image($data->promotion_photo, 'quiz/media/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"> Promotion Link</label>
                                        <input type="text" name="promotion_link" class="form-control"   placeholder="Enter Promotion Link" value="{{ isset($data->promotion_link)?$data->promotion_link:'' }}" >
                                    </div>
                                </div>
                            </div>
                            

                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row gy-4 quizbankdiv">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center gy-3">
                    <div class="col-sm d-flex align-items-center">
                        <h5 class="card-title mb-0">Add Quiz</h5>
                        
                    </div>
                    {{-- <div class="col-sm-auto">
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="javascript:;" class="btn btn-success add-btn addQuizBankdiv" ><i class="ri-add-line align-bottom me-1"></i> Add More</a>
                            <a href="javascript:;" class="btn btn-soft-danger removeQuizBankdiv"><i
                                    class="ri-delete-bin-2-line"></i></a>
                        </div>
                    </div> --}}
                </div>
            </div>



            <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="row">
                                
                                <div class="col-md-6">
                                        <label class="form-label"> Resourses</label>
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <div class="position-absolute top-100 start-100 translate-middle">
                                                    <label for="resourses-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none img-file-input" value="" name="resourses" id="resourses-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg" >
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="@isset($data->resourses){!! Helpers::image($data->resourses, 'quiz/resourses/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6 ">
                                        <label class="form-label"> Media</label>
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <div class="position-absolute top-100 start-100 translate-middle">
                                                    <label for="media-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none img-file-input" value="" name="media" id="media-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg" >
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="@isset($data->media){!! Helpers::image($data->media, 'quiz/media/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                </div>
                                <div class="col-md-12 mt-5">
                                    <label class="form-label" for="meta-description-input">Question Description</label>
                                    <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Options</label>
                                        <div class="quizoptionsContainer" id="">   
                                            @include('admin.quiz.groups.partials.quizoptions')
                                            <div>
                                                 <a href="javascript:;" class="addQuizdiv">Add more option</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 mt-4">
                                    <label class="form-label" for="meta-description-input">Suggested Answer</label>
                                     <textarea class="mlk-text-editor form-control" name="suggested_answer">{!! isset($data->suggested_answer)?$data->suggested_answer:'' !!}</textarea>
                                </div>
                            </div>
                            

                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>
@endif