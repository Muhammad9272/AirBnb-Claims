@extends('admin.layouts.master')
@section('title') Claim Details #{{ $claim->id }} @endsection
@section('css')
<style>
    .timeline-item {
        position: relative;
        padding-left: 45px;
        margin-bottom: 20px;
    }
    
    .timeline-item:before {
        content: "";
        position: absolute;
        left: 22px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item:last-child:before {
        bottom: 50%;
    }
    
    .timeline-icon {
        position: absolute;
        left: 10px;
        top: 0;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    
    .evidence-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }
    
    .evidence-item {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .evidence-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .evidence-preview {
        height: 150px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .evidence-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Chat Styles */
    .chat-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
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
        background: #405189;
        color: white;
    }

    .chat-avatar.user {
        background: #0ab39c;
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
        color: #495057;
    }

    .chat-message.admin .chat-username {
        color: #405189;
    }

    .chat-timestamp {
        font-size: 0.75rem;
        color: #6c757d;
        background: rgba(0,0,0,0.05);
        padding: 0.125rem 0.5rem;
        border-radius: 0.75rem;
        white-space: nowrap;
    }

    .chat-bubble {
        padding: 0.875rem 1rem;
        border-radius: 1rem;
        position: relative;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .chat-message.admin .chat-bubble {
        background: #405189;
        color: white;
        border-bottom-right-radius: 0.375rem;
        margin-left: auto;
    }

    .chat-message.user .chat-bubble {
        background: white;
        color: #495057;
        border: 1px solid #e9ecef;
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
        background: #405189;
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
        border: 1px solid #e9ecef;
        border-radius: 0 0 0 1rem;
        border-top: none;
        border-right: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.pending { background: #e6f3ff; color: #0969da; }
    .status-badge.under_review { background: #f0e6ff; color: #8b5cf6; }
    .status-badge.approved { background: #e6ffe6; color: #16a34a; }
    .status-badge.rejected { background: #ffe6e6; color: #dc2626; }

    /* Status History Timeline Styles */
    .status-timeline {
        position: relative;
        padding: 1rem 0;
    }

    .status-timeline::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #405189, #e9ecef);
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
        border: 1px solid #e9ecef;
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
        color: #495057;
        margin: 0;
        flex-grow: 1;
    }

    .status-timeline-time {
        font-size: 0.8rem;
        color: #6c757d;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }

    .status-timeline-user {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .status-timeline-notes {
        background: #f8f9fa;
        border-left: 3px solid #405189;
        padding: 0.75rem;
        margin-top: 0.75rem;
        border-radius: 0 0.375rem 0.375rem 0;
        font-size: 0.9rem;
        color: #495057;
    }

    /* Status Update Card Improvements */
    .status-update-card {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .status-update-header {
        background: linear-gradient(135deg, #405189, #4a5fa0);
        color: white;
        padding: 1rem;
        border-bottom: none;
    }

    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        appearance: none;
        padding-right: 2.5rem;
    }

    .form-select:focus {
        border-color: #405189;
        box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25);
        outline: 0;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #405189;
        box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25);
    }

    .commission-preview {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        color: #495057;
        margin-top: 0.5rem;
    }

    /* Icon colors for status timeline */
    .status-icon-pending { background: #17a2b8; }
    .status-icon-under_review { background: #6f42c1; }
    .status-icon-approved { background: #28a745; }
    .status-icon-rejected { background: #dc3545; }
    .status-icon-default { background: #6c757d; }
</style>
@endsection

@section('content')
@php
use App\CentralLogics\Helpers;
@endphp

@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.claims.index') }}">Claims</a> @endslot
@slot('title') Claim #{{ $claim->claim_number }} @endslot
@endcomponent

<div class="row">
    <div class="col-xl-8">
        <!-- Claim Details Card -->
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Claim Details</h5>
                <div class="flex-shrink-0">
                    @php
                        $statusBadgeClass = match($claim->status) {
                            'pending' => 'badge bg-info',
                            'under_review' => 'badge bg-primary',
                            'approved' => 'badge bg-success',
                            'rejected' => 'badge bg-danger',
                            default => 'badge bg-secondary'
                        };
                    @endphp
                    <span class="{{ $statusBadgeClass }} fs-12">{{ ucfirst(str_replace('_', ' ', $claim->status)) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 25%;">Claim Number</th>
                                <td>{{ $claim->claim_number }}</td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td>{{ $claim->title }}</td>
                            </tr>
                            <tr>
                                <th>Amount Requested</th>
                                <td>${{ number_format($claim->amount_requested, 2) }}</td>
                            </tr>
                            @if($claim->status === 'approved')
                            <tr>
                                <th>Amount Approved</th>
                                <td>${{ number_format($claim->amount_approved, 2) }}</td>
                            </tr>
                            @endif
                            {{-- <tr>
                                <th>Property Address</th>
                                <td>{{ $claim->property_address }}</td>
                            </tr> --}}
                            <tr>
                                <th>Airbnb Reservation Code</th>
                                <td>{{ $claim->airbnb_reservation_code }}</td>
                            </tr>
                            <tr>
                                <th>Stay Period</th>
                                <td>{{ $claim->getCheckInDateFormatted() }} - {{ $claim->getCheckOutDateFormatted() }}</td>
                            </tr>
                            <tr>
                                <th>Incident Date</th>
                                <td>{{ $claim->getIncidentDateFormatted() }}</td>
                            </tr>
                            <tr>
                                <th>Guest Information</th>
                                <td>
                                    <strong>Name:</strong> {{ $claim->guest_name }}<br>
                                    @if($claim->guest_email)
                                    <strong>Email:</strong> {{ $claim->guest_email }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>{{ $claim->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Updated</th>
                                <td>{{ $claim->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h5 class="fs-14 mb-3">Description</h5>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($claim->description)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Evidence Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Evidence Files</h5>
            </div>
            <div class="card-body">
                @if($claim->evidence->count() > 0)
                <div class="evidence-grid">
                    @foreach($claim->evidence as $evidence)
                        <div class="evidence-item">
                            <div class="evidence-preview">
                                @if($evidence->isImage())
                                    <a href="{{ Helpers::image($evidence->file_path, 'claims/') }}" target="_blank">
                                        <img src="{{ Helpers::image($evidence->file_path, 'claims/') }}" alt="Evidence">
                                    </a>
                                @else
                                    <div class="text-center">
                                        <i class="ri-file-text-line fs-24 text-muted"></i>
                                        <p class="mb-0 mt-2 fs-12">{{ strtoupper(pathinfo($evidence->file_name, PATHINFO_EXTENSION)) }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2">
                                <p class="text-truncate mb-1 fs-13" title="{{ $evidence->file_name }}">{{ $evidence->file_name }}</p>
                                <p class="mb-0 text-muted fs-12">
                                    {{ $evidence->created_at->format('M d, Y') }}
                                    <span class="text-muted"> • </span>
                                    <a href="{{ Helpers::image($evidence->file_path, 'claims/') }}" download class="link-primary fs-11">Download</a>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary fs-24">
                            <i class="ri-file-text-line"></i>
                        </div>
                    </div>
                    <h5>No Evidence Files</h5>
                    <p class="text-muted">No evidence files have been uploaded for this claim.</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Status History Card -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="ri-history-line me-2 text-primary"></i>Status History
                </h5>
            </div>
            <div class="card-body">
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
                                    'pending' => 'ri-time-line',
                                    'under_review' => 'ri-search-line',
                                    'approved' => 'ri-check-line',
                                    'rejected' => 'ri-close-line',
                                    default => 'ri-record-circle-line'
                                };
                            @endphp
                            <div class="status-timeline-icon {{ $iconClass }}">
                                <i class="{{ $icon }} text-white"></i>
                            </div>
                            <div class="status-timeline-content">
                                <div class="status-timeline-header">
                                    <h6 class="status-timeline-title">
                                        Status changed to 
                                        <span class="status-badge {{ $status->to_status }}">
                                            {{ ucfirst(str_replace('_', ' ', $status->to_status)) }}
                                        </span>
                                    </h6>
                                    <span class="status-timeline-time">
                                        {{ $status->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <div class="status-timeline-user">
                                    <i class="ri-user-line me-1"></i>
                                    Changed by <strong>{{ $status->user->name ?? 'System' }}</strong>
                                </div>
                                @if($status->notes)
                                    <div class="status-timeline-notes">
                                        <i class="ri-chat-quote-line me-2"></i>{{ $status->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                            <i class="ri-history-line"></i>
                        </div>
                    </div>
                    <h5>No Status History</h5>
                    <p class="text-muted">No status changes have been recorded for this claim yet.</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Comments/Chat Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-chat-3-line me-2"></i>Comments & Discussion
                </h5>
            </div>
            <div class="card-body p-0">
                @if($claim->comments->count() > 0)
                <div class="chat-container">
                    @foreach($claim->comments->sortBy('created_at') as $comment)
                        <div class="chat-message {{ ($comment->is_admin ?? false) ? 'admin' : 'user' }}">
                            <div class="chat-avatar {{ ($comment->is_admin ?? false) ? 'admin' : 'user' }}">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}

                            </div>
                            <div class="chat-content">
                                <div class="chat-header">
                                    <span class="chat-username">{{ ucfirst($comment->user->name ?? 'Unknown User') }}
                                    </span>
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
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary fs-24">
                            <i class="ri-chat-1-line"></i>
                        </div>
                    </div>
                    <h5>No Comments Yet</h5>
                    <p class="text-muted">Start the conversation by adding a comment below.</p>
                </div>
                @endif
                
                <!-- Add Comment Form -->
                <div class="chat-input-container m-3">
                    <form action="{{ route('admin.claims.comment', $claim->id) }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2">
                            <div class="chat-avatar admin">
                                 
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1) )}}
                            </div>
                            <div class="flex-grow-1">
                                <textarea class="form-control border-0 resize-none" name="content" rows="2" placeholder="Type your message here..." style="box-shadow: none;"></textarea>
                            </div>
                            <div class="align-self-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ri-send-plane-line"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <!-- User Information Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                @if($claim->user)
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        @if($claim->user->photo)
                            <img src="{{ Helpers::image($claim->user->photo, 'user/avatar/', 'user.png') }}" alt="User Avatar" class="avatar-lg rounded-circle">
                        @else
                            <div class="avatar-lg rounded-circle bg-soft-primary text-primary">
                                <span class="avatar-title">{{ substr($claim->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">{{ $claim->user->name }}</h5>
                        <p class="text-muted mb-0">{{ $claim->user->email }}</p>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 40%;">Member Since</th>
                                <td>{{ $claim->user->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Total Claims</th>
                                <td>{{ $claim->user->claims->count() }}</td>
                            </tr>
                            <tr>
                                <th>Subscription</th>
                                <td>
                                    @php
                                        $activeSubscription = $claim->user->activeuserSubscriptions()->first();
                                    @endphp
                                    
                                    @if($activeSubscription)
                                        <span class="badge bg-soft-success text-success">
                                            {{ $activeSubscription->plan->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger">No active plan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Commission Rate</th>
                                <td>
                                    @if($activeSubscription && $activeSubscription->plan)
                                        {{ $activeSubscription->plan->commission_percentage }}%
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.users.show', $claim->user->id) }}" class="btn btn-soft-primary btn-sm w-50">
                        <i class="ri-user-line me-1"></i> View Profile
                    </a>
                    <a href="{{ route('admin.claims.user-claims', $claim->user->id) }}" class="btn btn-soft-info btn-sm w-50">
                        <i class="ri-file-list-line me-1"></i> User Claims
                    </a>
                </div>
                @else
                <div class="text-center py-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-danger fs-24">
                            <i class="ri-error-warning-line"></i>
                        </div>
                    </div>
                    <h5>User Not Found</h5>
                    <p class="text-muted">The user associated with this claim does not exist or has been deleted.</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Update Status Card -->
        <div class="card status-update-card">
            <div class="card-header status-update-header border-0">
                <h5 class="card-title mb-0 text-white">
                    <i class="ri-edit-line me-2"></i>Update Status
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.claims.update-status', $claim->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4 ">
                        <label for="status" class="form-label fw-semibold">Change Status</label>
                        <select class="form-select" id="claim_status" name="status">
                            <option value="pending" {{ $claim->status === 'pending' ? 'selected' : '' }}>
                                📋 Pending
                            </option>
                            <option value="under_review" {{ $claim->status === 'under_review' ? 'selected' : '' }}>
                                🔍 Under Review
                            </option>
                            <option value="approved" {{ $claim->status === 'approved' ? 'selected' : '' }}>
                                ✅ Approved
                            </option>
                            <option value="rejected" {{ $claim->status === 'rejected' ? 'selected' : '' }}>
                                ❌ Rejected
                            </option>
                        </select>
                    </div>
                    
                    <div id="approved-fields" class="mb-4 {{ $claim->status === 'approved' ? '' : 'd-none' }}">
                        <label for="approved_amount" class="form-label fw-semibold">Approved Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="approved_amount" name="approved_amount" 
                                   min="0" step="0.01" value="{{ $claim->amount_approved ?? $claim->amount_requested }}">
                        </div>
                        <div class="commission-preview mt-2">
                            <div class="d-flex justify-content-between">
                                <span>Commission ({{ $activeSubscription->plan->commission_percentage ?? 0 }}%):</span>
                                <strong id="commission-preview">$0.00</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div id="rejected-fields" class="mb-4 {{ $claim->status === 'rejected' ? '' : 'd-none' }}">
                        <label for="rejection_reason" class="form-label fw-semibold">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                  placeholder="Please provide a detailed reason for rejection...">{{ $claim->rejection_reason }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="comment" class="form-label fw-semibold">Status Change Note</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="Add a note about this status change (optional)"></textarea>
                        <small class="text-muted">This note will be visible in the status history.</small>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-refresh-line me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-flashlight-line me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.claims.index') }}" class="btn btn-soft-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to Claims List
                    </a>
                    
                    @if($claim->status === 'pending')
                    <button class="btn btn-soft-primary" id="btn-start-review">
                        <i class="ri-search-line me-1"></i> Start Review
                    </button>
                    @endif
                    
                    @if($claim->status === 'under_review')
                    <button class="btn btn-soft-warning" id="btn-request-evidence">
                        <i class="ri-file-upload-line me-1"></i> Request More Evidence
                    </button>
                    @endif
                    
                    <button class="btn btn-soft-info" id="btn-add-comment">
                        <i class="ri-chat-3-line me-1"></i> Add Quick Comment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Handle status change
        $('#claim_status').on('change', function() {
            const status = $(this).val();
            
            // Toggle approved fields
            if (status === 'approved') {
                $('#approved-fields').removeClass('d-none');
            } else {
                $('#approved-fields').addClass('d-none');
            }
            
            // Toggle rejected fields
            if (status === 'rejected') {
                $('#rejected-fields').removeClass('d-none');
            } else {
                $('#rejected-fields').addClass('d-none');
            }
        });
        
        // Calculate commission preview
        $('#approved_amount').on('input', function() {
            const amount = parseFloat($(this).val()) || 0;
            const commissionRate = {{ $activeSubscription->plan->commission_percentage ?? 0 }};
            const commission = amount * (commissionRate / 100);
            
            $('#commission-preview').text('$' + commission.toFixed(2));
        });
        
        // Trigger calculation on page load
        $('#approved_amount').trigger('input');
        
        // Quick action buttons
        $('#btn-start-review').on('click', function() {
            $('#claim_status').val('under_review');
            $('#comment').val('Starting detailed review of this claim.');
            $('form').first().submit();
        });
        
        $('#btn-request-evidence').on('click', function() {
            // Focus on comment textarea in chat
            $('textarea[name="content"]').focus().val('Additional evidence is required to process this claim. Please upload supporting documentation.');
        });

        $('#btn-add-comment').on('click', function() {
            // Focus on comment textarea in chat
            $('textarea[name="content"]').focus();
        });

        // Auto-scroll chat to bottom
        const chatContainer = $('.chat-container');
        if (chatContainer.length) {
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }

        // Auto-resize textarea
        $('textarea[name="content"]').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endsection
