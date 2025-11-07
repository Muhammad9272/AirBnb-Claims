@extends('front.layouts.app')
@section('meta_title') Claim #{{ $claim->claim_number }} @endsection

@section('content')
@php
use App\CentralLogics\Helpers;
@endphp

<style>
    /* Status History Timeline Styles */
    .status-timeline {
        position: relative;
        padding: 1.5rem 0;
    }

    .status-timeline::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #3b82f6, #e5e7eb);
        border-radius: 1.5px;
    }

    .status-timeline-item {
        position: relative;
        padding-left: 70px;
        margin-bottom: 2rem;
    }

    .status-timeline-item:last-child {
        margin-bottom: 0;
    }

    .status-timeline-icon {
        position: absolute;
        left: 15px;
        top: 8px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 2;
    }

    .status-timeline-content {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }

    .status-timeline-content:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .status-timeline-header {
        display: flex;
        align-items: center;
        justify-content: between;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .status-timeline-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
        flex-grow: 1;
    }

    .status-timeline-time {
        font-size: 0.8rem;
        color: #6b7280;
        background: #f9fafb;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }

    .status-timeline-user {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .status-timeline-notes {
        background: #f9fafb;
        border-left: 3px solid #3b82f6;
        padding: 0.75rem;
        margin-top: 0.75rem;
        border-radius: 0 0.375rem 0.375rem 0;
        font-size: 0.9rem;
        color: #374151;
    }

    /* Chat Styles */
    .chat-container {
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.75rem;
    }

    .chat-message {
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .chat-message.admin {
        flex-direction: row-reverse;
    }

    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .chat-avatar.admin {
        background: #3b82f6;
        color: white;
    }

    .chat-avatar.user {
        background: #10b981;
        color: white;
    }

    .chat-content {
        max-width: 75%;
        flex-grow: 1;
    }

    .chat-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .chat-message.admin .chat-header {
        justify-content: flex-end;
        flex-direction: row-reverse;
    }

    .chat-username {
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
    }

    .chat-message.admin .chat-username {
        color: #3b82f6;
    }

    .chat-timestamp {
        font-size: 0.75rem;
        color: #6b7280;
        background: rgba(0,0,0,0.05);
        padding: 0.125rem 0.5rem;
        border-radius: 0.75rem;
        white-space: nowrap;
    }

    .chat-bubble {
        padding: 0.875rem 1.125rem;
        border-radius: 1.125rem;
        position: relative;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .chat-message.admin .chat-bubble {
        background: #3b82f6;
        color: white;
        border-bottom-right-radius: 0.375rem;
        margin-left: auto;
    }

    .chat-message.user .chat-bubble {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.375rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .chat-message.admin .chat-bubble::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: -5px;
        width: 10px;
        height: 10px;
        background: #3b82f6;
        border-radius: 0 0 1rem 0;
    }

    .chat-message.user .chat-bubble::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: -5px;
        width: 10px;
        height: 10px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0 0 0 1rem;
        border-top: none;
        border-right: none;
    }

    /* Status Icons */
    .status-icon-pending { background: #3b82f6; }
    .status-icon-under_review { background: #8b5cf6; }
    .status-icon-approved { background: #10b981; }
    .status-icon-rejected { background: #ef4444; }
    .status-icon-default { background: #6b7280; }

    /* Enhanced Status Badges */
    .status-badge-enhanced {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 1.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge-pending { 
        background: linear-gradient(135deg, #dbeafe, #bfdbfe); 
        color: #1d4ed8; 
        border: 1px solid #93c5fd;
    }
    .status-badge-under_review { 
        background: linear-gradient(135deg, #ede9fe, #ddd6fe); 
        color: #7c3aed; 
        border: 1px solid #c4b5fd;
    }
    .status-badge-approved { 
        background: linear-gradient(135deg, #d1fae5, #a7f3d0); 
        color: #059669; 
        border: 1px solid #6ee7b7;
    }
    .status-badge-rejected { 
        background: linear-gradient(135deg, #fee2e2, #fecaca); 
        color: #dc2626; 
        border: 1px solid #fca5a5;
    }
</style>

<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Header with claim info and status -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h1 class="text-2xl font-bold text-gray-800">Claim #{{ $claim->claim_number }}</h1>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-blue-100 text-blue-800',
                                            'under_review' => 'bg-purple-100 text-purple-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'paid' => 'bg-emerald-100 text-emerald-800',
                                        ];
                                        
                                        $class = $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $class }}">
                                        {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Submitted on {{ $claim->created_at->format('F d, Y') }}</p>
                            </div>
                            <a href="{{ route('user.claims.index') }}" class="text-accent hover:text-accent-dark flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Claims
                            </a>
                        </div>
                    </div>

                    @include('includes.alerts')

                    <!-- Claim details with better organization -->
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $claim->title }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Financial Information Card -->
                            <div class="md:col-span-1 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3 border-b pb-2">Financial Details</h3>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Amount Requested</dt>
                                        <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($claim->amount_requested, 2) }}</dd>
                                    </div>
                                    @if($claim->status === 'approved' && $claim->amount_approved)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Amount Approved</dt>
                                        <dd class="mt-1 text-lg font-semibold text-green-600">${{ number_format($claim->amount_approved, 2) }}</dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                            
                            <!-- Reservation Details Card -->
                            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3 border-b pb-2">Reservation Details</h3>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Reservation Code</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $claim->airbnb_reservation_code }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Guest Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $claim->guest_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Check-in / Check-out</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $claim->getCheckInDateFormatted() }} - {{ $claim->getCheckOutDateFormatted() }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Incident Date</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $claim->getIncidentDateFormatted() }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <!-- Property Information Card -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3 border-b pb-2">Property Information</h3>
                            <p class="text-sm text-gray-900">{{ $claim->property_address }}</p>
                        </div>
                        
                        <!-- Claim Description Card -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3 border-b pb-2">Claim Description</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($claim->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Evidence Section with better layout -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Evidence Files</h3>
                        
                        @if(!in_array($claim->status, ['approved', 'rejected', 'paid']))
                        <div class="space-x-2 flex ">
                            <button id="add-evidence-btn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Evidence
                            </button>

                            <button id="add-video-evidence-btn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Video Evidence
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        @if($claim->evidence->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($claim->evidence as $evidence)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 relative">
                                    @php
                                        $extension = pathinfo($evidence->file_name, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                        $isVideo = $evidence->is_video;
                                    @endphp

                                    @if($isVideo)
                                        {{-- Cross icon for video evidence --}}
                                        @if(!in_array($claim->status, ['approved', 'rejected', 'paid']))
                                        <button 
                                            class="absolute top-2 right-2 z-10 bg-white rounded-full p-1 shadow hover:bg-red-100 transition"
                                            title="Delete this video evidence"
                                            onclick="deleteEvidence({{ $evidence->id }}, this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        @endif
                                        <div class="h-40 bg-black flex items-center justify-center">
                                            <video class="h-full w-full object-cover" controls>
                                                <source src="{{ asset('assets/dynamic/claims/videos/' . $evidence->file_path) }}" type="video/{{ strtolower($extension) }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @elseif($isImage && !$isVideo)
                                        <a href="{{ Helpers::image($evidence->file_path, 'claims/') }}" target="_blank" class="block h-32 bg-gray-100 overflow-hidden">
                                            <img src="{{ Helpers::image($evidence->file_path, 'claims/') }}" alt="Evidence {{ $loop->iteration }}" class="h-full w-full object-cover hover:scale-105 transition-transform duration-200">
                                        </a>

                                    @else
                                        <div class="h-32 flex items-center justify-center bg-gray-100">
                                            <div class="text-center p-4">
                                                @php
                                                    $iconClass = match(strtolower($extension)) {
                                                        'pdf' => 'text-red-500',
                                                        'doc', 'docx' => 'text-blue-500',
                                                        'xls', 'xlsx' => 'text-green-500',
                                                        'ppt', 'pptx' => 'text-orange-500',
                                                        default => 'text-gray-500'
                                                    };
                                                @endphp
                                                <svg class="h-8 w-8 {{ $iconClass }} mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-xs mt-2 font-medium {{ $iconClass }}">{{ strtoupper($extension) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="p-3 bg-white">
                                        <p class="text-xs text-gray-600 truncate font-medium" title="{{ $evidence->file_name }}">{{ $evidence->file_name }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs text-gray-500">{{ $evidence->created_at->format('M d, Y') }}</p>

                                            @if($isVideo)
                                                {{-- Video download link --}}
                                                <a href="{{ asset('assets/dynamic/claims/videos/' . $evidence->file_path) }}" 
                                                download="{{ $evidence->file_name }}" 
                                                class="text-xs text-accent hover:text-accent-dark flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>
                                            @else
                                                {{-- Other files download --}}
                                                <a href="{{ Helpers::image($evidence->file_path, 'claims/') }}" 
                                                download="{{ $evidence->file_name }}" 
                                                class="text-xs text-accent hover:text-accent-dark flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            </div>
                        @else
                            <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No evidence files have been uploaded yet.</p>
                                @if(!in_array($claim->status, ['approved', 'rejected', 'paid']))
                                    <button id="empty-add-evidence-btn" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add First Evidence
                                    </button>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Add Evidence Form (hidden by default) -->
                        <div id="evidence-form" class="hidden mt-6 pt-4 border-t border-gray-200">
                            <form action="{{ route('user.claims.evidence', $claim->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                @csrf
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Upload Evidence Files</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                        <textarea id="description" name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="files" class="block text-sm font-medium text-gray-700">Files</label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <label for="files" class="relative cursor-pointer bg-white rounded-md font-medium text-accent hover:text-accent-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-accent">
                                                        <span>Upload files</span>
                                                        <input id="files" name="files[]" type="file" multiple class="sr-only" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    You can upload multiple files (JPG, PNG, PDF, DOC up to 10MB each)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" id="cancel-evidence" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Cancel
                                        </button>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark">
                                            Upload Evidence
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="video-upload-section" class="hidden mt-6 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Upload Video Evidence</h4>
                            <div class="space-y-4">
                                <div>
                                    <label for="video-description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                    <textarea id="video-description" name="border video_description" rows="2" class="border mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm"></textarea>
                                </div>
                                <div>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-accent hover:text-accent-dark">
                                                    <span>Upload video</span>
                                                    <input type="file" id="video-upload" accept="video/mp4,video/mov,video/avi,video/wmv" class="sr-only" data-claim-id="new">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Max video file size: 30MB. Accepted formats: MP4, MOV, AVI, WMV
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancel-video-evidence" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Cancel
                                    </button>
                                </div>
                                <div class="mt-2 hidden" id="upload-progress">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Uploading: <span id="progress-text">0%</span></p>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status History Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status History
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        @if($claim->statusHistory->count() > 0)
                        <div class="status-timeline">
                            @foreach($claim->statusHistory->sortByDesc('created_at') as $status)
                                <div class="status-timeline-item">
                                    @php
                                        $iconClass = match($status->to_status) {
                                            'pending' => 'status-icon-pending',
                                            'under_review' => 'status-icon-under_review',
                                            'approved' => 'status-icon-approved',
                                            'rejected' => 'status-icon-rejected',
                                            default => 'status-icon-default'
                                        };
                                        
                                        $icon = match($status->to_status) {
                                            'pending' => '⏳',
                                            'under_review' => '🔍',
                                            'approved' => '✅',
                                             'rejected' => '❌',
                                            default => '⚪'
                                        };
                                    @endphp
                                    <div class="status-timeline-icon {{ $iconClass }}">
                                        <span class="text-white">{{ $icon }}</span>
                                    </div>
                                    <div class="status-timeline-content">
                                        <div class="status-timeline-header">
                                            <h6 class="status-timeline-title">
                                                Status changed to 
                                                <span class="status-badge-enhanced status-badge-{{ $status->to_status }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status->to_status)) }}
                                                </span>
                                            </h6>
                                            <span class="status-timeline-time">
                                                {{ $status->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        <div class="status-timeline-user">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Changed by <strong>{{ $status->user->name ?? 'System' }}</strong>
                                        </div>
                                        @if($status->notes)
                                            <div class="status-timeline-notes">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                                {{ $status->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">No Status History</h5>
                            <p class="text-gray-500">No status changes have been recorded for this claim yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Communication & Comments Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Comments & Discussion
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        @if($claim->comments->count() > 0)
                        <div class="chat-container">
                            @foreach($claim->comments->sortBy('created_at') as $comment)
                                @php
                                    // In user view: current user messages on right, admin/others on left
                                    $isCurrentUser = $comment->user_id === auth()->id();
                                @endphp
                                <div class="chat-message {{ $isCurrentUser ? 'admin' : 'user' }}">
                                    <div class="chat-avatar {{ $isCurrentUser ? 'admin' : 'user' }}">
                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="chat-content">
                                        <div class="chat-header">
                                            <span class="chat-username">{{ ucfirst($comment->user->name ?? 'Unknown User') }}</span>
                                            <span class="chat-timestamp">{{ $comment->created_at->format('M d, h:i A') }}</span>
                                        </div>
                                        <div class="chat-bubble">
                                            {!! nl2br(e($comment->comment)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">No Comments Yet</h5>
                            <p class="text-gray-500">Start the conversation by adding a comment below.</p>
                        </div>
                        @endif
                        
                        @if(!in_array($claim->status, ['approved', 'rejected', 'paid']))
                            <!-- Add Comment Form -->
                            <div class="chat-input-container">
                                <form action="{{ route('user.claims.comment', $claim->id) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-3">
                                        <div class="chat-avatar user">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="flex-grow-1 w-full">
                                            <textarea name="content" id="content" rows="2" required 
                                                class="w-full border-0 resize-none focus:ring-0 focus:outline-none bg-gray-50 rounded-lg p-3" 
                                                placeholder="Type your message here..."
                                                style="box-shadow: none;"></textarea>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit" class="bg-accent hover:bg-accent-dark text-white p-2 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle evidence form visibility
    document.getElementById('add-evidence-btn')?.addEventListener('click', function() {
        document.getElementById('evidence-form').classList.remove('hidden');
        this.classList.add('hidden');
    });

    document.getElementById('add-video-evidence-btn')?.addEventListener('click', function() {
        document.getElementById('video-upload-section').classList.remove('hidden');
        this.classList.add('hidden');
        document.getElementById('add-evidence-btn')?.classList.add('hidden');
    });
    
    document.getElementById('empty-add-evidence-btn')?.addEventListener('click', function() {
        document.getElementById('evidence-form').classList.remove('hidden');
        this.classList.add('hidden');
    });
    
    document.getElementById('cancel-evidence')?.addEventListener('click', function() {
        document.getElementById('evidence-form').classList.add('hidden');
        const addBtn = document.getElementById('add-evidence-btn');
        const emptyAddBtn = document.getElementById('empty-add-evidence-btn');
        
        if (addBtn) addBtn.classList.remove('hidden');
        if (emptyAddBtn) emptyAddBtn.classList.remove('hidden');
    });
    
    document.getElementById('cancel-video-evidence')?.addEventListener('click', function() {
        document.getElementById('video-upload-section').classList.add('hidden');
        document.getElementById('add-video-evidence-btn')?.classList.remove('hidden');
        document.getElementById('add-evidence-btn')?.classList.remove('hidden');
        document.getElementById('video-upload').value = '';
        document.getElementById('video-description').value = '';
        document.getElementById('upload-progress').classList.add('hidden');
        updateProgressBar(0);
    });

    // Auto-scroll chat to bottom
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.querySelector('.chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });

    // Auto-resize textarea
    document.querySelector('textarea[name="content"]')?.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
<script>
    // Video evidence form show/hide logic
    document.getElementById('add-video-evidence-btn')?.addEventListener('click', function() {
        document.getElementById('video-upload-section').classList.remove('hidden');
        this.classList.add('hidden');
        document.getElementById('add-evidence-btn')?.classList.add('hidden');
    });

    document.getElementById('cancel-video-evidence')?.addEventListener('click', function() {
        document.getElementById('video-upload-section').classList.add('hidden');
        document.getElementById('add-video-evidence-btn')?.classList.remove('hidden');
        document.getElementById('add-evidence-btn')?.classList.remove('hidden');
        document.getElementById('video-upload').value = '';
        document.getElementById('video-description').value = '';
        document.getElementById('upload-progress').classList.add('hidden');
        updateProgressBar(0);
    });

    // Prevent multiple uploads at once
    let videoUploading = false;

    function updateProgressBar(progress) {
        const progressBar = document.querySelector('#upload-progress div > div');
        const progressText = document.getElementById('progress-text');
        if (progressBar && progressText) {
            progressBar.style.width = progress + '%';
            progressText.textContent = progress + '%';
        }
    }

    function showUploadComplete() {
        updateProgressBar(100);
        const progressText = document.getElementById('progress-text');
        if (progressText) {
            progressText.textContent = 'Upload complete!';
        }
        setTimeout(() => {
            document.getElementById('upload-progress').classList.add('hidden');
            document.getElementById('video-upload').value = '';
            document.getElementById('video-description').value = '';
            videoUploading = false;
            console.log('Video evidence uploaded successfully!');
            document.getElementById('video-upload-section').classList.add('hidden');
            document.getElementById('add-video-evidence-btn')?.classList.remove('hidden');
            document.getElementById('add-evidence-btn')?.classList.remove('hidden');
            location.reload();
        }, 1200);

    }

    function showUploadError(msg) {
        const progressText = document.getElementById('progress-text');
        if (progressText) {
            alert(msg || 'Upload failed. Please try again.');
            return;
        }
        setTimeout(() => {
            document.getElementById('upload-progress').classList.add('hidden');
            videoUploading = false;
        }, 2000);
    }

    function uploadVideoInChunks(file, claimId, description) {
        const allowedTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/wmv'];
        if (!allowedTypes.includes(file.type)) {
            showUploadError('Invalid file type.');
            return;
        }
        const chunkSize = 2 * 1024 * 1024; // 2MB chunks
        const totalChunks = Math.ceil(file.size / chunkSize);
        let currentChunk = 0;
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2); // convert bytes → MB and keep 2 decimals
        console.log("Step 2", fileSizeMB + " MB", chunkSize, totalChunks);

        if (fileSizeMB > 30) {
            showUploadError('Video size must not exceed 30MB');
            return;
        }

        document.getElementById('upload-progress').classList.remove('hidden');
        updateProgressBar(0);
        videoUploading = true;

        const uploadChunk = async () => {
            const start = currentChunk * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('chunk', currentChunk);
            formData.append('totalChunks', totalChunks);
            formData.append('filename', file.name);
            formData.append('claim_id', claimId);
            if (currentChunk === 0) {
                formData.append('description', description);
            }

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
                    const progress = Math.round(((currentChunk + 1) / totalChunks) * 100);
                    updateProgressBar(progress);

                    currentChunk++;
                    if (currentChunk < totalChunks) {
                        uploadChunk();
                    } else {
                        showUploadComplete();
                    }
                } else {
                    showUploadError(result.message || 'Upload failed. Please try again.');
                }
            } catch (error) {
                console.error('Upload failed:', error);
                showUploadError();
            }
        };

        uploadChunk();
    }

    document.querySelector('#video-upload')?.addEventListener('change', (e) => {
        if (videoUploading) {
            alert('A video is already uploading. Please wait.');
            return;
        }
        const file = e.target.files[0];
        const claimId = e.target.getAttribute('data-claim-id');
        const description = document.getElementById('video-description').value;
        if (file && claimId) {
            console.log("Step 1");
            uploadVideoInChunks(file, claimId, description);
        }
    });

    function deleteEvidence(evidenceId, btn) {
        btn.disabled = true;
        btn.classList.add('opacity-50');
        fetch(`/user/claims/evidence/${evidenceId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove the evidence card from the UI
                btn.closest('.border.rounded-lg').remove();
            } else {
                alert(data.message || 'Failed to delete evidence.');
                btn.disabled = false;
                btn.classList.remove('opacity-50');
            }
        })
        .catch(() => {
            alert('Failed to delete evidence.');
            btn.disabled = false;
            btn.classList.remove('opacity-50');
        });
    }
</script>
@endpush
@endsection
