<div class="card">
    <div class="card-header">
        <h5 class="card-title">Service Category Form</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                <small class="text-muted">Enter a unique category name</small>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Category Photo</label>
                @include('includes.inputs.media-input', [
                    'inputName' => 'photo',
                    'inputLabel' => 'Category Photo',
                    'imagePath' => isset($category->photo) ? Helpers::image($category->photo, 'services/category/') : ''
                ])
            </div>
        </div>
    </div>
</div>
