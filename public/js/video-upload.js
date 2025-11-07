document.addEventListener('DOMContentLoaded', function() {
    const videoUploadBtn = document.getElementById('add-video-evidence-btn');
    const videoUploadForm = document.getElementById('video-upload-form');
    const videoDropZone = document.getElementById('video-drop-zone');
    const videoInput = document.getElementById('video-file');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadStatus = document.getElementById('upload-status');
    const uploadProgress = document.getElementById('upload-progress');
    const uploadActions = document.getElementById('upload-actions');
    const removeButton = document.getElementById('remove-video');
    const submitButton = document.getElementById('submit-video');
    
    let currentFile = null;
    let uploadedChunks = 0;
    let totalChunks = 0;

    // Show video upload form
    videoUploadBtn?.addEventListener('click', function() {
        videoUploadForm.classList.remove('hidden');
        this.classList.add('hidden');
    });

    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        videoDropZone.classList.add('border-accent');
    }

    function unhighlight() {
        videoDropZone.classList.remove('border-accent');
    }

    // Handle file selection
    videoDropZone.addEventListener('drop', handleDrop, false);
    videoInput.addEventListener('change', handleFileSelect);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        handleFile(file);
    }

    function handleFileSelect(e) {
        const file = e.target.files[0];
        handleFile(file);
    }

    function handleFile(file) {
        if (!file || !file.type.startsWith('video/')) {
            alert('Please select a valid video file');
            return;
        }

        if (file.size > 30 * 1024 * 1024) {
            alert('File size must not exceed 30MB');
            return;
        }

        currentFile = file;
        startUpload();
    }

    function startUpload() {
        const chunkSize = 2 * 1024 * 1024; // 2MB chunks
        totalChunks = Math.ceil(currentFile.size / chunkSize);
        uploadedChunks = 0;

        // Show progress UI
        videoDropZone.classList.add('hidden');
        uploadProgress.classList.remove('hidden');
        uploadActions.classList.add('hidden');

        uploadChunk();
    }

    async function uploadChunk() {
        const chunkSize = 2 * 1024 * 1024;
        const start = uploadedChunks * chunkSize;
        const end = Math.min(start + chunkSize, currentFile.size);
        const chunk = currentFile.slice(start, end);

        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunk', uploadedChunks);
        formData.append('totalChunks', totalChunks);
        formData.append('filename', currentFile.name);
        formData.append('claim_id', document.querySelector('[data-claim-id]').dataset.claimId);
        formData.append('description', document.getElementById('video-description').value);

        try {
            const response = await fetch('/upload/chunk', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                uploadedChunks++;
                const progress = Math.round((uploadedChunks / totalChunks) * 100);
                updateProgress(progress);

                if (uploadedChunks < totalChunks) {
                    uploadChunk();
                } else {
                    uploadComplete(result);
                }
            }
        } catch (error) {
            console.error('Upload failed:', error);
            uploadStatus.textContent = 'Upload failed';
            uploadStatus.classList.add('text-red-600');
        }
    }

    function updateProgress(progress) {
        progressBar.style.width = `${progress}%`;
        progressText.textContent = `${progress}%`;
    }

    function uploadComplete(result) {
        uploadStatus.textContent = 'Upload complete!';
        uploadStatus.classList.add('text-green-600');
        uploadActions.classList.remove('hidden');
    }

    // Handle remove button
    removeButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove this video?')) {
            // Reset the form
            videoInput.value = '';
            currentFile = null;
            uploadedChunks = 0;
            totalChunks = 0;
            videoDropZone.classList.remove('hidden');
            uploadProgress.classList.add('hidden');
            uploadActions.classList.add('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
            uploadStatus.textContent = 'Uploading...';
            uploadStatus.classList.remove('text-green-600', 'text-red-600');
        }
    });

    // Handle submit button
    submitButton.addEventListener('click', function() {
        window.location.reload();
    });
});
