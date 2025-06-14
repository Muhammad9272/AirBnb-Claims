<div class="row gy-4">
    <div class="col-lg-12">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $data->name ?? '') }}" required>
                        <small class="text-muted">Enter a unique and descriptive name for the product</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ isset($data->sku) ? $data->sku : 'PRD'.( Str::random(3).substr(time(), 6,8) ) }}" readonly>
                        <small class="text-muted">Unique product identifier (auto-generated)</small>
                    </div>
                </div>

                <!-- Categories -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $data->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose the main category for this product</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subcategory <span class="subcategory-required d-none text-danger">*</span></label>
                        <select name="subcategory_id" id="subcategory_id" class="form-control">
                            <option value="" selected disabled>Select Subcategory</option>
                            @if(isset($data->category_id))
                                @foreach($subcategories->where('parent_id', $data->category_id) as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $data->subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted subcategory-hint">Select a subcategory if available for the chosen category</small>
                    </div>
                </div>

                <!-- Product Type -->
                <div class="mb-3">
                    <label class="form-label">Product Type <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input type="radio" name="product_type" value="console" class="form-check-input" {{ old('product_type', $data->product_type ?? '') == 'console' ? 'checked' : '' }} required>
                            <label class="form-check-label">Console</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="product_type" value="accessory" class="form-check-input" {{ old('product_type', $data->product_type ?? '') == 'accessory' ? 'checked' : '' }}>
                            <label class="form-check-label">Accessory</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="product_type" value="repair_part" class="form-check-input" {{ old('product_type', $data->product_type ?? '') == 'repair_part' ? 'checked' : '' }}>
                            <label class="form-check-label">Repair Part</label>
                        </div>
                    </div>
                    <small class="text-muted">Select the type of product you're listing</small>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-muted">(Recommended)</span></label>
                    <textarea name="description" class="mlk-text-editor form-control" rows="3">{{ old('description', $data->description ?? '') }}</textarea>
                    <small class="text-muted">Provide a detailed description of the product</small>
                    @include('includes.inputs.texteditor')
                </div>

                <!-- Max Bits Allowed -->
                <div class="mb-3">
                    <label class="form-label">Max Bits Allowed <span class="text-muted">(Optional)</span></label>
                    <input type="number" name="max_bits_allowed" class="form-control" min="0" 
                           value="{{ old('max_bits_allowed', $data->max_bits_allowed ?? 10) }}">
                    <small class="text-muted">Set the maximum bits allowed for this product (defaults to 10)</small>
                </div>

                <!-- Gallery -->
                <div class="mb-3">
                    <label class="form-label">Gallery Images</label>
                    @include('includes.inputs.dropzone', [
                        'inputName' => 'gallery',
                        'imagePath' => 'products/',
                        'gallery' => isset($data->gallery) ? json_decode($data->gallery) : null
                    ])
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label class="form-label">Tags <span class="text-muted">(Optional, comma-separated)</span></label>
                    <input type="text" name="tags" class="form-control" 
                           placeholder="e.g., new, xbox, console, gaming" 
                           value="{{ old('tags', isset($data) ? $data->formatted_tags : '') }}">
                </div>

                <!-- Product Attributes -->
                <div class="mb-3">
                    <label class="form-label">Product Attributes</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input type="checkbox" name="checks[]" value="featured" class="form-check-input"
                                {{ isset($data) && is_array($data->formatted_checks) && in_array('featured', $data->formatted_checks) ? 'checked' : '' }}>
                            <label class="form-check-label">Featured</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="checks[]" value="postage_eligible" class="form-check-input"
                                {{ isset($data) && is_array($data->formatted_checks) && in_array('postage_eligible', $data->formatted_checks) ? 'checked' : '' }}>
                            <label class="form-check-label">Postage Eligible</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Pricing </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Price (£) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">£</span>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $data->price ?? '') }}" required>
                            </div>
                            <small class="text-muted">Set the base price for this product (minimum £0.01)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', $data->quantity ?? '') }}" required>
                            <small class="text-muted">Enter the available stock quantity (minimum 0)</small>
                        </div>
                    </div>
                    
                </div>

               
            </div>
        </div>

        <!-- Product Options -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Add-ons & Variations</h5>
                <div class="d-flex gap-2">
                    <select id="optionTypeSelect" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Select Option Type</option>
                        @foreach($optionTypes as $type)
                            <option value="{{ $type->id }}" data-name="{{ $type->name }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addSelectedOptionType()">Add Option</button>
                </div>
            </div>
            <div class="card-body">
                <div id="optionsContainer">
                    @if(isset($data) && !empty($data->formatted_variations))
                        @foreach($data->formatted_variations as $variation)
                            <div class="option-type-group mb-4" data-type-id="{{ $variation['option_type_id'] }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $variation['option_type_name'] }}</h6>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-success" onclick="addVariation('{{ $variation['option_type_id'] }}')">
                                            Add Value
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeOptionType(this)">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="variations-container">
                                    @foreach($variation['values'] as $value)
                                        <div class="input-group mb-2">
                                            <input type="hidden" name="option_types[]" value="{{ $variation['option_type_id'] }}">
                                            <input type="text" name="option_values[{{ $variation['option_type_id'] }}][]" 
                                                   class="form-control" placeholder="Item Name" 
                                                   value="{{ $value['value'] }}" required>
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="option_prices[{{ $variation['option_type_id'] }}][]" 
                                                   class="form-control" placeholder="Additional Price" 
                                                   value="{{ $value['additional_price'] }}" step="0.01" min="0" required>
                                            <button type="button" class="btn btn-outline-danger" onclick="removeVariation(this)">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <!-- Empty State Message -->
                <div id="emptyOptionsMessage" class="text-center py-3 {{ isset($data->formatted_variations) && !empty($data->formatted_variations) ? 'd-none' : '' }}">
                    <p class="text-muted mb-0">No options added yet. Select an option type and click "Add Option" to begin.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>

    // Category change event to load subcategories dynamically
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        const subcategoryRequired = document.querySelector('.subcategory-required');
        const subcategoryHint = document.querySelector('.subcategory-hint');

        categorySelect.addEventListener('change', function() {
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            
            const categoryId = this.value;
            if (!categoryId) return;
            
            const url = "{{ route('front.getSubcategories', ':categoryId') }}".replace(':categoryId', categoryId);  
            
            fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.subcategories.length > 0) {
                        subcategorySelect.required = true;
                        subcategoryRequired.classList.remove('d-none');
                        subcategoryHint.innerHTML = 'Subcategory selection is required for this category';
                        data.subcategories.forEach(subcategory => {
                            const option = new Option(subcategory.name, subcategory.id);
                            subcategorySelect.add(option);
                        });

                        // 🌟 Auto-select existing subcategory if editing
                        @if(old('subcategory_id', $data->subcategory_id ?? null))
                            subcategorySelect.value = "{{ old('subcategory_id', $data->subcategory_id ?? '') }}";
                        @endif

                    } else {
                        subcategorySelect.required = false;
                        subcategoryRequired.classList.add('d-none');
                        subcategoryHint.innerHTML = 'No subcategories available for this category';
                    }
                }
            })
            .catch(error => console.error('Error:', error));

        });

        // Initial check for subcategory requirement on page load
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    });

