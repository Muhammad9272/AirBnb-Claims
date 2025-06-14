<div class="card">
    <div class="card-header">
        <h5 class="card-title">Category Form</h5>
    </div>
    <div class="card-body">

            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Category Name" value="{{ old('name', $category->name ?? '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter Description">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Category Photo</label>
                        <!-- Include the photo upload partial -->
                        @include('includes.inputs.media-input', ['inputName' => 'photo', 'imagePath' => isset($category->photo) ? Helpers::image($category->photo, 'category_photos/') : ''])
                    </div>
                </div>
            </div>


    </div>
</div>
