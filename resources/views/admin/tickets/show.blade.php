@extends('admin.layouts.master')
@section('title', 'Ticket Details')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') <a href="{{ route('admin.tickets.index') }}">Support</a> @endslot
        @slot('title') Ticket Details @endslot
    @endcomponent

<div class="container-fluid p-0">
    <!-- Ticket Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="ticket-badge me-3">
                        <span class="badge-circle bg-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'pending' ? 'warning' : 'danger') }}">
                            <i class="mdi mdi-ticket"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $ticket->subject }}</h4>
                        <div class="d-flex align-items-center text-muted small">
                            <span>Ticket #{{ $ticket->id }}</span>
                            <span class="mx-2">•</span>
                            <span>Created {{ $ticket->created_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $ticket->user->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3 mt-md-0">
                    <!-- Replace dropdown with traditional select for reliable behavior -->
                    <div class="me-3">
                        <select id="statusSelect" class="form-select rounded-pill px-4" style="padding-top: 0.5rem; padding-bottom: 0.5rem; min-width: 150px;" onchange="updateStatus(this.value)">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>
                                🟢 Open
                            </option>
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>
                                🟠 Pending
                            </option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                                🔴 Closed
                            </option>
                        </select>
                    </div>
                    <button class="btn btn-outline-secondary rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#ticketInfo">
                        <i class="mdi mdi-information-outline"></i> Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Collapsed Ticket Information -->
    <div id="ticketInfo" class="collapse mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Ticket Information</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label text-muted small">Requester</div>
                                <div class="info-value">{{ $ticket->user->name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Status</div>
                                <div class="info-value">
                                    <span class="badge bg-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Created</div>
                                <div class="info-value">{{ $ticket->created_at->format('M d, Y g:i A') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Last Updated</div>
                                <div class="info-value">{{ $ticket->updated_at->format('M d, Y g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Additional Information</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label text-muted small">Email</div>
                                <div class="info-value">{{ $ticket->user->email ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Phone</div>
                                <div class="info-value">{{ $ticket->user->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Total Responses</div>
                                <div class="info-value">{{ count($replies) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted small">Resolution Time</div>
                                <div class="info-value">{{ $ticket->status === 'closed' ? $ticket->created_at->diffForHumans($ticket->updated_at, true) : 'Ongoing' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversation Area -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <!-- Messages Container -->
            <div class="conversation-container" id="conversationContainer">
                <div class="conversation-scroll p-4" style="height: 500px; overflow-y: auto;">
                    <!-- Original Ticket Message -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-wrapper me-3">
                                    <div class="avatar">
                                        <img src="{{ $ticket->user && $ticket->user->photo ? 
                                            Helpers::image($ticket->user->photo, 'user/avatar/','user.png') : 
                                            asset('assets/images/users/user.png') }}" 
                                            alt="User Avatar">
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $ticket->user->name }}</h6>
                                    <small class="text-muted">{{ $ticket->created_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                            <div class="original-message-content">
                                <p class="mb-0">{{ $ticket->description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Divider -->
                    <div class="timeline-divider my-4">
                        <span class="timeline-line"></span>
                        <span class="timeline-text bg-light px-3 text-muted">Responses</span>
                    </div>

                    <!-- Replies -->
                    @foreach($replies as $index => $reply)
                        @if($index == 0)
                            <!-- Original Message -->
                            <div class="message-item original-message">
                                <div class="d-flex">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar">
                                            <img src="{{ $reply->sender && $reply->sender->photo ? 
                                                Helpers::image($reply->sender->photo, 'user/avatar/','user.png') : 
                                                asset('assets/images/users/user.png') }}" 
                                                alt="User">
                                        </div>
                                    </div>
                                    <div class="message-content flex-grow-1">
                                        <div class="message-header d-flex align-items-center mb-2">
                                            <h6 class="mb-0">{{ $ticket->user->name }}</h6>
                                            <span class="text-muted small ms-auto">{{ $reply->created_at->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <div class="message-body">
                                            <p>{{ $reply->message }}</p>
                                            @if($reply->attachments)
                                            <div class="attachments mt-3">
                                                 @foreach(json_decode($reply->attachments, true) as $key=>$attachment)
                                                    <div class="attachment-item">
                                                        <div class="d-flex align-items-center p-2 border rounded bg-light">
                                                            <i class="mdi mdi-file-document-outline fs-5 me-2 text-primary"></i>
                                                            <div class="flex-grow-1 text-truncate">
                                                                Attachment {{ $key+1 }}
                                                            </div>
                                                            <a href="{!! Helpers::image($attachment, 'tickets/') !!}" 
                                                                class="btn btn-sm btn-light rounded-circle" download>
                                                                <i class="mdi mdi-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-divider my-4">
                                <span class="timeline-line"></span>
                                <span class="timeline-text bg-light px-3 text-muted">Responses</span>
                            </div>
                        @else
                            <!-- Responses -->
                            <div class="message-item {{ $reply->sender_type === 'admin' ? 'admin-message' : 'user-message' }}">
                                <div class="d-flex">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar {{ $reply->sender_type === 'admin' ? 'admin-avatar' : '' }}">
                                            @if($reply->sender_type === 'admin')
                                                <img src="{!! Helpers::image(Auth::guard('admin')->user()->photo, 'admin/images/','user.png') !!}" alt="Admin">
                                            @else
                                                <img src="{{ $reply->sender && $reply->sender->photo ? 
                                                    Helpers::image($reply->sender->photo, 'user/avatar/','user.png') : 
                                                    asset('assets/images/users/user.png') }}" 
                                                    alt="User">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="message-content flex-grow-1">
                                        <div class="message-header d-flex align-items-center mb-2">
                                            <h6 class="mb-0">
                                                {{ $reply->sender_type === 'admin' ? 'Support Agent' : $ticket->user->name }}
                                                @if($reply->sender_type === 'admin')
                                                    <span class="badge bg-primary ms-2 small">Staff</span>
                                                @endif
                                            </h6>
                                            <span class="text-muted small ms-auto">{{ $reply->created_at->format('M d, Y g:i A') }}</span>
                                        </div>


    <div class="message-body">
    <p>{{ $reply->message }}</p>
    @if($reply->attachments)
        <div class="attachments mt-3">
            @foreach(json_decode($reply->attachments, true) as $attachment)
                <div class="attachment-item">
                    <div class="d-flex align-items-center p-2 border rounded bg-light">
                        <i class="mdi mdi-file-document-outline me-2 text-purple-400"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            {{ $attachment }}
                                        </div>
                                        <a href="{!! Helpers::image($attachment, 'tickets/') !!}" 
                                            class="btn btn-sm btn-light rounded-full" download>
                                            <i class="mdi mdi-download"></i>
                                        </a>
                        
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    </div>



                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <div class="reply-form-container p-4 border-top">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control border-0 bg-light" 
                                      name="message" 
                                      rows="3" 
                                      placeholder="Type your reply here..."></textarea>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="attachment-input">
                                <input type="file" id="attachments" class="d-none" name="attachments[]" multiple>
                                <label for="attachments" class="btn btn-light me-2 mb-0 d-flex align-items-center">
                                    <i class="mdi mdi-paperclip me-1"></i> Attach Files
                                </label>
                            </div>
                            <div id="attachment-preview" class="d-flex flex-grow-1 me-2">
                                <!-- Attachment previews will be inserted here via JavaScript -->
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                Reply <i class="mdi mdi-send ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-4 border-top bg-light text-center">
                    <div class="d-inline-block p-3 rounded-circle bg-danger-soft text-danger mb-3">
                        <i class="mdi mdi-lock-outline fs-4"></i>
                    </div>
                    <h5 class="mb-2">This ticket is closed</h5>
                    <p class="text-muted mb-3">This support ticket has been marked as resolved and is now closed for new replies.</p>
                    <button class="btn btn-outline-secondary" onclick="updateStatus('open')">
                        <i class="mdi mdi-refresh me-1"></i> Reopen Ticket
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function updateStatus(status) {
        // alert(4);
        $.ajax({
            url: "{{ route('admin.tickets.status', $ticket->id) }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                status: status
            },
            beforeSend: function() {
                // Disable select and show loading
                $('#statusSelect').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Ticket status updated successfully');

                    // Optional: Redirect if you want
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Error updating status');
                    $('#statusSelect').prop('disabled', false);
                }
            },

            error: function(xhr) {
                // Show error toast
                toastr.error(xhr.responseJSON?.message || 'Error updating status');
                $('#statusSelect').prop('disabled', false);
            }
        });
    }
</script>
@endsection

@section('css')
<style>
    /* General styles */
    body {
        background-color: #f8f9fa;
    }
    
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    /* Ticket header */
    .badge-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }
    
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    /* Dropdown styling */
    .dropdown-menu {
        border-radius: 0.5rem;
        padding: 0.5rem;
        z-index: 9999 !important; /* Very high z-index to ensure it's on top */
        position: absolute !important;
    }
    
    .dropdown-item {
        border-radius: 0.35rem;
        padding: 0.5rem 1rem;
        position: relative;
    }
    
    .dropdown-item.active {
        background-color: #f8f9fa;
        color: #000;
        font-weight: 500;
    }
    
    /* Fix for dropdown positioning */
    .dropdown {
        position: relative;
    }
    
    /* Force dropdown to appear on top */
    .ticket-status-dropdown {
        position: initial !important;
    }
    
    .ticket-status-dropdown .dropdown-menu {
        transform: none !important;
        top: 100% !important;
        left: 0 !important;
    }
    
    /* Info panel styling */
    .info-label {
        margin-bottom: 0.25rem;
        color: #6c757d;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    /* Message styling */
    .message-item {
        margin-bottom: 2rem;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .admin-avatar {
        background-color: #e3f2fd;
        border: 2px solid #4dabf7;
    }
    
    .message-body {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }
    
    .admin-message .message-body {
        background-color: #e3f2fd;
    }
    
    .original-message .message-body {
        background-color: #fff3cd;
    }
    
    /* Timeline */
    .timeline-divider {
        position: relative;
        text-align: center;
    }
    
    .timeline-line {
        display: block;
        height: 1px;
        background-color: #dee2e6;
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        z-index: 0;
    }
    
    .timeline-text {
        position: relative;
        z-index: 1;
    }
    
    /* Attachment styling */
    .attachment-item {
        margin-bottom: 0.5rem;
    }
    
    .attachment-preview-item {
        margin-right: 0.5rem;
    }
    
    /* Custom scrollbar */
    .conversation-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .conversation-scroll::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }
    
    .conversation-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    .conversation-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }
    
    /* Status colors */
    .bg-danger-soft {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    /* Custom select styling */
    select.form-select {
        background-position: right 0.75rem center;
        transition: all 0.2s ease;
    }
    
    select.border-success {
        border-width: 2px;
    }
    
    select.border-warning {
        border-width: 2px;
    }
    
    select.border-danger {
        border-width: 2px;
    }
</style>
@endsection