function addSelectedOptionType() {
    const select = document.getElementById('optionTypeSelect');
    const typeId = select.value;
    const typeName = select.options[select.selectedIndex].dataset.name;
    
    if (!typeId) {
        alert('Please select an option type');
        return;
    }
    
    if (document.querySelector(`.option-type-group[data-type-id="${typeId}"]`)) {
        alert('This option type is already added');
        return;
    }

    const container = document.getElementById('optionsContainer');
    const emptyMessage = document.getElementById('emptyOptionsMessage');
    emptyMessage.classList.add('d-none');
    
    container.appendChild(createOptionGroup(typeId, typeName));
    select.value = '';
}

function createOptionGroup(typeId, typeName) {
    const optionGroup = document.createElement('div');
    optionGroup.className = 'option-type-group mb-4';
    optionGroup.dataset.typeId = typeId;
    
    optionGroup.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">${typeName}</h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="addVariation('${typeId}')">
                    Add Value
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeOptionType(this)">
                    Remove
                </button>
            </div>
        </div>
        <div class="variations-container"></div>
    `;
    
    return optionGroup;
}

function addVariation(typeId) {
    const container = document.querySelector(`.option-type-group[data-type-id="${typeId}"] .variations-container`);
    const valueGroup = document.createElement('div');
    valueGroup.className = 'input-group mb-2';
    
    valueGroup.innerHTML = `
        <input type="hidden" name="option_types[]" value="${typeId}">
        <input type="text" name="option_values[${typeId}][]" 
               class="form-control" placeholder="Enter Item name" required>
        <span class="input-group-text">$</span>
        <input type="number" name="option_prices[${typeId}][]" 
               class="form-control" placeholder="Additional Price" step="0.01" min="0" required>
        <button type="button" class="btn btn-outline-danger" onclick="removeVariation(this)">×</button>
    `;
    
    container.appendChild(valueGroup);
}

function removeVariation(button) {
    button.closest('.input-group').remove();
}

function removeOptionType(button) {
    const optionGroup = button.closest('.option-type-group');
    optionGroup.remove();
    
    const container = document.getElementById('optionsContainer');
    const emptyMessage = document.getElementById('emptyOptionsMessage');
    if (container.children.length === 0) {
        emptyMessage.classList.remove('d-none');
    }
}
</script>

@endsection
