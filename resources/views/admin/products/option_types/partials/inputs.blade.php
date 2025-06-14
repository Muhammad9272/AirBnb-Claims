<div class="row gy-4">
    <!-- Left Column (Main Product Info) -->
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <!-- Product Name -->
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Xbox Series X, PlayStation 5" value="{{ isset($data->name) ? $data->name : '' }}" required>
                </div>

                <!-- Category & Subcategory -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Microsoft" {{ isset($data->category) && $data->category == 'Microsoft' ? 'selected' : '' }}>Microsoft</option>
                            <option value="Sony" {{ isset($data->category) && $data->category == 'Sony' ? 'selected' : '' }}>Sony</option>
                            <option value="Nintendo" {{ isset($data->category) && $data->category == 'Nintendo' ? 'selected' : '' }}>Nintendo</option>
                            <option value="Sega" {{ isset($data->category) && $data->category == 'Sega' ? 'selected' : '' }}>Sega</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subcategory <span class="text-muted">(Optional)</span></label>
                        <select name="subcategory" class="form-control">
                            <option value="">Select Subcategory</option>
                            <option value="Xbox Series X" {{ isset($data->subcategory) && $data->subcategory == 'Xbox Series X' ? 'selected' : '' }}>Xbox Series X</option>
                            <option value="PS4" {{ isset($data->subcategory) && $data->subcategory == 'PS4' ? 'selected' : '' }}>PS4</option>
                            <option value="Switch" {{ isset($data->subcategory) && $data->subcategory == 'Switch' ? 'selected' : '' }}>Switch</option>
                        </select>
                    </div>
                </div>

                <!-- Product Type -->
                <div class="mb-3">
                    <label class="form-label">Product Type</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input type="radio" name="product_type" value="console" class="form-check-input" {{ isset($data->product_type) && $data->product_type == 'console' ? 'checked' : '' }} required>
                            <label class="form-check-label">Console</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="product_type" value="accessory" class="form-check-input" {{ isset($data->product_type) && $data->product_type == 'accessory' ? 'checked' : '' }}>
                            <label class="form-check-label">Accessory</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="product_type" value="repair_part" class="form-check-input" {{ isset($data->product_type) && $data->product_type == 'repair_part' ? 'checked' : '' }}>
                            <label class="form-check-label">Repair Part</label>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Product Description</label>
                    <textarea name="description" class="mlk-text-editor form-control" placeholder="Enter Product Description" rows="3" required>{{ isset($data->description) ? $data->description : '' }}</textarea>
                      @include('includes.inputs.texteditor')
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label class="form-label">Tags <span class="text-muted">(Optional, comma-separated)</span></label>
                    <input type="text" name="tags" class="form-control" placeholder="e.g., new, xbox, console, gaming" value="{{ isset($data->tags) ? $data->tags : '' }}">
                </div>
            </div>
        </div>

        <!-- Product Options -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Options</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addOption()">Add Option</button>
            </div>
            <div class="card-body">
                <div id="optionsContainer">
                    <!-- Dynamic options will be added here -->
                    @if(isset($data->options))
                        @foreach(json_decode($data->options) as $option)
                            <div class="option-row mb-3">
                                <div class="input-group">
                                    <input type="text" name="option_names[]" class="form-control" placeholder="Option name" value="{{ $option->name }}">
                                    <input type="text" name="option_values[]" class="form-control" placeholder="Values (comma-separated)" value="{{ implode(',', $option->values) }}">
                                    <button type="button" class="btn btn-danger" onclick="removeOption(this)">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Pricing -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Pricing & Stock</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Regular Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ isset($data->price) ? $data->price : '' }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Discounted Price <span class="text-muted">(Optional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="discounted_price" class="form-control" step="0.01" min="0" value="{{ isset($data->discounted_price) ? $data->discounted_price : '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity Available</label>
                    <input type="number" name="quantity" class="form-control" min="0" value="{{ isset($data->quantity) ? $data->quantity : '' }}" required>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ isset($data->is_featured) && $data->is_featured ? 'checked' : '' }}>
                        <label class="form-check-label">Featured Product</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="postage_eligible" class="form-check-input" value="1" {{ isset($data->postage_eligible) && $data->postage_eligible ? 'checked' : '' }}>
                        <label class="form-check-label">Eligible for Postage Page</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ isset($data->status) && $data->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="out_of_stock" {{ isset($data->status) && $data->status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="discontinued" {{ isset($data->status) && $data->status == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Media</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if(isset($data->image))
                        <div class="mt-2">
                            <img src="{{ asset('storage/products/' . $data->image) }}" class="img-thumbnail" width="150" alt="Product Image">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
function addOption() {
    const container = document.getElementById('optionsContainer');
    const optionRow = document.createElement('div');
    optionRow.className = 'option-row mb-3';
    optionRow.innerHTML = `
        <div class="input-group">
            <input type="text" name="option_names[]" class="form-control" placeholder="Option name">
            <input type="text" name="option_values[]" class="form-control" placeholder="Values (comma-separated)">
            <button type="button" class="btn btn-danger" onclick="removeOption(this)">Remove</button>
        </div>
    `;
    container.appendChild(optionRow);
}

function removeOption(button) {
    button.closest('.option-row').remove();
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
});
</script>
@endsection
