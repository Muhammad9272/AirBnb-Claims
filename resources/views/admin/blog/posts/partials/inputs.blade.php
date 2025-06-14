<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <!-- Title Field -->
                <div class="mb-3">
                    <label class="form-label">Post Title <span class="text-danger">*</span></label>
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

                <!-- Summary -->
                <div class="mb-3">
                    <label class="form-label">Summary</label>
                    <textarea name="summary" class="form-control" rows="3">{{ old('summary', $data->summary ?? '') }}</textarea>
                </div>

                <!-- Content -->
                <div class="mb-3">
                    <label class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control mlk-text-editor" rows="6">{{ old('content', $data->content ?? '') }}</textarea>
                </div>

                <!-- Featured Image -->
                <div class="mb-3">
                    <label class="form-label">Featured Image</label>
                    @include('includes.inputs.media-input', [
                        'inputName' => 'photo',
                        'inputLabel' => 'Blog Photo',
                        'imagePath' => isset($data->photo) ? Helpers::image($data->photo, 'blog/') : ''
                    ])
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           placeholder="Enter tags separated by commas" 
                           value="{{ old('tags', $data->formatted_tags ?? '') }}">
                </div>

                <!-- Featured Post -->
               {{--  <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="featured" class="form-check-input" 
                               value="1" {{ old('featured', $data->featured ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label">Featured Post</label>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@include('includes.inputs.texteditor')
