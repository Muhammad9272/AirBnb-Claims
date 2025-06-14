    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Code</label>
                                <input type="text" name="coupon_code" class="form-control" placeholder="Enter Coupon Code" value="{{ isset($data->coupon_code) ? $data->coupon_code : 'AP'.( Str::random(3).substr(time(), 6,8) ) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Name</label>
                                <input type="text" name="coupon_name" class="form-control" placeholder="Enter Coupon Name" value="{{ isset($data->coupon_name) ? $data->coupon_name : '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" step="0.001" name="discount" class="form-control" placeholder="Enter Discount" value="{{ isset($data->discount) ? $data->discount : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label class="form-label">Max Usage Count <small>(Set 0 for unlimited)</small></label>
                                <input type="number" name="max_usage_count" class="form-control" value="{{ isset($data->max_usage_count) ? $data->max_usage_count : 0 }}">
                            </div>
                            {{-- <div class="mb-3 d-done">
                                <label class="form-label">Earnings (%)</label>
                                <input type="number" step="0.001" name="earnings" class="form-control" placeholder="Enter Earnings" value="{{ isset($data->earnings) ? $data->earnings : '' }}" required>
                            </div> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ isset($data->start_date) ? $data->start_date : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date </label>
                                <small>Leave empty if you dont want to set end date</small>
                                <input type="date" name="end_date" class="form-control" value="{{ isset($data->end_date) ? $data->end_date : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       {{--  <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Max Usage Count <small>(Set 0 for unlimited)</small></label>
                                <input type="number" name="max_usage_count" class="form-control" value="{{ isset($data->max_usage_count) ? $data->max_usage_count : 0 }}">
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ isset($data->status) && $data->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ isset($data->status) && $data->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>            
        </div>
       
    </div>