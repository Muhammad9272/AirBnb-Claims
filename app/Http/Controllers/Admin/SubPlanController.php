<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\SubFeature;
use App\Models\SubPlan;
use DB;
use DataTables;
use Illuminate\Http\Request;
class SubPlanController extends Controller
{
    public function __construct(){

     $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function datatables()
    {   
        $datas=SubPlan::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()

                             ->editColumn('name', function(SubPlan $data) {
                                return $data->is_featured==1?'<div>'.$data->name. '<span class="badge badge-soft-success ml-10">Featured</span></div>':$data->name.'<p class="text-muted"> Slug: '.$data->slug.'</p>';
                            })
                            ->editColumn('interval', function(SubPlan $data) {
                                return Helpers::setInterval($data->interval);
                            })
                            ->editColumn('price', function(SubPlan $data) {
                               return Helpers::setCurrency($data->price);
                            })
                            ->addColumn('status', function(SubPlan $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.subplan.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.subplan.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(SubPlan $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.subplan.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 
                                </div>';
                            }) 
                            ->rawColumns(['name','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        // Get the current webhook configuration if it exists
        $webhookConfig = [
            'webhook_url' => route('stripe.webhook'),
            'webhook_secret' => config('services.stripe.webhook_secret'),
        ];
        
        try {
            // Try to get existing webhook info
            if (config('services.stripe.secret')) {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $webhooks = \Stripe\WebhookEndpoint::all(['limit' => 5]);
                
                foreach ($webhooks->data as $webhook) {
                    if (str_contains($webhook->description ?? '', 'AirBnB Claims App')) {
                        $webhookConfig['id'] = $webhook->id;
                        $webhookConfig['webhook_url'] = $webhook->url;
                        $webhookConfig['status'] = $webhook->status;
                        $webhookConfig['events'] = $webhook->enabled_events;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore any Stripe API errors
        }
        
        return view('admin.subplans.index', compact('webhookConfig'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $subfeatures=SubFeature::where('status',1)->get();
        return view('admin.subplans.create',compact('subfeatures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'name'=>'required|string|max:255|unique:sub_plans,name',
            'price'=>'required',
            'interval'=>'required',
            // 'details'=>'required',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'features' => 'nullable|string',
            'display_label' => 'nullable|string|max:255',
            'claims_limit' => 'nullable|integer|min:1',
        ]);

        $slug=Helpers::slug($request->name.'-'.$request->interval);
        $subplan=SubPlan::where('slug',$slug)->first();
        if($subplan){
            return back()->with('error','Plan already exists with this name & time interval.');
        }

        $data=new SubPlan();
        $input=$request->all();
        
        $data->slug=$slug;
        $data->name=$request->name;
        $data->price=$request->price;
        $data->details=$request->details;
        $data->commission_percentage = $request->commission_percentage;
        $data->features = $request->features;
        $data->display_label = $request->display_label;
        $data->is_featured = $request->has('is_featured') ? 1 : 0;
        $data->claims_limit = $request->claims_limit ?: null; // Store as null if empty (unlimited)

        // Upload intro video
        if ($request->hasFile('photo')) {
            $data->photo = Helpers::upload('subplan/', config('fileformats.image'), $request->file('photo'));
        }

        $data->fill($input)->save();
      
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.subplan.edit',$data->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $data=SubPlan::find($id);
        $subfeatures=SubFeature::where('status',1)->get();

        return view('admin.subplans.edit',compact('subfeatures','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
           'name' => 'required|string|max:255|unique:sub_plans,name,' . $id,
            'price'=>'required',
            'interval'=>'required',
            // 'details'=>'require',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'features' => 'nullable|string',
            'display_label' => 'nullable|string|max:255',
            'claims_limit' => 'nullable|integer|min:1',
        ]);
        
        $slug = Helpers::slug($request->name.'-'.$request->interval);
        $subplan = SubPlan::where('slug', $slug)
                          ->where('id', '<>', $id)
                          ->first();
        if ($subplan) {
            return back()->with('error', 'Plan already exists with this name & time interval.');
        }

        $data=SubPlan::find($id);
        $input=$request->all();

        $data->slug=$slug;
        $data->name=$request->name;
        $data->price=$request->price;
        $data->details=$request->details;
        $data->commission_percentage = $request->commission_percentage;
        $data->features = $request->features;
        $data->display_label = $request->display_label;
        $data->is_featured = $request->has('is_featured') ? 1 : 0;
        $data->claims_limit = $request->claims_limit ?: null; // Store as null if empty (unlimited)

        if ($request->hasFile('photo')) {
            $data->photo = Helpers::update('subplan/',$data->photo, config('fileformats.image'), $request->file('photo'));
        }

        $data->update($input);
        
        toastr()->success('Data has been Updated successfully!');
        return redirect()->route('admin.subplan.edit',$data->id);
    }

     public function status($id1,$id2)
    {
        $data = SubPlan::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    /**
     * Configure and test Stripe webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function configureWebhook(Request $request)
    {
        $request->validate([
            'webhook_url' => 'required|url'
        ]);

        try {
            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            // Check if a webhook already exists with our signature
            $existingWebhooks = \Stripe\WebhookEndpoint::all(['limit' => 100]);
            $webhookId = null;
            
            foreach ($existingWebhooks->data as $webhook) {
                if (str_contains($webhook->description ?? '', 'AirBnB Claims App')) {
                    $webhookId = $webhook->id;
                    break;
                }
            }
            
            // Define events we want to listen for
            $events = [
                'checkout.session.completed',
                'customer.subscription.created',
                'customer.subscription.updated',
                'customer.subscription.deleted',
                'invoice.payment_succeeded',
                'invoice.payment_failed'
            ];
            
            $webhook = null;
            
            if ($webhookId) {
                // Update existing webhook
                $webhook = \Stripe\WebhookEndpoint::update($webhookId, [
                    'url' => $request->webhook_url,
                    'enabled_events' => $events,
                ]);
                $message = 'Webhook updated successfully!';
            } else {
                // Create new webhook
                $webhook = \Stripe\WebhookEndpoint::create([
                    'url' => $request->webhook_url,
                    'enabled_events' => $events,
                    'description' => 'AirBnB Claims App Webhook',
                ]);
                $message = 'Webhook created successfully!';
            }
            
            // Update the .env file with the webhook secret
            if ($webhook && $webhook->secret) {
                $this->updateEnvVariable('STRIPE_WEBHOOK_SECRET', $webhook->secret);
                
                // Also update the config at runtime
                config(['services.stripe.webhook_secret' => $webhook->secret]);
            }
            
            // Test the webhook - handle local development URLs specially
            $testResult = $this->testWebhookUrl($request->webhook_url);
            
            if ($testResult['is_local']) {
                toastr()->info($message . ' Note: ' . $testResult['message']);
            } else if ($testResult['success']) {
                toastr()->success($message . ' ' . $testResult['message']);
            } else {
                toastr()->warning($message . ' However, ' . $testResult['message']);
            }
            
            return back();
        } catch (\Exception $e) {
            toastr()->error('Error configuring webhook: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Test if a webhook URL is accessible (AJAX endpoint)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testWebhookEndpoint(Request $request)
    {
        $request->validate([
            'webhook_url' => 'required|url'
        ]);
        
        $result = $this->testWebhookUrl($request->webhook_url);
        
        return response()->json($result);
    }

    /**
     * Test if a webhook URL is accessible (internal helper)
     * 
     * @param string $url
     * @return array
     */
    private function testWebhookUrl($url)
    {
        // Check if this is a local URL
        $isLocal = preg_match('/localhost|127.0.0.1|::1|.local|.test/', $url);
        
        if ($isLocal) {
            return [
                'success' => false,
                'is_local' => true,
                'message' => 'Local URL detected. Stripe cannot send webhooks to localhost.'
            ];
        }
        
        try {
            // For non-local URLs, attempt to ping the endpoint
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                return [
                    'success' => true,
                    'is_local' => false,
                    'message' => 'Endpoint is reachable (HTTP ' . $httpCode . ').'
                ];
            } else if ($httpCode > 0) {
                return [
                    'success' => false,
                    'is_local' => false,
                    'message' => 'Endpoint responded with HTTP ' . $httpCode . '. Stripe may not be able to deliver events.'
                ];
            } else {
                return [
                    'success' => false,
                    'is_local' => false,
                    'message' => 'Could not connect to webhook endpoint. Ensure the URL is publicly accessible.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'is_local' => false,
                'message' => 'Error testing webhook: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update an environment variable in the .env file
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');
        
        if (!file_exists($path)) {
            return false;
        }
        
        // Read the .env file
        $content = file_get_contents($path);
        
        // Escape special characters in the value
        $value = str_replace('"', '\"', $value);
        
        // If the key exists, replace its value
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
        } else {
            // Add the key if it doesn't exist
            $content .= "\n{$key}=\"{$value}\"\n";
        }
        
        // Write the updated content back to the file
        file_put_contents($path, $content);
        
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = SubPlan::findOrFail($id);
        $data->delete();
    }
}
