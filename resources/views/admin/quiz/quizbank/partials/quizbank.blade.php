


<div class="row gy-4 quizbankdiv">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center gy-3">
                    <div class="col-sm d-flex align-items-center">
                        <h5 class="card-title mb-0">Add Quiz</h5>
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                    @if($question_type == "Game-based")
                    <div class="row gy-4">
                        <!-- Short Title -->
                        <div class="col-md-6 mt-4">
                            @include('admin.quiz.quizbank.partials.form-inputs.short-title')
                        </div>
                         <!-- Select Game -->
                        <div class="col-md-6 mt-4">
                            <div class="mb-3">
                                <label class="form-label">Select Game</label>
                                <select class="form-select" id="game_id"
                                 name="game_id" required>
                                    <option value="" selected disabled>Select Game</option>
                                    @foreach(Helpers::getGames() as $game)
                                     <option value="{{$game}}"  @if(isset($data->game_id) && ($game === $data->game_id)) selected @endif>{{$game}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Suggested Answer -->
                        <div class="col-md-12 mt-4">
                            @include('admin.quiz.quizbank.partials.form-inputs.suggested_answer')
                        </div>
                    </div>
                    @elseif($question_type == "Pdf-based")
                     <div class="row gy-4">
                        <!-- Short Title -->
                        <div class="col-md-6 mt-4">
                            @include('admin.quiz.quizbank.partials.form-inputs.short-title')
                        </div>
                        <div class="col-md-6 mt-4">
                            
                                    <label class="form-label">Free Demo Pages</label> 
                                    <input type="number" name="free_demo_pages" class="form-control"  placeholder="Enter Number of pages eg. 1" value="{{ isset($data->free_demo_pages)?$data->free_demo_pages:1 }}" required>
   
                           
                        </div>
                         <!-- Select Game -->
                        <div class="col-md-6 mt-4">
                            <div class="mb-3">
                                <div class="form-group">                 
                                   <label class="col-md-3 control-label" >Add Pdf Document</label>
                                   @include('admin.quiz.quizbank.partials.form-inputs.pdf-input')  
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    @else
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="row">
                                
                                <div class="col-md-12">
                                        <label class="form-label"> Resources Gallery</label>
                                        <div class="text-center">
                                             @include('includes.inputs.dropzone', ['inputName' => 'gallery','imagePath' => $data->gallery ?? null])

                                        </div>
                                </div>
                                @if(isset($data) && $data->external_gallery)
                                <div class="col-md-8 resources-external-gallery mt-2 mb-2">
                                    <label class="form-label">  Gallery Uploaded from Csv</label>
                                    <div class="card"> 
                                        <div class="card-body">
                                            <!-- Swiper -->
                                            <div class="swiper navigation-swiper rounded">
                                                    @include('includes.swiper')
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                        </div><!-- end card-body -->
                                    </div><!-- end card -->
                                </div>
                                @endif
                                <div class="col-md-12 mt-5">
                                    <label class="form-label" for="meta-description-input">Question Description</label>
                                    <textarea class="mlk-text-editor form-control" name="details">{!! isset($data->details)?$data->details:'' !!}</textarea>
                                    @include('includes.inputs.texteditor')
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-10 mt-4">
                                   @include('admin.quiz.quizbank.partials.form-inputs.short-title')
                                </div>
                            </div>
                            @if($question_type=="Self-Recorded")
                            <div class="row">
                                <div class="col-md-10 mt-4">
                                    
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Prepare Time (Seconds)</label>
                                        <small class="text-muted d-block">Prep Time is used so user can prepare before video-recording starting</small>
                                        <input type="number" name="prepare_time" class="form-control"   placeholder="Enter Prepare Time" value="{{ isset($data->prepare_time)?$data->prepare_time:'' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Response Time (Seconds)</label>
                                        <small class="text-muted d-block">After Prepare Time (By default its set to 2mins, so user can prepare himself), After 2 mins Respose time will start</small>
                                        <input type="number" name="response_time" class="form-control"   placeholder="Enter Response Time" value="{{ isset($data->response_time)?$data->response_time:'' }}" required>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Promotion Media</label>
                                      <div class="text-start">
                                        <input type="file" name="promotion_media" class="form-control">
                                      </div>
                                      <div>
                                        @if(isset($data) && $data->promotion_media)
                                          @php
                                            $path = $data->promotion_media;
                                            $isExternalUrl = filter_var($path, FILTER_VALIDATE_URL) !== false;
                                            $extension = pathinfo($path, PATHINFO_EXTENSION);
                                            $allowedVideoFormats = explode('|', config('fileformats.video'));
                                            $isVideo = in_array($extension, $allowedVideoFormats);
                                            $src = $isExternalUrl ? $path : Helpers::image($path, 'quiz/gallery/');
                                          @endphp
                                          @if($isVideo)
                                            <video src="{{ $src }}" width="320" height="240" controls></video>
                                          @else        
                                            <img src="{{ $src }}" alt="promotion media" class="img-thumbnail img-fluid mt-2">  
                                          @endif
                                          <p>Last media added.</p>
                                        @endif
                                      </div>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label"> Promotion Link</label>
                                        <input type="text" name="promotion_link" class="form-control"   placeholder="Enter Promotion Link" value="{{ isset($data->promotion_link)?$data->promotion_link:'' }}" >
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Options</label>
                                        <div class="quizoptionsContainer" id="">   
                                            @include('admin.quiz.quizbank.partials.quizoptions')
                                            <div>
                                                 <a href="javascript:;" class="addQuizdiv">Add more option</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 mt-4">
                                    @include('admin.quiz.quizbank.partials.form-inputs.suggested_answer')
                                </div>
                            </div>
                            @endif
                            

                        </div>

                    </div>
                    @endif
            </div>
        </div>
    </div>
</div>
