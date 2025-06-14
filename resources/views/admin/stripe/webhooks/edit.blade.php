@extends('admin.layouts.master')
@section('title') Edit Stripe Webhook @endsection
@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.stripe.webhooks.index') }}">Stripe Webhooks</a> @endslot
@slot('li_3') <a href="{{ route('admin.stripe.webhooks.show', $webhook->id) }}">Webhook Details</a> @endslot
@slot('title') Edit Webhook @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Webhook Endpoint</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stripe.webhooks.update', $webhook->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label class="form-label">Webhook URL</label>
                            <input type="text" class="form-control" value="{{ $webhook->url }}" disabled readonly>
                            <div class="form-text">
                                The webhook URL cannot be changed. Create a new webhook if you need a different URL.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="events" class="form-label">Events to subscribe to <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('events') is-invalid @enderror" 
                                    id="events" name="events[]" multiple="multiple" required>
                                @foreach($availableEvents as $event)
                                    <option value="{{ $event }}" {{ in_array($event, $webhook->enabled_events) ? 'selected' : '' }}>
                                        {{ $event }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select at least one event.
                            </div>
                            @error('events')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                                      rows="3" placeholder="Optional description for this webhook endpoint">{{ old('description', $webhook->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch form-switch-lg">
                                <input type="checkbox" class="form-check-input" id="toggle-status" 
                                      {{ $webhook->status === 'enabled' ? 'checked' : '' }}>
                                <label class="form-check-label" for="toggle-status">
                                    <span id="status-text">{{ ucfirst($webhook->status) }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-information-line fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mt-0 mb-1">Webhook Secret</h5>
                                        <p class="mb-2">
                                            Make sure your application is configured to use the following secret to verify webhook signatures:
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <code class="me-2">{{ $webhook->secret }}</code>
                                            <button type="button" class="btn btn-sm btn-soft-primary copy-secret" 
                                                    data-secret="{{ $webhook->secret }}">
                                                <i class="ri-file-copy-line"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.stripe.webhooks.show', $webhook->id) }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Webhook</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select events to subscribe to",
            allowClear: true
        });
        
        // Copy webhook secret to clipboard
        $('.copy-secret').on('click', function() {
            const secret = $(this).data('secret');
            navigator.clipboard.writeText(secret).then(() => {
                toastr.success('Secret copied to clipboard!');
            }).catch(err => {
                console.error('Could not copy text: ', err);
                toastr.error('Failed to copy secret. Please manually select and copy it.');
            });
        });
        
        // Toggle webhook status
        $('#toggle-status').on('change', function() {
            const isEnabled = $(this).is(':checked');
            const webhookId = '{{ $webhook->id }}';
            
            $.ajax({
                url: `/management0712/stripe/webhooks/${webhookId}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#status-text').text(response.status === 'enabled' ? 'Enabled' : 'Disabled');
                        toastr.success(`Webhook ${response.status === 'enabled' ? 'enabled' : 'disabled'} successfully!`);
                    } else {
                        // Reset switch to previous state
                        $('#toggle-status').prop('checked', !isEnabled);
                        toastr.error('Failed to update webhook status.');
                    }
                },
                error: function() {
                    // Reset switch to previous state
                    $('#toggle-status').prop('checked', !isEnabled);
                    toastr.error('An error occurred while updating webhook status.');
                }
            });
        });
    });
</script>
@endsection
