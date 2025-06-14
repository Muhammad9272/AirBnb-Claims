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
                                        <div class="col-span-2">
                                            <label for="property_address" class="block text-sm font-medium text-gray-700 mb-1">Property Address <span class="text-red-500">*</span></label>
                                            <textarea name="property_address" id="property_address" rows="2" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Full address of the Airbnb property">{{ old('property_address') }}</textarea>
                                            @error('property_address')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                        <div>
                                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1">Guest Email</label>
                                            <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                                placeholder="Email address of the guest">
                                            @error('guest_email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
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
                                        
                                        <!-- Evidence Files -->
                                        <div>
                                            <label for="evidence_files" class="block text-sm font-medium text-gray-700 mb-1">Evidence Files</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600 justify-center">
                                                        <label for="evidence_files" class="relative cursor-pointer bg-white rounded-md font-medium text-accent hover:text-accent-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-accent">
                                                            <span>Upload files</span>
                                                            <input id="evidence_files" name="evidence_files[]" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        Upload photos, documents, or other evidence (JPG, PNG, PDF, DOC up to 10MB each)
                                                    </p>
                                                </div>
                                            </div>
                                            <div id="file-preview" class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
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
    // File preview functionality
    document.getElementById('evidence_files').addEventListener('change', function(e) {
        const filePreviewDiv = document.getElementById('file-preview');
        filePreviewDiv.innerHTML = '';
        
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'border rounded-lg p-2 flex flex-col items-center';
                
                // Check if image file
                const isImage = file.type.startsWith('image/');
                
                if (isImage) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.height = '80px';
                    img.style.width = 'auto';
                    img.style.objectFit = 'contain';
                    fileDiv.appendChild(img);
                } else {
                    // For non-image files, show icon
                    const icon = document.createElement('div');
                    icon.className = 'flex items-center justify-center bg-gray-100 h-20 w-20 rounded-lg';
                    
                    let iconType;
                    if (file.type.includes('pdf')) {
                        iconType = 'PDF';
                    } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                        iconType = 'DOC';
                    } else {
                        iconType = 'FILE';
                    }
                    
                    icon.innerHTML = `<span class="text-gray-500 font-medium">${iconType}</span>`;
                    fileDiv.appendChild(icon);
                }
                
                const fileName = document.createElement('p');
                fileName.className = 'mt-1 text-xs text-gray-500 truncate w-full text-center';
                fileName.textContent = file.name;
                fileDiv.appendChild(fileName);
                
                filePreviewDiv.appendChild(fileDiv);
            };
            
            reader.readAsDataURL(file);
        });
    });
    
    // Date validation and defaults
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation for check-in and check-out
        document.getElementById('check_in_date').addEventListener('change', function() {
            document.getElementById('check_out_date').min = this.value;
        });
        
        // Auto-fill incident date based on check-in date
        document.getElementById('check_in_date').addEventListener('change', function() {
            if (!document.getElementById('incident_date').value) {
                document.getElementById('incident_date').value = this.value;
            }
        });
    });
</script>
@endpush
@endsection
