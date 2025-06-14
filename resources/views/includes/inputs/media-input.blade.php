<div class="position-relative d-inline-block">
    <div class="position-absolute top-100 start-100 translate-middle">
        <label for="{{ $inputName }}-image-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
            <div class="avatar-xs">
                <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                    <i class="ri-image-fill"></i>
                </div>
            </div>
        </label>
        <input class="form-control d-none img-file-input" value="" name="{{ $inputName }}" id="{{ $inputName }}-image-input" type="file"
            accept="image/png, image/gif, image/jpeg" >
    </div>
    <div class="avatar-lg">
        <div class="avatar-title bg-light rounded">
             <img src="{{$imagePath}}" id="product-img" class="avatar-md h-auto image-previewable" />
        </div>
    </div>
</div>

@pushOnce('partial_script')
<script type="text/javascript">
    function setupImageUpload(inputSelector, previewClass) {
        var inputs = document.querySelectorAll(inputSelector);
        var previews = document.querySelectorAll(previewClass);
        inputs.forEach(function (input, index) {
          input.addEventListener("change", function () {
            var file = input.files[0];
            var reader = new FileReader();
            reader.addEventListener("load", function () {
              previews[index].src = reader.result;
            }, false);
            if (file) {
              reader.readAsDataURL(file);
            }
          });
        });
    } 
    // Initialize ImagePreviewable      
    setupImageUpload(".img-file-input", ".image-previewable");
</script>


@endpushOnce