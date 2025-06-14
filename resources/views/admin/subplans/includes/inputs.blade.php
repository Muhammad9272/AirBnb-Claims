<div class="col-xxl-4 col-md-4">
    <div>
        <label for="basiInput" class="form-label"> Name</label>
        <input type="text" class="form-control" id="basiInput" name="name" value="{{ isset($data->name) ? $data->name : '' }}" required>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label for="basiInput2" class="form-label"> Price</label>
        <input type="number" name="price" step="0.001" class="form-control" id="basiInput2" value="{{ isset($data->price) ? $data->price : '' }}" required>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label for="basiInput2" class="form-label"> Time Interval</label>
        <select class="form-select" name="interval" required>
            <option value=""> Select Interval</option>
            <option value="weekly" {{ isset($data->interval) && $data->interval == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ isset($data->interval) && $data->interval == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="quarterly" {{ isset($data->interval) && $data->interval == 'quarterly' ? 'selected' : '' }}>3 Months</option>
            <option value="biannually" {{ isset($data->interval) && $data->interval == 'biannually' ? 'selected' : '' }}>6 Months</option>
            <option value="yearly" {{ isset($data->interval) && $data->interval == 'yearly' ? 'selected' : '' }}>Yearly</option>
            <option value="unlimited" {{ isset($data->interval) && $data->interval == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
        </select>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label for="commission_percentage" class="form-label">Commission Percentage (%)</label>
        <input type="number" name="commission_percentage" step="0.01" min="0" max="100" class="form-control" id="commission_percentage" value="{{ isset($data->commission_percentage) ? $data->commission_percentage : '20' }}">
        <small class="text-muted">Percentage of approved claim payouts (e.g. 20 for 20%)</small>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label for="claims_limit" class="form-label">Claims Limit</label>
        <input type="number" name="claims_limit" min="1" class="form-control" id="claims_limit" value="{{ isset($data->claims_limit) ? $data->claims_limit : '' }}">
        <small class="text-muted">Number of claims allowed per billing cycle. Leave empty for unlimited claims.</small>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label for="display_label" class="form-label">Special Display Label</label>
        <input type="text" name="display_label" class="form-control" id="display_label" value="{{ isset($data->display_label) ? $data->display_label : '' }}">
        <small class="text-muted">Optional label (e.g. "Save $120/year")</small>
    </div>
</div>

<div class="col-xxl-4 col-md-4">
    <div>
        <label class="form-label">Is Featured Plan?</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ isset($data->is_featured) && $data->is_featured == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">Featured plans get highlighted on the homepage</label>
        </div>
    </div>
</div>

<div class="col-xxl-12 col-md-12">
    <div>
        <label for="exampleFormControlTextarea5" class="form-label">Description</label>
        <textarea class="form-control" id="exampleFormControlTextarea5" name="details" rows="3">{{ isset($data->details) ? $data->details : '' }}</textarea>
    </div>
</div>

<div class="col-xxl-12 col-md-12 mt-3">
    <div>
        <label for="features" class="form-label">Plan Features (one per line)</label>
        <textarea class="form-control" id="features" name="features" rows="5" placeholder="Unlimited claim submissions&#10;Full-service claims management&#10;Evidence gathering, filing, follow-up&#10;Monthly reports + support">{{ isset($data->features) ? $data->features : '' }}</textarea>
        <small class="text-muted">Enter each feature on a new line. These will be displayed as bullet points on the homepage.</small>
    </div>
</div>
