@extends('admin.layouts.master')
@section('title') Subscription Plans @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')

@slot('li_1') <a href="{{ route('admin.subplan.index') }}"> Subsription</a> @endslot
@slot('title') Plans @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header end-t-end">
                <h5 class="card-title mb-0">Subsription Plans </h5>
                <a href="{{ route('admin.subplan.create') }}" class="btn btn-primary waves-effect waves-light">Add </a>
            </div>
            <div class="card-body">
                <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th data-ordering="false">ID</th>                           
                            <th data-ordering="false">Name</th>
                            <th>Price</th>                           
                            <th>Interval</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->

<!-- Stripe Webhook Configuration Card -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Stripe Webhook Configuration</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="ri-information-line me-2"></i>What are webhooks?</h5>
                            <p>Webhooks allow Stripe to send real-time event notifications to your application when events occur, such as successful payments or subscription changes.</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.stripe.webhook.configure') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="webhook_url" class="form-label">Webhook URL <span class="text-danger">*</span></label>
                            <input type="url" id="webhook_url" name="webhook_url" class="form-control" 
                                   value="{{ $webhookConfig['webhook_url'] ?? route('stripe.webhook') }}" required>
                            <small class="form-text text-muted">
                                This is the URL that will receive webhook events from Stripe.
                                @if(strpos(($webhookConfig['webhook_url'] ?? ''), 'localhost') !== false || 
                                    strpos(($webhookConfig['webhook_url'] ?? ''), '127.0.0.1') !== false)
                                <div class="mt-1 text-warning">
                                    <i class="ri-alert-line"></i> Local URL detected. Stripe cannot send webhooks to localhost. 
                                    Consider using a service like <a href="https://ngrok.com/" target="_blank">ngrok</a> for testing.
                                </div>
                                @endif
                            </small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Current Webhook Secret</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $webhookConfig['webhook_secret'] ?? 'Not configured' }}" readonly>
                                @if(isset($webhookConfig['webhook_secret']) && !empty($webhookConfig['webhook_secret']))
                                <button class="btn btn-outline-secondary copy-webhook-secret" type="button" 
                                        data-secret="{{ $webhookConfig['webhook_secret'] }}">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                                @endif
                            </div>
                            <small class="form-text text-muted">
                                This secret is used to verify webhook signatures.
                            </small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($webhookConfig['id']))
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-{{ $webhookConfig['status'] == 'enabled' ? 'success' : 'danger' }} me-2">
                                        {{ ucfirst($webhookConfig['status'] ?? 'Not set') }}
                                    </span>
                                    <span class="text-muted">
                                        Subscribed to {{ count($webhookConfig['events'] ?? []) }} events
                                    </span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-info me-2 test-webhook-btn">
                                        <i class="ri-sound-module-line me-1"></i> Test Webhook
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-refresh-line me-1"></i> Update Webhook
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-add-line me-1"></i> Create Webhook
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div class="modal fade" id="confirm-delete" aria-hidden="true" aria-labelledby="..." tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-3">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                    colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                </lord-icon>
                <div class="mt-4 pt-4">
                    <h4>Uh oh, You are about to delete this Data!</h4>
                    <p class="text-muted"> Do you want to proceed?</p>
                    <!-- Toogle to second dialog -->
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                             <a href="" class="btn btn-danger btn-ok">
                                Delete
                            </a>                           
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Webhook Modal -->
<div class="modal fade" id="test-webhook-modal" tabindex="-1" aria-labelledby="test-webhook-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="test-webhook-modal-label">Webhook Test Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="webhook-test-result">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Testing webhook endpoint...</p>
                    </div>
                </div>
                
                <div id="webhook-troubleshoot-guide" class="mt-4" style="display: none;">
                    <h5>Troubleshooting Steps</h5>
                    <div class="accordion" id="webhookTroubleshootingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Using ngrok for local development
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#webhookTroubleshootingAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li><a href="https://ngrok.com/download" target="_blank">Download and install ngrok</a></li>
                                        <li>Start your Laravel application (e.g., <code>php artisan serve</code>)</li>
                                        <li>Open a terminal/command prompt and run: <code>ngrok http 8000</code> (if your app runs on port 8000)</li>
                                        <li>Copy the https URL provided by ngrok (e.g., <code>https://abc123.ngrok.io</code>)</li>
                                        <li>Use this URL plus your webhook path as the webhook URL: <code>https://abc123.ngrok.io/stripe/webhook</code></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Server Configuration Issues
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#webhookTroubleshootingAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li>Ensure your server accepts POST requests to the webhook URL</li>
                                        <li>Check your firewall settings to allow incoming connections</li>
                                        <li>Verify that your web server (Apache/Nginx) is properly configured to pass requests to your application</li>
                                        <li>Ensure the webhook route is properly defined in your routes file</li>
                                        <li>Check the web server error logs for more details about connection failures</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Laravel CSRF Protection
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#webhookTroubleshootingAccordion">
                                <div class="accordion-body">
                                    <p>Laravel's CSRF protection might block Stripe webhook requests. Ensure your webhook route is excluded from CSRF verification:</p>
                                    <pre><code>// In app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'stripe/webhook',
    // other webhook URLs...
];</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="toggle-troubleshooting" class="btn btn-info">Show Troubleshooting Guide</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.subplan.datatables') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name'},
            { data: 'price', name: 'price'},
            { data: 'interval', name: 'interval'},
            { data: 'status', name: 'status'},
            { data: 'action', searchable: false, orderable: false }
        ],
        language: { }
    });
    
    // Copy webhook secret to clipboard
    $(document).on('click', '.copy-webhook-secret', function() {
        const secret = $(this).data('secret');
        if (secret) {
            navigator.clipboard.writeText(secret).then(() => {
                toastr.success('Webhook secret copied to clipboard!');
            }).catch(err => {
                console.error('Could not copy text: ', err);
                toastr.error('Failed to copy. Please select and copy manually.');
            });
        }
    });
    
    // Test webhook endpoint
    $(document).on('click', '.test-webhook-btn', function() {
        const webhookUrl = $('#webhook_url').val();
        
        // Show the modal
        $('#test-webhook-modal').modal('show');
        
        // Make AJAX call to test the webhook
        $.ajax({
            url: "{{ route('admin.stripe.webhook.test') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                webhook_url: webhookUrl
            },
            success: function(response) {
                if (response.success) {
                    $('#webhook-test-result').html(`
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="ri-check-double-line text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-success mb-2">Endpoint is reachable!</h5>
                            <p>${response.message}</p>
                        </div>
                    `);
                } else {
                    $('#webhook-test-result').html(`
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-danger mb-2">Endpoint Test Failed</h5>
                            <p>${response.message}</p>
                            ${response.is_local ? `
                            <div class="alert alert-warning mt-3">
                                <p class="mb-0">Local URLs cannot receive webhooks from Stripe. Use a service like <a href="https://ngrok.com" target="_blank">ngrok</a> to expose your local server.</p>
                            </div>
                            ` : ''}
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#webhook-test-result').html(`
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-danger mb-2">Test Failed</h5>
                        <p>An error occurred while testing the webhook endpoint.</p>
                        <div class="alert alert-danger mt-3">
                            <p class="mb-0">${xhr.responseJSON?.error || 'Unknown error occurred.'}</p>
                        </div>
                    </div>
                `);
            }
        });
    });

    // Toggle troubleshooting guide
    $(document).on('click', '#toggle-troubleshooting', function() {
        const guide = $('#webhook-troubleshoot-guide');
        if (guide.is(':visible')) {
            guide.hide();
            $(this).text('Show Troubleshooting Guide');
        } else {
            guide.show();
            $(this).text('Hide Troubleshooting Guide');
        }
    });
</script>
@endsection
