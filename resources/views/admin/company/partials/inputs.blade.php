    <div class="row gy-4">
        <div class="col-lg-8">

            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control"   placeholder="Enter Company Name" value="{{ isset($data->name)?$data->name:'' }}" required>
                        </div>
                    </div>
                    <div> <label>Gallery</label></div>
                    <div class="row mb-4">
                        
                        <div class="col-md-4 text-center"> 
                            <p class="text-muted"> Logo</p>
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="logo-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none img-file-input" value="" name="logo" id="logo-image-input" type="file"
                                            accept="image/png, image/gif, image/jpeg" >
                                    </div>
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="@isset($data->logo){!! Helpers::image($data->logo, 'company/logo/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 text-center">
                            <p class="text-muted">Banner</p>
                           <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="banner-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none img-file-input" value="" name="banner" id="banner-image-input" type="file"
                                            accept="image/png, image/gif, image/jpeg">
                                    </div>
                                    <div class="avatar-lg" style="width:160px">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="@isset($data->banner){!! Helpers::image($data->banner, 'company/banner/') !!}@endif" id="product-img" width="150px" class="avatar-md1 h-auto image-previewable" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#company-detail-info"
                                role="tab">
                                Company Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#company-application-process"
                                role="tab">
                                 Real Samples
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#company-sample-question"
                                role="tab">
                                Practice Question
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#company-position-process"
                                role="tab">
                                 Add Position 
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <!-- end card header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="company-detail-info" role="tabpanel">
                            <label class="form-label" for="meta-description-input">Company Details</label>
                             <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                              @include('includes.inputs.texteditor')
                            <!-- end row -->
                        </div>
                        <!-- end tab-pane -->

                        <div class="tab-pane" id="company-application-process" role="tabpanel">    
                            <div>
                                <label class="form-label">Application Process</label>
                                <textarea class="mlk-text-editor form-control" name="application_process">{!! isset($data->application_process)?$data->application_process:'' !!}</textarea>
                            </div>
                        </div>
                        <!-- end tab-pane -->                        
                       <div class="tab-pane" id="company-sample-question" role="tabpanel">    
                            <div>
                                <label class="form-label" >Sample Question</label>
                                <select data-choices name="sample_question_ids[]" multiple="multiple">
                                    @if(isset($quizbanks))
                                    @foreach($quizbanks as $quizbank)
                                        @php
                                            $selected = ($data->sample_question_ids !== null && in_array($quizbank->id, json_decode($data->sample_question_ids))) ? 'selected' : '';
                                        @endphp
                                        <option value="{{$quizbank->id}}" {{$selected}}>{{$quizbank->title}}</option>
                                    @endforeach
                                   {{--  @foreach($quizbanks as $quizbank)
                                        <option value="{{$quizbank->id}}" @if($data->sample_question_ids && in_array($quizbank->id, json_decode($data->sample_question_ids))) selected @endif>{{$quizbank->title}}</option>
                                    @endforeach --}}
                                    @endif    
                                </select>                              
                            </div>
                        </div>
                        <!-- end tab pane -->
                        <div class="tab-pane" id="company-position-process" role="tabpanel">    
                            <div>
                                <label class="form-label">Add Position</label>
                                <textarea class="mlk-text-editor form-control" name="position_details">{!! isset($data->position_details)?$data->position_details:'' !!}</textarea>
                            </div>
                        </div>
                        <!-- end tab pane -->

                    </div>
                    <!-- end tab content -->
                </div>
                <!-- end card body -->
            </div>

        </div>
        <div class="col-lg-4">
            <!-- end card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tags</h5>
                </div>
                <div class="card-body">
                    <div class="hstack gap-3 align-items-start">
                        <div class="flex-grow-1">
                            <input class="form-control" data-choices data-choices-multiple-remove="true" placeholder="Enter tags" name="tags" type="text"
                        value="{{ isset($data->tags)?$data->tags:'' }}" />
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Short Description</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">Add short description </p>
                    <textarea class="form-control" placeholder="Must enter minimum of a 100 characters" rows="3" name="small_description" >
                        {!! isset($data->small_description)?$data->small_description:'' !!}
                    </textarea>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>