@extends('admin.layouts.master')
@section('title') Webhook Details @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.stripe.webhooks.index') }}">Stripe Webhooks</a> @endslot
@slot('title') Webhook Details @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Webhook Details</h5>
                <div>
                    <a href="{{ route('admin.stripe.webhooks.edit', $webhook->id) }}" class="btn btn-primary">
                        <i class="ri-edit-box-line align-middle me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-success test-webhook" data-id="{{ $webhook->id }}">
                        <i class="ri-sound-module-line align-middle me-1"></i> Test Webhook
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $webhook->id }}</td>
                            </tr>
                            <tr>
                                <th>URL</th>
                                <td>{{ $webhook->url }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $webhook->status === 'enabled' ? 'success' : 'danger' }}">
                                        {{ ucfirst($webhook->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $webhook->description ?: 'No description' }}</td>
                            </tr>
                            <tr>
                                <th>Secret</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <code class="me-2">{{ $webhook->secret }}</code>
                                        <button type="button" class="btn btn-sm btn-outline-primary copy-secret" 
                                                data-secret="{{ $webhook->secret }}">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        Use this signing secret to verify webhook signatures in your application.
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>{{ date('F j, Y, g:i a', $webhook->created) }}</td>
                            </tr>
                            <tr>
                                <th>API Version</th>
                                <td>{{ $webhook->api_version }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h5>Subscribed Events</h5>
                    <div class="row">
                        @foreach($webhook->enabled_events as $event)
                            <div class="col-md-4 mb-2">
                                <span class="badge bg-info">{{ $event }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Result Modal -->
<div class="modal fade" id="test-webhook-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="test-result"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
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
        
        // Test webhook endpoint
        $('.test-webhook').on('click', function() {
            const webhookId = $(this).data('id');
            
            $.ajax({
                url: `/management0712/stripe/webhooks/${webhookId}/test`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#test-result').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Testing webhook endpoint...</p></div>');
                    $('#test-webhook-modal').modal('show');
                },
                success: function(response) {
                    if (response.success) {
                        $('#test-result').html(`
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="ri-check-double-line text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-success mb-2">Endpoint is reachable!</h5>
                                <p>${response.message}</p>
                            </div>
                        `);
                    } else {
                        $('#test-result').html(`
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-danger mb-2">Endpoint Test Failed</h5>
                                <p>${response.message}</p>
                                <div class="alert alert-warning mt-3">
                                    <p class="mb-0">Make sure your endpoint is publicly accessible and properly configured.</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#test-result').html(`
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-danger mb-2">Test Failed</h5>
                            <p>An error occurred while testing the webhook endpoint.</p>
                            <div class="alert alert-danger mt-3">
                                <p class="mb-0">${xhr.responseJSON ? xhr.responseJSON.error : 'Unknown error'}</p>
                            </div>
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endsection
