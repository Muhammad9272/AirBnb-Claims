@extends('admin.layouts.master')
@section('title') Create Stripe Webhook @endsection
@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.stripe.webhooks.index') }}">Stripe Webhooks</a> @endslot
@slot('title') Create New Webhook @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Create New Webhook Endpoint</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Create a new webhook endpoint to receive event notifications from Stripe.
                    Make sure your endpoint is publicly accessible and can properly handle Stripe's signature verification.
                </p>
                
                <form action="{{ route('admin.stripe.webhooks.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="url" class="form-label">Webhook URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" 
                                   value="{{ old('url', url('/stripe/webhook')) }}" required placeholder="https://example.com/stripe/webhook">
                            <div class="invalid-feedback">
                                Please enter a valid URL.
                            </div>
                            @error('url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                This must be a public URL that Stripe can send events to.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="events" class="form-label">Events to subscribe to <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('events') is-invalid @enderror" 
                                    id="events" name="events[]" multiple="multiple" required>
                                @foreach($availableEvents as $event)
                                    <option value="{{ $event }}" {{ in_array($event, old('events', [])) ? 'selected' : '' }}>
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
                                      rows="3" placeholder="Optional description for this webhook endpoint">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                <h5><i class="ri-information-line me-1"></i> Important!</h5>
                                <p class="mb-0">
                                    After creating your webhook endpoint, you'll need to configure your application to verify 
                                    webhook signatures using the webhook signing secret, which will be available after creation.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.stripe.webhooks.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Webhook</button>
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
    });
</script>
@endsection
