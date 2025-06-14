<div class="card">
    <div class="card-header">
        <h5 class="card-title">Nostalgia Category Form</h5>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                    <small class="text-muted">Enter a unique category name</small>
                </div>
            </div>
            
            @if(!isset($category))
            {{-- Only show level and parent selection for new categories --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Category Level <span class="text-danger">*</span></label>
                    <select name="level" id="level_select" class="form-control" required>
                        <option value="1">Main Category</option>
                        <option value="2">Sub Category</option>
                        <option value="3">Child Category</option>
                    </select>
                    <small class="text-muted">Select the category level (cannot be changed later)</small>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="mb-3 parent-category-section d-none">
                    <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                    <div class="parent-selects">
                        <select name="parent_id" id="parent_category_select" class="form-control">
                            <option value="">Select Parent Category</option>
                        </select>
                    </div>
                    <small class="text-muted parent-hint">Select the appropriate parent category (cannot be changed later)</small>
                </div>
            </div>
            @else
            {{-- Show read-only info for existing categories --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Category Level</label>
                    <input type="text" class="form-control" value="{{ $category->level == 1 ? 'Main Category' : ($category->level == 2 ? 'Sub Category' : 'Child Category') }}" readonly>
                    @if($category->parent)
                    <small class="text-muted">Under: {{ $category->parent->name }}</small>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Category Photo</label>
                    @include('includes.inputs.media-input', [
                        'inputName' => 'photo', 
                        'inputLabel' => 'Category Photo',
                        'imagePath' => isset($category->photo) ? Helpers::image($category->photo, 'nostalgia/category_photos/') : ''
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

@push('partial_script')
<script>
// Only initialize level change handlers for new category creation
@if(!isset($category))
document.addEventListener('DOMContentLoaded', function() {
    const levelSelect = document.getElementById('level_select');
    const parentSection = document.querySelector('.parent-category-section');
    const parentSelect = document.getElementById('parent_category_select');
    const parentHint = document.querySelector('.parent-hint');

    levelSelect.addEventListener('change', handleLevelChange);
    
    function handleLevelChange() {
        const level = parseInt(levelSelect.value);
        
        if (level === 1) {
            parentSection.classList.add('d-none');
            parentSelect.removeAttribute('required');
            parentSelect.value = '';
        } else {
            parentSection.classList.remove('d-none');
            parentSelect.setAttribute('required', 'required');
            loadParentCategories(level);
        }
    }

    function loadParentCategories(level) {
        const currentId = '{{ $category->id ?? "" }}';
        const requiredParentLevel = level === 3 ? 2 : 1; // For level 3, we need level 2 parents

        fetch(`{{ route('admin.nostalgia.category.parents') }}?level=${level}&current_id=${currentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    parentSelect.innerHTML = '<option value="">Select Parent Category</option>';
                    data.categories.forEach(category => {
                        const option = new Option(category.name, category.id);
                        parentSelect.add(option);
                    });

                    updateParentHint(level);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                parentHint.innerHTML = 'Error loading parent categories';
            });
    }

    function updateParentHint(level) {
        if (level === 2) {
            parentHint.innerHTML = 'Select a main category as parent';
        } else if (level === 3) {
            parentHint.innerHTML = 'Select a sub-category as parent';
        }
    }

    // Initial load if editing
    if (levelSelect.value > 1) {
        handleLevelChange();
    }
});
@endif
</script>
@endpush
