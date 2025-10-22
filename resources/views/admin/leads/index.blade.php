@extends('admin.layouts.master')
@section('title', 'Leads Management')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.leads.index') }}">Leads</a>
        @endslot
        @slot('title')
            Management
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h4 class="card-title mb-0">Leads Management</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.leads.export', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="ri-download-line"></i> Export CSV
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card card-sm border-0 bg-primary bg-opacity-10 mb-0">
                                <div class="card-body text-center p-2 p-sm-3">
                                    <h5 class="card-title text-primary mb-1 fs-6 fs-sm-5">{{ $stats['total_leads'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Total Leads</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card card-sm border-0 bg-info bg-opacity-10 mb-0">
                                <div class="card-body text-center p-2 p-sm-3">
                                    <h5 class="card-title text-info mb-1 fs-6 fs-sm-5">{{ $stats['new_leads'] }}</h5>
                                    <p class="card-text text-muted small mb-0">New Leads</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card card-sm border-0 bg-success bg-opacity-10 mb-0">
                                <div class="card-body text-center p-2 p-sm-3">
                                    <h5 class="card-title text-success mb-1 fs-6 fs-sm-5">{{ $stats['converted_leads'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Converted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card card-sm border-0 bg-secondary bg-opacity-10 mb-0">
                                <div class="card-body text-center p-2 p-sm-3">
                                    <h5 class="card-title text-secondary mb-1 fs-6 fs-sm-5">{{ $stats['registered_leads'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Registered</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card card-sm border-0 bg-dark bg-opacity-10 mb-0">
                                <div class="card-body text-center p-2 p-sm-3">
                                    <h5 class="card-title text-dark mb-1 fs-6 fs-sm-5">{{ $stats['discount_used_leads'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Used Discount</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.leads.index') }}">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label small">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, Email, Phone..." value="{{ request('search') }}">
                            </div>
                            {{-- <div class="col-6 col-md-6 col-lg-2">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                                </select>
                            </div> --}}
                            <div class="col-6 col-md-6 col-lg-2">
                                <label class="form-label small">Registration</label>
                                <select name="is_registered" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_registered') === '1' ? 'selected' : '' }}>Registered</option>
                                    <option value="0" {{ request('is_registered') === '0' ? 'selected' : '' }}>Not Registered</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-6 col-lg-2">
                                <label class="form-label small">Source</label>
                                <select name="source" class="form-select form-select-sm">
                                    <option value="">All Sources</option>
                                    <option value="referral" {{ request('source') === 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="instagram" {{ request('source') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="google_ads" {{ request('source') === 'google_ads' ? 'selected' : '' }}>Google Ads</option>
                                    <option value="facebook" {{ request('source') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="twitter" {{ request('source') === 'twitter' ? 'selected' : '' }}>Twitter</option>
                                    <option value="youtube" {{ request('source') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                    <option value="tiktok" {{ request('source') === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                    <option value="search_engine" {{ request('source') === 'search_engine' ? 'selected' : '' }}>Search Engine</option>
                                    <option value="friend" {{ request('source') === 'friend' ? 'selected' : '' }}>Friend or Family</option>
                                    <option value="other" {{ request('source') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-6 col-lg-2">
                                <label class="form-label small">Date From</label>
                                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6 col-md-6 col-lg-2">
                                <label class="form-label small">Date To</label>
                                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-12 col-md-12 col-lg-1">
                                <label class="form-label small d-none d-lg-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="ri-search-line d-lg-none"></i>
                                        <span class="d-none d-lg-inline">Filter</span>
                                    </button>
                                    <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="ri-refresh-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('admin.includes.alerts')

                <!-- Leads Table -->
                <div class="card-body">
                    @if($leads->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="table-responsive d-none d-lg-block">
                            <table class="table table-nowrap table-striped-columns mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Lead Info</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Source</th>
                                        {{-- <th scope="col">Status</th> --}}
                                        <th scope="col">Registration</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                        @php
                                            $registeredUser = $lead->registeredUser;
                                            $subscription = $lead->discountSubscription();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary">
                                                            {{ strtoupper(substr($lead->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $lead->name }}</h6>
                                                        <p class="text-muted mb-0 small">ID: #{{ $lead->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1"><i class="ri-mail-line text-muted"></i> {{ $lead->email }}</p>
                                                <p class="text-muted mb-0 small">
                                                    <i class="ri-phone-line text-muted"></i> 
                                                    {{ $lead->phone ?? 'No phone' }}
                                                </p>
                                            </td>
                                            <td>
                                                @if($lead->source)
                                                    <span class="badge bg-info mb-1">
                                                        {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                                                    </span>
                                                    @if($lead->source === 'referral' && $lead->referral_name)
                                                        <p class="text-muted mb-0 small mt-1">
                                                            <i class="ri-user-line"></i> {{ $lead->referral_name }}
                                                        </p>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Not specified</span>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                <select class="form-select form-select-sm status-select" data-lead-id="{{ $lead->id }}" style="width: 130px;">
                                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                    <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                                </select>
                                            </td> --}}
                                            <td>
                                                @if($registeredUser)
                                                    <span class="badge bg-success">
                                                        <i class="ri-check-line"></i> Registered
                                                    </span>
                                                    <p class="text-muted mb-0 small mt-1">{{ $registeredUser->name }}</p>
                                                    <p class="text-muted mb-0 small">ID: {{ $registeredUser->id }}</p>
                                                @else
                                                    <span class="badge bg-secondary">Not Registered</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription)
                                                    <span class="badge bg-warning mb-1">
                                                        <i class="ri-price-tag-3-line"></i> Used
                                                    </span>
                                                    <p class="text-muted mb-0 small">Code: {{ $subscription->discount_code }}</p>
                                                    <p class="text-muted mb-0 small">Amount: ${{ number_format($subscription->discount_amount, 2) }}</p>
                                                @else
                                                    <span class="badge bg-light text-muted">Not Used</span>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="mb-1">{{ $lead->created_at->format('M d, Y') }}</p>
                                                <p class="text-muted mb-0 small">{{ $lead->created_at->format('h:i A') }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#leadModal{{ $lead->id }}">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-lead" data-lead-id="{{ $lead->id }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-lg-none">
                            @foreach($leads as $lead)
                                @php
                                    $registeredUser = $lead->registeredUser;
                                    $subscription = $lead->discountSubscription();
                                @endphp
                                <div class="card mb-3 border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary bg-soft text-primary">
                                                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $lead->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $lead->id }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#leadModal{{ $lead->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-lead" data-lead-id="{{ $lead->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <small class="text-muted d-block"><i class="ri-mail-line"></i> {{ $lead->email }}</small>
                                            <small class="text-muted d-block"><i class="ri-phone-line"></i> {{ $lead->phone ?? 'No phone' }}</small>
                                            @if($lead->source)
                                                <small class="d-block mt-1">
                                                    <span class="badge bg-info">
                                                        {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                                                    </span>
                                                    @if($lead->source === 'referral' && $lead->referral_name)
                                                        <span class="text-muted ms-1">by {{ $lead->referral_name }}</span>
                                                    @endif
                                                </small>
                                            @endif
                                        </div>

                                        {{-- <div class="row g-2 mb-2">
                                            <div class="col-12">
                                                <label class="form-label small mb-1">Status</label>
                                                <select class="form-select form-select-sm status-select" data-lead-id="{{ $lead->id }}">
                                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                    <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                @if($registeredUser)
                                                    <span class="badge bg-success mb-1">
                                                        <i class="ri-check-line"></i> Registered
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary mb-1">Not Registered</span>
                                                @endif
                                                
                                                @if($subscription)
                                                    <span class="badge bg-warning mb-1">
                                                        <i class="ri-price-tag-3-line"></i> Discount Used
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $lead->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center justify-content-sm-end mt-3">
                            {{ $leads->links() }}
                        </div>

                        <!-- Lead Details Modals -->
                        @foreach($leads as $lead)
                            @php
                                $registeredUser = $lead->registeredUser;
                                $subscription = $lead->discountSubscription();
                            @endphp
                            <div class="modal fade" id="leadModal{{ $lead->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary bg-soft">
                                            <h5 class="modal-title">Lead Details - {{ $lead->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Name:</label>
                                                    <p class="mb-0">{{ $lead->name }}</p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Email:</label>
                                                    <p class="mb-0 text-break">{{ $lead->email }}</p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Phone:</label>
                                                    <p class="mb-0">{{ $lead->phone ?? 'Not provided' }}</p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Source:</label>
                                                    <p class="mb-0">
                                                        @if($lead->source)
                                                            <span class="badge bg-info">
                                                                {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                                                            </span>
                                                            @if($lead->source === 'referral' && $lead->referral_name)
                                                                <br><small class="text-muted">Referred by: {{ $lead->referral_name }}</small>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">Not specified</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Status:</label>
                                                    <p class="mb-0">
                                                        <span class="badge bg-{{ $lead->status === 'new' ? 'info' : 'success' }}">
                                                            {{ ucfirst($lead->status) }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Registration Status:</label>
                                                    <p class="mb-0">
                                                        @if($registeredUser)
                                                            <span class="badge bg-success">Registered</span>
                                                            <br><small class="text-muted">User: {{ $registeredUser->name }} (ID: {{ $registeredUser->id }})</small>
                                                        @else
                                                            <span class="badge bg-secondary">Not Registered</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Discount Usage:</label>
                                                    <p class="mb-0">
                                                        @if($subscription)
                                                            <span class="badge bg-warning">Used</span>
                                                            <br><small class="text-muted">Code: {{ $subscription->discount_code }}</small>
                                                            <br><small class="text-muted">Amount: ${{ number_format($subscription->discount_amount ?? 0, 2) }}</small>
                                                            @if(isset($subscription->discount_percentage))
                                                                <br><small class="text-muted">Percentage: {{ $subscription->discount_percentage }}%</small>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-light text-muted">Not Used</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">IP Address:</label>
                                                    <p class="mb-0">{{ $lead->ip_address ?? 'Not available' }}</p>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label fw-semibold text-muted small">Created:</label>
                                                    <p class="mb-0">{{ $lead->created_at->format('M d, Y h:i A') }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold text-muted small">User Agent:</label>
                                                    <p class="small text-muted mb-0" style="word-break: break-all;">{{ $lead->user_agent ?? 'Not available' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        <div class="text-center py-5">
                            <div class="avatar-md mx-auto mb-4">
                                <div class="avatar-title bg-primary bg-soft text-primary rounded-circle fs-24">
                                    <i class="ri-user-search-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-1">No Leads Found</h5>
                            <p class="text-muted">No leads match your current filters.</p>
                            <a href="{{ route('admin.leads.index') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="ri-refresh-line"></i> Reset Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const leadId = this.dataset.leadId;
            const status = this.value;
            const selectElement = this;
            const originalValue = this.getAttribute('data-original-value') || this.value;
            
            fetch(`/admin/leads/${leadId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store new value as original
                    selectElement.setAttribute('data-original-value', status);
                    
                    // Show success message (optional)
                    console.log('Status updated successfully');
                } else {
                    // Revert to original value
                    selectElement.value = originalValue;
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert to original value
                selectElement.value = originalValue;
                alert('An error occurred while updating status');
            });
        });
        
        // Store original value
        select.setAttribute('data-original-value', select.value);
    });

    // Handle delete
    document.querySelectorAll('.delete-lead').forEach(function(button) {
        button.addEventListener('click', function() {
            const leadId = this.dataset.leadId;
            
            if (confirm('Are you sure you want to delete this lead? This action cannot be undone.')) {
                fetch(`/admin/leads/${leadId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to show updated list
                        window.location.reload();
                    } else {
                        alert('Failed to delete lead');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the lead');
                });
            }
        });
    });
});
</script>

<style>
/* Responsive improvements */
@media (max-width: 991.98px) {
    .card-body {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection