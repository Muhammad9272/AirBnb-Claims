<div class="row gy-4">
    <div class="col-lg-12">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Name Field -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $data->name ?? '') }}" required>
                        <small class="text-muted">Enter a unique and descriptive name</small>
                    </div>
                </div>

                <!-- Categories -->
                <div class="row">
                    <!-- Main Category -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Main Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Main Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $data->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sub Category -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sub Category <span class="subcategory-required d-none text-danger">*</span></label>
                        <select name="subcategory_id" id="subcategory_id" class="form-control">
                            <option value="">Select Sub Category</option>
                            @if(isset($subcategories))
                                @foreach($subcategories as $sub)
                                    <option value="{{ $sub->id }}" {{ old('subcategory_id', $data->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted subcategory-hint">Select if applicable for the chosen category</small>
                    </div>

                    <!-- Child Category -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Child Category <span class="childcategory-required d-none text-danger">*</span></label>
                        <select name="childcategory_id" id="childcategory_id" class="form-control">
                            <option value="">Select Child Category</option>
                            @if(isset($childcategories))
                                @foreach($childcategories as $child)
                                    <option value="{{ $child->id }}" {{ old('childcategory_id', $data->childcategory_id ?? '') == $child->id ? 'selected' : '' }}>
                                        {{ $child->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted childcategory-hint">Select if applicable for the chosen subcategory</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control mlk-text-editor" rows="4">{{ old('description', $data->description ?? '') }}</textarea>
                    <small class="text-muted">Provide details about this nostalgic item</small>

                    @include('includes.inputs.texteditor')


                </div>

                <!-- Gallery -->
                <div class="mb-3">
                    <label class="form-label">Gallery Images</label>
                    @include('includes.inputs.dropzone', [
                        'inputName' => 'gallery',
                        'imagePath' => 'nostalgia/items/',
                        'gallery' => isset($data->gallery) ? json_decode($data->gallery) : null
                    ])
                    <small class="text-muted">First image will be used as main photo</small>
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label class="form-label">Tags <span class="text-muted">(Optional)</span></label>
                    <input type="text" name="tags" class="form-control" 
                           placeholder="e.g., new, xbox, console, gaming" 
                           value="{{ old('tags', isset($data) && is_array(json_decode($data->tags)) ? implode(',', json_decode($data->tags)) : $data->tags ?? '') }}">
                    <small class="text-muted">Comma-separated tags to help with search</small>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Specifications</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Release Type</label>
                            <input type="text" name="specifications[release_type]" class="form-control" 
                                   value="{{ old('specifications.release_type', $data->formatted_specifications['release_type'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Prototype</label>
                            <input type="text" name="specifications[prototype]" class="form-control" 
                                   value="{{ old('specifications.prototype', $data->formatted_specifications['prototype'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Regional Code</label>
                            <input type="text" name="specifications[regional_code]" class="form-control" 
                                   value="{{ old('specifications.regional_code', $data->formatted_specifications['regional_code'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" name="specifications[color]" class="form-control" 
                                   value="{{ old('specifications.color', $data->formatted_specifications['color'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Desire Rating</label>
                            <input type="number" name="specifications[desire]" class="form-control" min="0" max="10" 
                                   value="{{ old('specifications.desire', $data->formatted_specifications['desire'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="specifications[country]" class="form-control" 
                                   value="{{ old('specifications.country', $data->formatted_specifications['country'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Release Date</label>
                            <input type="date" name="specifications[release_date]" class="form-control" 
                                   value="{{ old('specifications.release_date', $data->formatted_specifications['release_date'] ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- External Resources -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">External Resources</h5>
            </div>
            <div class="card-body">
                <!-- External Links -->
                <div class="mb-3">
                    <label class="form-label">External Links</label>
                    <input type="text" name="external_resources[external_links]" class="form-control" 
                           placeholder="Enter URLs separated by commas" 
                           value="{{ old('external_resources.external_links', isset($data) && isset($data->formatted_resources['external_links']) ? $data->formatted_resources['external_links'] : '') }}">
                    <small class="text-muted">Multiple links should be separated by commas</small>
                </div>

                <!-- Common Faults -->
                <div class="mb-3">
                    <label class="form-label">Common Faults</label>
                    <input type="text" name="external_resources[common_faults]" class="form-control" 
                           placeholder="Enter fault descriptions separated by commas" 
                           value="{{ old('external_resources.common_faults', isset($data) && isset($data->formatted_resources['common_faults']) ? $data->formatted_resources['common_faults'] : '') }}">
                </div>

                <!-- Guides -->
                <div class="mb-3">
                    <label class="form-label">Guides</label>
                    <input type="text" name="external_resources[guides]" class="form-control" 
                           placeholder="Enter guide URLs separated by commas" 
                           value="{{ old('external_resources.guides', isset($data) && isset($data->formatted_resources['guides']) ? $data->formatted_resources['guides'] : '') }}">
                </div>

                <!-- Where to Buy -->
                <div class="mb-3">
                    <label class="form-label">Where to Buy</label>
                    <input type="text" name="external_resources[buy_links]" class="form-control" 
                           placeholder="Enter purchase URLs separated by commas" 
                           value="{{ old('external_resources.buy_links', isset($data) && isset($data->formatted_resources['buy_links']) ? $data->formatted_resources['buy_links'] : '') }}">
                </div>
            </div>
        </div>
    </div>
</div>

@push('partial_script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    const childcategorySelect = document.getElementById('childcategory_id');
    const subcategoryRequired = document.querySelector('.subcategory-required');
    const childcategoryRequired = document.querySelector('.childcategory-required');
    const subcategoryHint = document.querySelector('.subcategory-hint');
    const childcategoryHint = document.querySelector('.childcategory-hint');

    // Load subcategories when main category changes
    categorySelect.addEventListener('change', function() {
        subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        childcategorySelect.innerHTML = '<option value="">Select Child Category</option>';
        
        if (!this.value) return;
        
        fetch(`{{ route('admin.nostalgia.item.subcategories', '') }}/${this.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.subcategories.length > 0) {
                    subcategorySelect.required = true;
                    subcategoryRequired.classList.remove('d-none');
                    subcategoryHint.innerHTML = 'Subcategory selection is required for this category';
                    
                    data.subcategories.forEach(sub => {
                        subcategorySelect.add(new Option(sub.name, sub.id));
                    });
                } else {
                    subcategorySelect.required = false;
                    subcategoryRequired.classList.add('d-none');
                    subcategoryHint.innerHTML = 'No subcategories available for this category';
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Load child categories when subcategory changes
    subcategorySelect.addEventListener('change', function() {
        childcategorySelect.innerHTML = '<option value="">Select Child Category</option>';
        
        if (!this.value) return;
        
        fetch(`{{ route('admin.nostalgia.item.childcategories', '') }}/${this.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.childcategories.length > 0) {
                    childcategorySelect.required = true;
                    childcategoryRequired.classList.remove('d-none');
                    childcategoryHint.innerHTML = 'Child category selection is required for this subcategory';
                    
                    data.childcategories.forEach(child => {
                        childcategorySelect.add(new Option(child.name, child.id));
                    });
                } else {
                    childcategorySelect.required = false;
                    childcategoryRequired.classList.add('d-none');
                    childcategoryHint.innerHTML = 'No child categories available for this subcategory';
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Trigger change events on page load if values are pre-selected
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
    if (subcategorySelect.value) {
        subcategorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
