@extends('admin.layouts.master')
@section('title', 'Influencers Management')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.influencers.index') }}">Influencers</a>
        @endslot
        @slot('title')
            Management
        @endslot
    @endcomponent

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Influencers Management</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.influencers.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line"></i> Add New Influencer
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="card card-sm border-0 bg-primary bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary mb-1">{{ $stats['total_influencers'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Total Influencers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card card-sm border-0 bg-success bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success mb-1">{{ $stats['active_influencers'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card card-sm border-0 bg-secondary bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-secondary mb-1">{{ $stats['inactive_influencers'] }}</h5>
                                    <p class="card-text text-muted small mb-0">Inactive</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.influencers.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Name, Email, Affiliate Code..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    <a href="{{ route('admin.influencers.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('admin.includes.alerts')

                <!-- Influencers Table -->
                <div class="card-body">
                    @if($influencers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-nowrap table-striped-columns mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Influencer Info</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Affiliate Code</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($influencers as $influencer)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary">
                                                            {{ strtoupper(substr($influencer->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $influencer->name }}</h6>
                                                        <p class="text-muted mb-0 small">ID: #{{ $influencer->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1">{{ $influencer->email }}</p>
                                                <p class="text-muted mb-0 small">{{ $influencer->phone ?? 'No phone' }}</p>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $influencer->affiliate_code }}</span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" 
                                                           type="checkbox" 
                                                           data-influencer-id="{{ $influencer->id }}"
                                                           {{ $influencer->status ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ $influencer->status ? 'Active' : 'Inactive' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1">{{ $influencer->created_at->format('M d, Y') }}</p>
                                                <p class="text-muted mb-0 small">{{ $influencer->created_at->format('H:i A') }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.influencers.show', $influencer) }}" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    @if(Auth::guard('admin')->user()->IsSuper())
                                                        <a href="{{ route('admin.influencer.secret', $influencer->id) }}" class="btn btn-sm btn-success" title="Secret Login">
                                                            <i class="ri-login-box-line"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-warning resend-credentials" data-influencer-id="{{ $influencer->id }}" title="Resend Credentials">
                                                        <i class="ri-mail-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-influencer" data-influencer-id="{{ $influencer->id }}" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $influencers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="avatar-md mx-auto mb-4">
                                <div class="avatar-title bg-primary bg-soft text-primary rounded-circle fs-24">
                                    <i class="ri-user-star-line"></i>
                                </div>
                            </div>
                            <h5>No Influencers Found</h5>
                            <p class="text-muted">No influencers match your current filters.</p>
                            <a href="{{ route('admin.influencers.create') }}" class="btn btn-primary">
                                <i class="ri-add-line"></i> Add First Influencer
                            </a>
                        </div>
                    @endif
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
    destroy: "{{ route('admin.influencers.destroy', ':id') }}"
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

document.addEventListener('DOMContentLoaded', function() {
    // Handle status toggle
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const influencerId = this.dataset.influencerId;
            const status = this.checked ? 1 : 0;
            const currentToggle = this;
            
            showLoader();
            
            fetch(routes.toggleStatus.replace(':id', influencerId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
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
                    // Update label text
                    const label = currentToggle.nextElementSibling;
                    if (label) {
                        label.textContent = status ? 'Active' : 'Inactive';
                    }
                    
                    // Show success notification if toastr is available
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message || 'Status updated successfully');
                    }
                } else {
                    // Revert toggle if failed
                    currentToggle.checked = !currentToggle.checked;
                    if (typeof toastr !== 'undefined') {
                        toastr.error(data.message || 'Failed to update status');
                    } else {
                        alert('Failed to update status');
                    }
                }
            })
            .catch(error => {
                hideLoader();
                console.error('Error:', error);
                // Revert toggle if failed
                currentToggle.checked = !currentToggle.checked;
                if (typeof toastr !== 'undefined') {
                    toastr.error('An error occurred while updating status');
                } else {
                    alert('An error occurred while updating status');
                }
            });
        });
    });

    // Handle resend credentials
    document.querySelectorAll('.resend-credentials').forEach(function(button) {
        button.addEventListener('click', function() {
            const influencerId = this.dataset.influencerId;
            const originalContent = button.innerHTML;
            
            if (confirm('Are you sure you want to generate new credentials and send them via email?')) {
                button.disabled = true;
                button.innerHTML = '<i class="ri-loader-4-line"></i>';
                showLoader();
                
                fetch(routes.resendCredentials.replace(':id', influencerId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message || 'New credentials sent successfully!');
                        } else {
                            alert('New credentials sent successfully!');
                        }
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(data.message || 'Failed to send credentials');
                        } else {
                            alert('Failed to send credentials: ' + (data.message || 'Unknown error'));
                        }
                    }
                })
                .catch(error => {
                    hideLoader();
                    console.error('Error:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('An error occurred while sending credentials');
                    } else {
                        alert('An error occurred');
                    }
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalContent;
                });
            }
        });
    });

    // Handle delete
    document.querySelectorAll('.delete-influencer').forEach(function(button) {
        button.addEventListener('click', function() {
            const influencerId = this.dataset.influencerId;
            
            if (confirm('Are you sure you want to delete this influencer? This action cannot be undone.')) {
                showLoader();
                
                fetch(routes.destroy.replace(':id', influencerId), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message || 'Influencer deleted successfully');
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(data.message || 'Failed to delete influencer');
                        } else {
                            alert('Failed to delete influencer');
                        }
                    }
                })
                .catch(error => {
                    hideLoader();
                    console.error('Error:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('An error occurred while deleting influencer');
                    } else {
                        alert('An error occurred');
                    }
                });
            }
        });
    });
});
</script>
@endsection