@extends('front.layouts.app')
@section('meta_title') Create New Claim @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Create New Claim</h1>
                        <a href="{{ route('user.claims.index') }}" class="inline-flex items-center text-accent hover:text-accent-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Claims
                        </a>
                    </div>

                    @include('includes.alerts')

                    <div class="p-6">
                        <form action="{{ route('user.claims.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Basic Claim Information Section -->
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Claim Information</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Title -->
                                        <div class="col-span-2">
                                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Claim Title <span class="text-red-500">*</span></label>
                                            <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Brief description of your claim">
                                            @error('title')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Claim Number - Auto-generated but showing for reference -->
                                        <div>
                                            <label for="claim_number" class="block text-sm font-medium text-gray-700 mb-1">Claim Number</label>
                                            <input type="text" id="claim_number" disabled
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-gray-500 placeholder-gray-400"
                                                placeholder="Will be auto-generated">
                                        </div>
                                        
                                        <!-- Amount Requested -->
                                        <div>
                                            <label for="amount_requested" class="block text-sm font-medium text-gray-700 mb-1">Claim Amount ($) <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">$</span>
                                                </div>
                                                <input type="number" name="amount_requested" id="amount_requested" value="{{ old('amount_requested') }}" min="1" step="0.01" required
                                                    class="w-full pl-8 px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                    placeholder="0.00">
                                            </div>
                                            @error('amount_requested')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Property and Reservation Information Section -->
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 mb-4">Property & Reservation Information</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Property Address -->
                                        {{-- <div class="col-span-2">
                                            <label for="property_address" class="block text-sm font-medium text-gray-700 mb-1">Property Address <span class="text-red-500">*</span></label>
                                            <textarea name="property_address" id="property_address" rows="2" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Full address of the Airbnb property">{{ old('property_address') }}</textarea>
                                            @error('property_address')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div> --}}
                                        
                                        <!-- Airbnb Reservation Code -->
                                        <div>
                                            <label for="airbnb_reservation_code" class="block text-sm font-medium text-gray-700 mb-1">Airbnb Reservation Code <span class="text-red-500">*</span></label>
                                            <input type="text" name="airbnb_reservation_code" id="airbnb_reservation_code" value="{{ old('airbnb_reservation_code') }}" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="e.g. HM2NWXZ4R2">
                                            @error('airbnb_reservation_code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Incident Date -->
                                        <div>
                                            <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-1">Incident Date <span class="text-red-500">*</span></label>
                                            <input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                                            @error('incident_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Check-in Date -->
                                        <div>
                                            <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date <span class="text-red-500">*</span></label>
                                            <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date') }}" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                                            @error('check_in_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Check-out Date -->
                                        <div>
                                            <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date <span class="text-red-500">*</span></label>
                                            <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date') }}" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                                            @error('check_out_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Guest Information Section -->
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 mb-4">Guest Information</h2>
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Guest Name -->
                                        <div>
                                            <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">Guest Name <span class="text-red-500">*</span></label>
                                            <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Full name of the guest">
                                            @error('guest_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Guest Email -->
                                        {{-- <div>
                                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1">Guest Email</label>
                                            <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Email address of the guest">
                                            @error('guest_email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div> --}}
                                    </div>
                                </div>
                                
                                <!-- Claim Details Section -->
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 mb-4">Claim Details</h2>
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Description -->
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Claim Description <span class="text-red-500">*</span></label>
                                            <textarea name="description" id="description" rows="5" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Please provide detailed information about your claim, including what happened, the extent of the damage or issue, and any other relevant details...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Evidence Files - FIXED VERSION -->
                                        <div>
                                            <label for="evidence_files" class="block text-sm font-medium text-gray-700 mb-1">
                                                Evidence Files 
                                                <span id="file-count-container" class="ml-2 text-sm text-gray-500" style="display: none;">
                                                    (<span id="file-count">0</span> files selected)
                                                </span>
                                            </label>
                                            
                                            <!-- Click trigger button -->
                                            <button type="button" id="file-upload-trigger" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-gray-400 transition-colors cursor-pointer w-full bg-white hover:bg-gray-50">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600 justify-center">
                                                        <span class="font-medium text-accent hover:text-accent-dark">
                                                            Upload files
                                                        </span>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        Upload photos, documents, or other evidence
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        JPG, PNG, GIF, PDF, DOC, DOCX up to 10MB each
                                                    </p>
                                                </div>
                                            </button>
                                            
                                            <!-- Hidden file input -->
                                            <input type="file" name="evidence_files[]" id="evidence_files" class="hidden" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                            
                                            <!-- File Preview Area -->
                                            <div id="file-preview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"></div>
                                            
                                            <!-- Instructions -->
                                            <div class="mt-2 text-xs text-gray-500">
                                                <p>• Click the area above to select files from your device</p>
                                                <p>• You can also drag and drop files directly onto the upload area</p>
                                                <p>• Multiple files can be selected - they will be added to your collection</p>
                                                <p>• Hover over uploaded files to see the remove button (×)</p>
                                            </div>
                                            
                                            @error('evidence_files.*')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 text-right">
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-200">
                                    Submit Claim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store accumulated files
    let accumulatedFiles = [];
    
    // Get elements
    const fileInput = document.getElementById('evidence_files');
    const uploadTrigger = document.getElementById('file-upload-trigger');
    const filePreview = document.getElementById('file-preview');
    const fileCountContainer = document.getElementById('file-count-container');
    const fileCount = document.getElementById('file-count');
    
    // Simple click handler - most important fix
    uploadTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Upload button clicked');
        fileInput.click();
    });
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        console.log('File input changed, files:', this.files.length);
        if (this.files.length > 0) {
            const newFiles = Array.from(this.files);
            addFiles(newFiles);
        }
    });
    
    // Drag and drop handlers
    uploadTrigger.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('border-accent', 'bg-accent/5');
    });
    
    uploadTrigger.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-accent', 'bg-accent/5');
    });
    
    uploadTrigger.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-accent', 'bg-accent/5');
        
        console.log('Files dropped:', e.dataTransfer.files.length);
        if (e.dataTransfer.files.length > 0) {
            const droppedFiles = Array.from(e.dataTransfer.files);
            addFiles(droppedFiles);
        }
    });
    
    // Add files function
    function addFiles(newFiles) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        let validFiles = [];
        let errors = [];
        
        newFiles.forEach(file => {
            // Check file type
            if (!allowedTypes.includes(file.type) && !isValidExtension(file.name)) {
                errors.push(`"${file.name}" is not a supported file type.`);
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                errors.push(`"${file.name}" is too large (max 10MB).`);
                return;
            }
            
            // Check for duplicates
            if (accumulatedFiles.some(f => f.name === file.name && f.size === file.size)) {
                errors.push(`"${file.name}" is already selected.`);
                return;
            }
            
            validFiles.push(file);
        });
        
        if (errors.length > 0) {
            alert('Some files could not be added:\n' + errors.join('\n'));
        }
        
        if (validFiles.length > 0) {
            accumulatedFiles = accumulatedFiles.concat(validFiles);
            updateDisplay();
            updateFileInput();
            console.log('Added files, total:', accumulatedFiles.length);
        }
    }
    
    // Check file extension
    function isValidExtension(filename) {
        const validExt = ['.jpg', '.jpeg', '.png', '.gif', '.pdf', '.doc', '.docx'];
        const ext = filename.toLowerCase().substring(filename.lastIndexOf('.'));
        return validExt.includes(ext);
    }
    
    // Update file input
    function updateFileInput() {
        try {
            const dt = new DataTransfer();
            accumulatedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
        } catch (error) {
            console.error('Error updating file input:', error);
        }
    }
    
    // Update display
    function updateDisplay() {
        // Update file count
        if (accumulatedFiles.length > 0) {
            fileCountContainer.style.display = 'inline';
            fileCount.textContent = accumulatedFiles.length;
        } else {
            fileCountContainer.style.display = 'none';
        }
        
        // Update preview
        filePreview.innerHTML = '';
        accumulatedFiles.forEach((file, index) => {
            const fileDiv = createFilePreview(file, index);
            filePreview.appendChild(fileDiv);
        });
    }
    
    // Create file preview
    function createFilePreview(file, index) {
        const div = document.createElement('div');
        div.className = 'border rounded-lg p-2 relative group bg-white hover:shadow-md transition-shadow';
        
        // Remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity z-10 font-bold';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = () => removeFile(index);
        div.appendChild(removeBtn);
        
        // File content
        const isImage = file.type.startsWith('image/');
        if (isImage) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-20 object-cover rounded';
            img.onload = () => URL.revokeObjectURL(img.src);
            div.appendChild(img);
        } else {
            const icon = document.createElement('div');
            icon.className = 'h-20 flex items-center justify-center bg-gray-100 rounded text-sm font-bold';
            if (file.type.includes('pdf')) {
                icon.className += ' text-red-600';
                icon.textContent = 'PDF';
            } else {
                icon.className += ' text-blue-600';
                icon.textContent = 'DOC';
            }
            div.appendChild(icon);
        }
        
        // File name
        const name = document.createElement('p');
        name.className = 'text-xs mt-1 truncate font-medium';
        name.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
        name.title = file.name;
        div.appendChild(name);
        
        // File size
        const size = document.createElement('p');
        size.className = 'text-xs text-gray-500';
        size.textContent = formatFileSize(file.size);
        div.appendChild(size);
        
        return div;
    }
    
    // Remove file
    function removeFile(index) {
        if (index >= 0 && index < accumulatedFiles.length) {
            accumulatedFiles.splice(index, 1);
            updateDisplay();
            updateFileInput();
        }
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Date validation and defaults - EXISTING FUNCTIONALITY
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const incidentDate = document.getElementById('incident_date');
    
    if (checkInDate) {
        checkInDate.addEventListener('change', function() {
            if (checkOutDate) {
                checkOutDate.min = this.value;
                
                // If check-out date is before check-in date, clear it
                if (checkOutDate.value && checkOutDate.value < this.value) {
                    checkOutDate.value = '';
                }
            }
            
            // Auto-fill incident date based on check-in date if not already set
            if (incidentDate && !incidentDate.value) {
                incidentDate.value = this.value;
            }
        });
    }
    
    // Ensure check-out date is after check-in date
    if (checkOutDate) {
        checkOutDate.addEventListener('change', function() {
            if (checkInDate && checkInDate.value && this.value < checkInDate.value) {
                alert('Check-out date cannot be before check-in date.');
                this.value = '';
            }
        });
    }
    
    console.log('File upload and form functionality initialized');
});
</script>
@endpush
@endsection