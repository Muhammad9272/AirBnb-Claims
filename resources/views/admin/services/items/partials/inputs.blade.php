<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <!-- Title Field -->
                <div class="mb-3">
                    <label class="form-label">Service Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $data->title ?? '') }}" required>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $data->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

               

                <!-- Content -->
                <div class="mb-3">
                    <label class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control mlk-text-editor" rows="6">{{ old('content', $data->content ?? '') }}</textarea>

                    @include('includes.inputs.texteditor')
                </div>

                <!-- Gallery -->
                <div class="mb-3">
                    <label class="form-label">Gallery Images</label>
                    @include('includes.inputs.dropzone', [
                        'inputName' => 'gallery',
                        'imagePath' => 'services/',
                        'gallery' => isset($data->gallery) ? json_decode($data->gallery) : null
                    ])
                </div>

                <!-- Service Packages -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Service Packages</h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addPackage()">
                            <i class="fas fa-plus me-1"></i>Add Package
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="packages-container">
                            @if(isset($data) && !empty(json_decode($data->items)))
                                @foreach(json_decode($data->items, true) as $package)
                                    <div class="package-group mb-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" name="package_labels[]" class="form-control" 
                                                       placeholder="Package Label (e.g., Basic, Premium)" 
                                                       value="{{ $package['label'] }}">
                                            </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="package_prices[]" class="form-control" 
                                                           placeholder="Package Price" step="0.01" min="0"
                                                           value="{{ $package['price'] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-package">
                                                    <i class=" las la-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <small class="text-muted">Add different service packages with their respective prices</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('partial_script')
<script>
function addPackage() {
    const container = document.getElementById('packages-container');
    const packageHtml = `
        <div class="package-group mb-3">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="package_labels[]" class="form-control" 
                           placeholder="Package Label (e.g., Basic, Premium)" required>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="package_prices[]" class="form-control" 
                               placeholder="Package Price" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-package">
                        <i class=" las la-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', packageHtml);
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-package')) {
        e.target.closest('.package-group').remove();
    }
});
</script>
@endpush
