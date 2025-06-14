   <div class="row gy-4">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"> Name</label>
                                <input type="text" name="name" class="form-control"   placeholder="Enter  Name" value="{{ isset($data->name)?$data->name:'' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"> Email</label>
                                <input type="email" name="email" class="form-control"   placeholder="Enter Email" value="{{ isset($data->email)?$data->email:'' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"> Phone</label>
                                <input type="text" name="phone" class="form-control"   placeholder="Enter Phone" value="{{ isset($data->phone)?$data->phone:'' }}" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"> Password</label>
                                <input type="password" name="password" class="form-control"   placeholder="Enter password" value="" {{ isset($data->phone)?'':'required' }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"> Select Role</label>
                                <select  name="role_id" class="form-select" required="">
                                    <option value="">{{ __('Select Role') }}</option>
                                        @foreach(DB::table('roles')->get() as $dta)
                                        <option value="{{ $dta->id }}" {{(isset($data) && $data->role_id == $dta->id) ? 'selected' : '' }}>{{ $dta->name }}</option>
                                        @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4 text-center"> 
                                <p class="text-muted"> Profile Photo</p>
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
                                            <input class="form-control d-none img-file-input" value="" name="photo" id="logo-image-input" type="file"
                                                accept="image/png, image/gif, image/jpeg" >
                                        </div>
                                        <div class="avatar-lg">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="@isset($data->photo){!! Helpers::image($data->photo, 'admin/images/') !!}@endif" id="product-img" class="avatar-md h-auto image-previewable" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
       
    </div>