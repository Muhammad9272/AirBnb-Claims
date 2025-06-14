<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tutors Information</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                            <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control"   placeholder="Enter Tutor Name" value="{{ isset($data->name)?$data->name:'' }}" required>
                            </div>                            
                            <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"   placeholder="Enter Eamil" value="{{ isset($data->email)?$data->email:'' }}" required>
                            </div>
                            <div class="col-md-6">
                                    <label class="form-label">Select Language </label>
                                    <select class="form-control" data-choices name="language[]" multiple="multiple" >
                                        {{-- @php
                                            $selectedLanguages = isset($data->language) ? (is_array($data->language) ? $data->language : [$data->language]) : [];
                                        @endphp --}}
                                        @foreach(Helpers::getLanguages() as $language)
                                        @if(isset($data->language))
                                        <option value="{{$language['name']}}" {{(is_array(json_decode($data->language)) && in_array($language['name'], json_decode($data->language))) || $data->language === $language['name'] ? 'selected' : ''}}>{{$language['name']}}</option>
                                        @else
                                        
                                         <option value="{{$language['name']}}">{{$language['name']}}</option>
                                         @endif
                                       
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tags </label>
                                <div class="hstack gap-3 align-items-start">
                                    <div class="flex-grow-1">
                                        <input class="form-control" data-choices data-choices-multiple-remove="true" placeholder="Enter Tags" name="tags" type="text"
                                    value="{{ isset($data->tags)?$data->tags:'' }}" />
                                    </div>
                                </div> 
                            </div>



                            <div class="card">
                                <div class=" mb-4">
                                    <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#ad-tab-about-me"
                                                role="tab">
                                               About me
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#ad-tab-coaching-services"
                                                role="tab">
                                                 Coaching Services
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#ad-tab-faq"
                                                role="tab">
                                                FAQ
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card header -->
                                <div class="">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="ad-tab-about-me" role="tabpanel">
                                            <label class="form-label" for="meta-description-input">About Me</label>
                                             <textarea class="mlk-text-editor form-control" name="about">{!! isset($data->about)?$data->about:'' !!}</textarea>
                                              @include('includes.inputs.texteditor')
                                            <!-- end row -->
                                        </div>
                                        <!-- end tab-pane -->

                                        <div class="tab-pane" id="ad-tab-coaching-services" role="tabpanel">    
                                            <div>
                                                <label class="form-label">Coaching Services</label>
                                                <textarea class="mlk-text-editor form-control" name="coaching_services">{!! isset($data->coaching_services)?$data->coaching_services:'' !!}</textarea>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->
                                        <div class="tab-pane" id="ad-tab-faq" role="tabpanel">    
                                            <div>
                                                <label class="form-label" >Faqs</label>
                                                <textarea class="mlk-text-editor form-control" name="faqs">{!! isset($data->faqs)?$data->faqs:'' !!}</textarea>                          
                                            </div>
                                        </div>
                                        <!-- end tab pane -->
                                    </div>
                                    <!-- end tab content -->
                                </div>
                                <!-- end card body -->
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
                                <label class="form-label d-block text-start">Profile Photo</label>
                                @include('includes.inputs.media-input', [
                                    'inputName' => 'photo',
                                    'imagePath' => isset($data->photo) ? Helpers::image($data->photo, 'user/avatar/') : null
                                ])

                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Introduction Video</label>
                                    <input type="file" name="intro_video" class="form-control" >
                                    @if(isset($data->intro_video))
                                    <div class="mt-2">
                                        <a href="{{Helpers::image($data->intro_video, 'user/intro_videos/')}}" class="mb-2" download="">View Last added Video</a>
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


