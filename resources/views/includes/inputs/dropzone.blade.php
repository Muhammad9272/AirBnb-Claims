@pushOnce('partial_css')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

<style type="text/css">
    .dropzone .dz-preview .dz-image img {
        width: 130px;
        height: 130px;
        border-radius: 8px;
        object-fit: cover;
        margin-top: 10px;
    }

    .dz-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        margin: 10px;
        width: 150px;
        background-color: #f9f9f9;
    }

    .dz-preview .dz-main-image {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 8px;
        font-size: 0.9em;
    }
    .dz-main-image label{
        display: flex;
        align-items: center;
        margin-bottom:0px;
    }

    .dz-preview .dz-main-image input[type="radio"] {
        margin-right: 5px;
    }

    .dz-preview .dz-details {
        text-align: center;
        font-size: 0.85em;
        color: #666;
    }
</style>
@endPushOnce

<div class="dropzone" style="min-height: 150px;" id="document-dropzone">
    <div class="dz-message needsclick">
        <div class="mb-1">
            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
        </div>
        <h5>{{ __('Drop Files here or click to upload') }}</h5>
    </div>
</div>
<!-- Hidden input to store the main image name -->
<input type="hidden" name="main_image" id="main-image">

@pushOnce('partial_script')    
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script> 
    function initializeDropzone(element) {
        if (element.length) {
            var uploadedDocumentMap = {};
            var dropzone = new Dropzone(element.get(0), {
                url: '{{ route('dropzone.storeMedia') }}',
                maxFilesize: 5, // Maximum file size in MB
                addRemoveLinks: true,
                acceptedFiles: '{{ config('fileformats.galleryimages') }}',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (file, response) {
                    $('form').append('<input type="hidden" name="gallery[]" class="galimages" value="' + response.name + '">');
                    uploadedDocumentMap[file.name] = response.name;

                    // Add a "Set as Main Image" radio button for each file

                    const mainImageHtml = `<div class="dz-main-image ">
                        <label>
                            <input type="radio" name="main_image_radio" data-file-name="${response.name}" 
                            onchange="setMainImage('${response.name}')">
                            {{ __('Thumbnail') }}
                        </label>
                    </div>`;

                    $(file.previewElement).append(mainImageHtml);
                },
                removedfile: function (file) {
                    file.previewElement.remove();
                    var name = uploadedDocumentMap[file.name] || file.file_name;
                    $('form').find('input[class="galimages"][value="' + name + '"]').remove();

                    if ($('#main-image').val() === name) {
                        $('#main-image').val('');
                    }
                },
                init: function () {
                   @if(isset($data) && $data->gallery)
                        // Parse gallery data as JSON if it’s in string format
                        var files = {!! is_string($data->gallery) ? json_encode(json_decode($data->gallery)) : json_encode($data->gallery) !!};
                        var theis = this;
                        var videoFormats = {!! json_encode(config('fileformats.video')) !!};

                        files.forEach(function(file) {
                            var filename = "{{ url('/') }}" + '/assets/dynamic/images/{{$imagePath}}' + file;
                            var fileExtension = filename.split('.').pop().toLowerCase();
                            var isVideo = videoFormats.includes(fileExtension);
                            var thumbnailImage = isVideo ? "{{ asset('/images/video.png') }}" : filename;

                            // Mock file object for Dropzone
                            var filedata = { name: filename, file_name: file, size: 12345 };

                            // Original functionality to display file preview
                            theis.options.addedfile.call(theis, filedata);
                            theis.options.thumbnail.call(theis, filedata, thumbnailImage);
                            filedata.previewElement.classList.add('dz-complete');
                            $('form').append('<input type="hidden" name="old[]" class="galimages" value="' + file + '">');

                            // Add "Set as Main Image" radio button functionality
                            const mainImageHtml = `<div class="dz-main-image">
                                <label>
                                    <input type="radio" name="main_image_radio" data-file-name="${file}" onchange="setMainImage('${file}')">
                                    {{ __('Thumbnail') }}
                                </label>
                            </div>`;
                            $(filedata.previewElement).append(mainImageHtml);

                            // Pre-check the main image if it matches the saved main image
                            if (file === "{{ $data->main_image ?? '' }}") {
                                $('#main-image').val(file);
                                $(filedata.previewElement).find('input[type="radio"]').prop('checked', true);
                            }
                        });
                    @endif

                }
            });
        }
    }

    function setMainImage(fileName) {
        $('#main-image').val(fileName); // Update hidden input with main image name
    }

    initializeDropzone($('#document-dropzone'));
</script>
@endPushOnce
