<div class="mb-3">
    <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
    <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $data->title ?? '') }}" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
    <textarea id="description" name="description" class="form-control mlk-text-editor @error('description') is-invalid @enderror" 
              rows="5" >{{ old('description', $data->description ?? '') }}</textarea>
               @include('includes.inputs.texteditor')
    @error('description')
   
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>



<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="bit_value" class="form-label">Bit Value <span class="text-danger">*</span></label>
            <input id="bit_value" name="bit_value" type="number" min="1" 
                   class="form-control @error('bit_value') is-invalid @enderror"
                   value="{{ old('bit_value', $data->bit_value ?? '10') }}" required>
            @error('bit_value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Number of bits users will earn when task is approved</small>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="max_submissions" class="form-label">Max Submissions</label>
            <input id="max_submissions" name="max_submissions" type="number" min="1"
                   class="form-control @error('max_submissions') is-invalid @enderror"
                   value="{{ old('max_submissions', $data->max_submissions ?? '') }}">
            @error('max_submissions')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Leave empty for unlimited submissions</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="required_proof" class="form-label">Required Proof</label>
            <input id="required_proof" name="required_proof" type="text"
                   class="form-control @error('required_proof') is-invalid @enderror"
                   value="{{ old('required_proof', $data->required_proof ?? '') }}">
            @error('required_proof')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Describe what proof users need to submit (e.g., "Screenshot of your purchase")</small>
        </div>
    </div>
    
{{--     <div class="col-md-6">
        <div class="mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" {{ (old('status', $data->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ (old('status', $data->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div> --}}
</div>
