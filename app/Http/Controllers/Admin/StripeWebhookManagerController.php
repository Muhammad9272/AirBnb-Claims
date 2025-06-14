<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\WebhookEndpoint;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;
use DataTables;

class StripeWebhookManagerController extends Controller
{
    protected $availableEvents = [
        'checkout.session.completed',
        'customer.subscription.created',
        'customer.subscription.updated',
        'customer.subscription.deleted',
        'invoice.payment_succeeded',
        'invoice.payment_failed',
        'customer.created',
        'payment_intent.succeeded',
        'payment_intent.payment_failed'
    ];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of webhooks.
     */
    public function index()
    {
        return view('admin.stripe.webhooks.index');
    }

    /**
     * Show the form for creating a new webhook endpoint.
     */
    public function create()
    {
        return view('admin.stripe.webhooks.create', [
            'availableEvents' => $this->availableEvents
        ]);
    }

    /**
     * Return webhook data for DataTables.
     */
    public function datatables()
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $webhooks = WebhookEndpoint::all(['limit' => 100]);
            
            return DataTables::of($webhooks->data)
                ->addIndexColumn()
                ->addColumn('url', function($webhook) {
                    return $webhook->url;
                })
                ->addColumn('status', function($webhook) {
                    $class = $webhook->status == 'enabled' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="'.$class.'">' . ucfirst($webhook->status) . '</span>';
                })
                ->addColumn('events', function($webhook) {
                    return count($webhook->enabled_events) . ' events';
                })
                ->addColumn('action', function($webhook) {
                    return '<div class="action-list">
                        <a href="' . route('admin.stripe.webhooks.show', $webhook->id) . '" class="btn btn-info btn-sm"><i class="ri-eye-fill"></i> View</a>
                        <a href="' . route('admin.stripe.webhooks.edit', $webhook->id) . '" class="btn btn-primary btn-sm ms-1"><i class="ri-edit-box-line"></i> Edit</a>
                        <button type="button" data-id="' . $webhook->id . '" class="btn btn-danger btn-sm ms-1 delete-webhook"><i class="ri-delete-bin-line"></i> Delete</button>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load webhooks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created webhook endpoint.
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'events' => 'required|array|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $webhookEndpoint = WebhookEndpoint::create([
                'url' => $request->url,
                'enabled_events' => $request->events,
                'description' => $request->description ?: 'Created from admin portal',
                'api_version' => '2022-11-15', // Use current API version
            ]);
            
            toastr()->success('Webhook endpoint created successfully!');
            return redirect()->route('admin.stripe.webhooks.show', $webhookEndpoint->id);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            toastr()->error('Failed to create webhook: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified webhook endpoint.
     */
    public function show($id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $webhook = WebhookEndpoint::retrieve($id);
            
            return view('admin.stripe.webhooks.show', [
                'webhook' => $webhook
            ]);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            toastr()->error('Failed to retrieve webhook: ' . $e->getMessage());
            return redirect()->route('admin.stripe.webhooks.index');
        }
    }

    /**
     * Show the form for editing the specified webhook endpoint.
     */
    public function edit($id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $webhook = WebhookEndpoint::retrieve($id);
            
            return view('admin.stripe.webhooks.edit', [
                'webhook' => $webhook,
                'availableEvents' => $this->availableEvents
            ]);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            toastr()->error('Failed to retrieve webhook: ' . $e->getMessage());
            return redirect()->route('admin.stripe.webhooks.index');
        }
    }

    /**
     * Update the specified webhook endpoint.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'events' => 'required|array|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $webhook = WebhookEndpoint::update($id, [
                'enabled_events' => $request->events,
                'description' => $request->description,
            ]);
            
            toastr()->success('Webhook endpoint updated successfully!');
            return redirect()->route('admin.stripe.webhooks.show', $webhook->id);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            toastr()->error('Failed to update webhook: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Toggle status of the webhook endpoint.
     */
    public function toggleStatus($id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $webhook = WebhookEndpoint::retrieve($id);
            
            // Toggle the status (enabled/disabled)
            $newStatus = $webhook->status === 'enabled' ? 'disabled' : 'enabled';
            
            WebhookEndpoint::update($id, [
                'disabled' => $newStatus === 'disabled',
            ]);
            
            return response()->json(['success' => true, 'status' => $newStatus]);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified webhook endpoint.
     */
    public function destroy($id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            WebhookEndpoint::delete($id);
            
            return response()->json(['success' => true]);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test the specified webhook endpoint.
     */
    public function testWebhook($id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Create a test event to the webhook
            $webhook = WebhookEndpoint::retrieve($id);
            
            // Actually sending test events requires using the Stripe CLI, 
            // but we can at least ping the endpoint to check it's reachable
            $ch = curl_init($webhook->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Endpoint is reachable (HTTP ' . $httpCode . ')'
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Endpoint responded with HTTP ' . $httpCode
                ]);
            }
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
