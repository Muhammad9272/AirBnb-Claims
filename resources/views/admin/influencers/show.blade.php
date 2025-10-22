@extends('admin.layouts.master')
@section('title', 'Influencer Details')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.influencers.index') }}">Influencers</a>
        @endslot
        @slot('title')
            {{ $influencer->name }}
        @endslot
    @endcomponent

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Influencer Profile -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">{{ $influencer->name }}</h4>
                            <p class="text-muted mb-0">{{ $influencer->email }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-{{ $influencer->status ? 'success' : 'danger' }} fs-12">
                                {{ $influencer->status ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="toggleStatus({{ $influencer->id }}, {{ $influencer->status ? 0 : 1 }})">
                                            <i class="ri-toggle-line"></i> Toggle Status
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="resendCredentials({{ $influencer->id }})">
                                            <i class="ri-mail-send-line"></i> Resend Credentials
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteInfluencer({{ $influencer->id }})">
                                            <i class="ri-delete-bin-line"></i> Delete Account
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <p class="fw-medium">{{ $influencer->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone Number</label>
                                <p class="fw-medium">{{ $influencer->phone ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Affiliate Code</label>
                                <div class="d-flex align-items-center">
                                    <code class="me-2">{{ $influencer->affiliate_code }}</code>
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $influencer->affiliate_code }}')">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Joined Date</label>
                                <p class="fw-medium">{{ $influencer->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Affiliate Link</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="affiliateLink" 
                                           value="{{ url('/?ref=' . $influencer->affiliate_code) }}" 
                                           readonly>
                                    <button class="btn btn-outline-primary" onclick="copyToClipboard('{{ url('/?ref=' . $influencer->affiliate_code) }}')">
                                        <i class="ri-file-copy-line"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commissions History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commission History</h5>
                </div>
                <div class="card-body">
                    @if($commissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Claim</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Info</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissions as $commission)
                                        <tr>
                                            <td>
                                                <small class="text-muted">Created:</small><br>
                                                {{ $commission->created_at->format('M d, Y') }}
                                                @if($commission->paid_date)
                                                    <br><small class="text-success">Paid: {{ $commission->paid_date->format('M d, Y') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $commission->customer->name }}</p>
                                                    <small class="text-muted">{{ $commission->customer->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">#{{ $commission->claim->claim_number }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-medium text-success">${{ number_format($commission->commission_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                @if($commission->status === 'paid')
                                                    <span class="badge bg-success"><i class="ri-checkbox-circle-line"></i> Paid</span>
                                                @elseif($commission->status === 'approved')
                                                    <span class="badge bg-info"><i class="ri-check-line"></i> Approved</span>
                                                @elseif($commission->status === 'rejected')
                                                    <span class="badge bg-danger"><i class="ri-close-circle-line"></i> Rejected</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="ri-time-line"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($commission->payment_method)
                                                    <small class="text-muted">Method:</small> {{ $commission->payment_method }}<br>
                                                    <small class="text-muted">Ref:</small> {{ $commission->payment_reference }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if($commission->status === 'pending')
                                                        <button type="button" class="btn btn-success" onclick="approveCommission({{ $commission->id }})">
                                                            <i class="ri-check-line"></i> Approve
                                                        </button>
                                                        <button type="button" class="btn btn-danger" onclick="rejectCommission({{ $commission->id }})">
                                                            <i class="ri-close-line"></i> Reject
                                                        </button>
                                                    @elseif($commission->status === 'approved')
                                                        <button type="button" class="btn btn-primary" onclick="openPaymentModal({{ $commission->id }})">
                                                            <i class="ri-money-dollar-circle-line"></i> Mark as Paid
                                                        </button>
                                                    @elseif($commission->status === 'paid')
                                                        <button type="button" class="btn btn-info btn-sm" onclick="viewCommissionDetails({{ $commission->id }})">
                                                            <i class="ri-eye-line"></i> View Details
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $commissions->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="ri-money-dollar-circle-line display-4 text-muted"></i>
                            <h5 class="mt-3">No Commissions Yet</h5>
                            <p class="text-muted">This influencer hasn't earned any commissions yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics Cards -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Performance Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h4 class="mb-1 text-primary">{{ $stats['total_referrals'] }}</h4>
                                <p class="mb-0 text-muted small">Total Referrals</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h4 class="mb-1 text-success">${{ number_format($stats['paid_commissions'], 2) }}</h4>
                                <p class="mb-0 text-muted small">Paid</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h4 class="mb-1 text-info">${{ number_format($stats['approved_commissions'], 2) }}</h4>
                                <p class="mb-0 text-muted small">Approved</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h4 class="mb-1 text-warning">${{ number_format($stats['pending_commissions'], 2) }}</h4>
                                <p class="mb-0 text-muted small">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Performance -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">This Month</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">New Referrals</span>
                        <span class="fw-medium">{{ $stats['monthly_referrals'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Earnings</span>
                        <span class="fw-medium text-success">${{ number_format($stats['monthly_earnings'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Conversion Rate</span>
                        <span class="fw-medium">{{ $stats['conversion_rate'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="resendCredentials({{ $influencer->id }})">
                            <i class="ri-mail-send-line"></i> Resend Login Credentials
                        </button>
                        <button class="btn btn-outline-warning" onclick="toggleStatus({{ $influencer->id }}, {{ $influencer->status ? 0 : 1 }})">
                            <i class="ri-toggle-line"></i> 
                            {{ $influencer->status ? 'Deactivate' : 'Activate' }} Account
                        </button>
                        <a href="{{ route('admin.influencers.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
// Define routes
const routes = {
    toggleStatus: "{{ route('admin.influencers.update.status', ':id') }}",
    resendCredentials: "{{ route('admin.influencers.resend.credentials', ':id') }}",
    destroy: "{{ route('admin.influencers.destroy', ':id') }}",
    index: "{{ route('admin.influencers.index') }}"
};

// Show/Hide loader
function showLoader() {
    const loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.style.display = 'flex';
    }
}

function hideLoader() {
    const loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.style.display = 'none';
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Copied to clipboard!');
    }).catch(function() {
        toastr.error('Failed to copy to clipboard');
    });
}

function toggleStatus(id, newStatus) {
    if (confirm('Are you sure you want to toggle this influencer\'s status?')) {
        showLoader();
        
        fetch(routes.toggleStatus.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            hideLoader();
            if (data.success) {
                toastr.success(data.message);
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toastr.error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            hideLoader();
            console.error('Error:', error);
            toastr.error('An error occurred while updating status');
        });
    }
}

function resendCredentials(id) {
    if (confirm('Are you sure you want to resend login credentials to this influencer?')) {
        showLoader();
        
        fetch(routes.resendCredentials.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            hideLoader();
            if (data.success) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            hideLoader();
            console.error('Error:', error);
            toastr.error('An error occurred while resending credentials');
        });
    }
}

function deleteInfluencer(id) {
    if (confirm('Are you sure you want to delete this influencer? This action cannot be undone.')) {
        showLoader();
        
        fetch(routes.destroy.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            hideLoader();
            if (data.success) {
                toastr.success(data.message);
                setTimeout(() => window.location.href = routes.index, 1500);
            } else {
                toastr.error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            hideLoader();
            console.error('Error:', error);
            toastr.error('An error occurred while deleting influencer');
        });
    }
}

// Commission Management Functions
function approveCommission(commissionId) {
    if (confirm('Are you sure you want to approve this commission?')) {
        updateCommissionStatus(commissionId, 'approved');
    }
}

function rejectCommission(commissionId) {
    const notes = prompt('Rejection reason (optional):');
    updateCommissionStatus(commissionId, 'rejected', notes);
}

function updateCommissionStatus(commissionId, status, notes = null) {
    showLoader();
    
    fetch(`/management0712/influencers/commission/${commissionId}/update-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoader();
        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            toastr.error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        hideLoader();
        console.error('Error:', error);
        toastr.error('An error occurred while updating commission status');
    });
}

function openPaymentModal(commissionId) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    document.getElementById('commissionId').value = commissionId;
    document.getElementById('paymentForm').reset();
    modal.show();
}

function submitPayment() {
    const commissionId = document.getElementById('commissionId').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const paymentReference = document.getElementById('paymentReference').value;
    const paymentNotes = document.getElementById('paymentNotes').value;
    
    if (!paymentMethod || !paymentReference) {
        toastr.error('Please fill in all required fields');
        return;
    }
    
    showLoader();
    
    fetch(`/management0712/influencers/commission/${commissionId}/mark-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            payment_method: paymentMethod,
            payment_reference: paymentReference,
            notes: paymentNotes
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoader();
        if (data.success) {
            toastr.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            toastr.error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        hideLoader();
        console.error('Error:', error);
        toastr.error('An error occurred while marking commission as paid');
    });
}

function viewCommissionDetails(commissionId) {
    // Find commission data from table
    toastr.info('Commission details displayed in the table');
}
</script>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Mark Commission as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" id="commissionId">
                    
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" id="paymentMethod" required>
                            <option value="">Select Method</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Stripe">Stripe</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="paymentReference" class="form-label">Payment Reference/Transaction ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="paymentReference" placeholder="e.g., TXN123456789" required>
                        <div class="form-text">Enter transaction ID, check number, or reference</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="paymentNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="paymentNotes" rows="3" placeholder="Additional payment details..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitPayment()">
                    <i class="ri-check-line"></i> Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>
@endsection