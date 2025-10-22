<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Cart;
use App\CentralLogics\Helpers;
use App\CentralLogics\MailchimpHelper;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\TransactionLogic;
use App\CentralLogics\UserAccess;
use App\CentralLogics\getUserSubscription;
use App\Classes\GeniusMailer;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\SubPlan;
use App\Models\Subscriptions;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Session;
use Validator;
use \MailchimpMarketing\ApiClient;

class UserController extends Controller
{
    protected $mailchimpHelper;


    public function __construct(GeneralSetting $settings, MailchimpHelper $mailchimpHelper){
      $this->settings = $settings::first();
      $this->middleware('auth:admin');
      $this->mailchimpHelper = $mailchimpHelper;

    
    }
    
    public function secretlogin($email)
    {   if(Auth::guard('admin')->user()->IsSuper()){
            $user=User::user()->where('email',$email)->first();
            Auth::guard('web')->login($user); 
        }
        
    }

     //*** GET Request
    public function secret($id)
    { 
        if(Auth::guard('admin')->user()->IsSuper()){
            Auth::guard('web')->logout();
            // $data = User::find($id);
            $data=User::user()->where('id',$id)->first();
            Auth::guard('web')->login($data); 
            return redirect()->route('user.dashboard');
        }
    }

    public function usersDataTables($value='')
    {   
        $datas=User::user()->where('id','!=',1)->orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            // ->addIndexColumn()
                            ->addColumn('user_details', function(User $data) {
                                return '<div>
                                    <p class="mb-0">Name : '.$data->name.'</p>
                                    <p class="mb-0"><small>Email : '.$data->email.'</small></p>
                                    <p class="mb-0"><small>Phone : '.$data->phone.'</small></p>
                                </div>'; 
                            })
                            ->addColumn('member_ship', function(User $data) {
                                $activeSubscription = $data->subscriptionsActive();
                                $planName = $activeSubscription && $activeSubscription->subplan ? $activeSubscription->subplan->name :null;
                                $membershipStatus =  $activeSubscription ? 'Active' : ($planName?'Expired':'');

                                return '<div>
                                    <strong>Membership Level:</strong> '.$planName.'<br>
                                    <strong>Membership Status:</strong> '.$membershipStatus.'
                                </div>'; 
                            })
                            
                            ->addColumn('referral_info', function(User $data) {
                                $referredBy = null;
                                $referralCount = 0;
                                $isInfluencer = $data->role_type === 'influencer';
                                
                                // Get who referred this user
                                if ($data->referred_by) {
                                    $referrer = User::find($data->referred_by);
                                    $referredBy = $referrer ? $referrer->name : 'Unknown';
                                }
                                
                                // Count how many users this user has referred
                                $referralCount = User::where('referred_by', $data->id)->count();
                                
                                $html = '<div class="small">';
                                
                                if ($isInfluencer) {
                                    $html .= '<span class="badge bg-purple mb-1"><i class="ri-star-fill"></i> Influencer</span><br>';
                                }
                                
                                if ($referredBy) {
                                    $html .= '<strong>Referred By:</strong> '.$referredBy.'<br>';
                                }
                                
                                if ($referralCount > 0) {
                                    $html .= '<strong>Total Referrals:</strong> <span class="badge bg-success">'.$referralCount.'</span><br>';
                                }
                                
                                $html .= '<strong>Affiliate Code:</strong> <code class="text-primary">'.$data->affiliate_code.'</code>';
                                
                                $html .= '</div>';
                                
                                return $html;
                            })
                            
                            ->editColumn('created_at', function(User $data) {
                                return $data->created_at->format('F d, Y');
                            })
                            ->addColumn('status', function(User $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.user.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.user.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            ->addColumn('action', function(User $data) {
                                $actions = '<div class="action-list d-flex gap-2">
                                            <a href="'.route('admin.users.show', $data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-eye-fill align-middle fs-16 me-2"></i>View</a>';
                                            
                                // Check if the logged-in admin is a super admin
                                if(Auth::guard('admin')->user()->IsSuper()) {
                                    $actions .= '<a href="' . route('admin.user.secret', $data->id) . '" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-eye-fill align-middle fs-16 "></i> Secret Login</a>';
                                }

                                $actions .= '</div>';
                                return $actions;
                            })

                            ->rawColumns(['user_details','created_at','member_ship_level','member_ship','referral_info','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function users($value='')
    {   
        return view('admin.users.users');
    }

    public function subscribedusersDataTables(Request $request)
    {   
        if($request->subscription_type=="all-subscriptions"){
              $datas=Subscriptions::orderBy('id','desc')->get();  
        }else{
             $now = Carbon::now()->subMinutes(2);
             $datas=Subscriptions::where('stripe_id', '!=', '')
                  ->where('ends_at', '>=', $now)
                  ->where('stripe_status','active')
                  ->orderBy('id','desc')
                  ->get();
        }
        
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('user_id', function(Subscriptions $data) {
                                return $data->relateduser?$data->relateduser->name:'Not Defined';
                            })
                            ->editColumn('stripe_status', function(Subscriptions $data) {
                                if($data->stripe_status=="active" || $data->stripe_status=="trialing")
                                {  
                                   if($data->stripe_status=="trialing"){
                                    $msg="<span class='badge badge-soft-secondary badge-border'>".Str::upper($data->stripe_status)."</span><br>";
                                    $msg.="<small>Trial Ends At: ".Carbon::parse($data->trial_ends_at)->format('F d, Y ')."</small>";
                                   }else{
                                     $msg="<span class='badge text-bg-success'>".Str::upper($data->stripe_status)."</span><br>";
                                     $msg.="<small>Ends At: ".Carbon::parse($data->ends_at)->format('F d, Y')."</small>";
                                   }
                                   return $msg;

                                }else{
                                   $msg="<span class='badge text-bg-danger'>".Str::upper($data->stripe_status)."</span><br>";
                                   // $msg.="<small>Ends At: ".Carbon::parse($data->ends_at)->format('F d, Y H:i A')."</small>";
                                   return $msg;
                                }
                                
                            })
                            ->editColumn('created_at', function(Subscriptions $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y');
                            })
                            ->addColumn('action', function(Subscriptions $data) {
                                return '<div class="action-list">
                                 <a data-href="' . route('admin.users.destroy',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 "></i></a>
                                
                                <a href="'.route('admin.users.transactions.index',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light">View Transactions</a> 

                                </div>';
                            }) 
                            ->rawColumns(['stripe_status','created_at','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function subscribedusers(Request $request)
    {   
       
        if( $request->subscription_type){
           $subscription_type = $request->subscription_type;
        }else{
            $subscription_type="active-subscriptions";
        }
        return view('admin.users.subscribed',compact('subscription_type'));
    }

    public function userstransactionsDataTables($id='')
    {   
        if($id){
         $subscription=Subscriptions::find($id);  
         $datas=Transaction::where('subscriptions_id',$subscription->id)->orderBy('id','desc')->get();
        }else{
         $datas=Transaction::orderBy('id','desc')->get();
        }          
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('user_id', function(Transaction $data) {
                                return $data->relateduser?$data->relateduser->name:'Not Defined';
                            })
                            ->editColumn('amount', function(Transaction $data) {

                               if($data->status=="trialing"){
                                 $msg=AppHelper::setCurrency(0);
                                 $msg.="<br><span class='badge badge-soft-secondary badge-border'>".Str::upper($data->status)."</span>";
                               }else{
                                 $msg=AppHelper::setCurrency($data->amount);
                                 $msg.="<br><span class='badge text-bg-success'>".Str::upper($data->status)."</span>";
                               }
                               return $msg;                           
                            })
                            ->editColumn('earning_net_admin', function(Transaction $data) {
                                return AppHelper::setCurrency($data->earning_net_admin);
                            })
                            ->editColumn('referrer_link', function(Transaction $data) {
                                if($data->referrer_link){
                                    $userearn=AppHelper::setCurrency($data->earning_net_user);
                                    $msg="<span>Referrer Link :".$data->referrer_link."</span><br><span>Referrer Earning :".$userearn."</span>";
                                    return $msg;
                                }else{
                                    return "No Referral";
                                }
                                
                            })
                            ->editColumn('created_at', function(Transaction $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y H:i A');
                            })
                            ->rawColumns(['earning_net_admin','referrer_link','amount','created_at'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function userstransactions($id='')
    {
        return view('admin.users.transactions',compact('id'));
    }

     //*** GET Request
    public function show($id)
    {
        $data = User::findOrFail($id);
        // Calculate affiliate earnings from ReferralTransaction table
        $affiliate_earnings = \App\Models\ReferralTransaction::where('referrer_user_id', $data->id)
            ->where('status', 'completed')
            ->sum('credit_amount');
        $subplans=SubPlan::active()->get();
        return view('admin.users.show',compact('data','affiliate_earnings','subplans'));
    }

    public function update(Request $request,$id)
    {
               //--- Validation Section

        $rules = [
            'affiliate_consumer_percentage' => 'required|numeric|between:0,99.99',
            'affiliate_referrer_percentage' => 'required|numeric|between:0,99.99',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        //--- Validation Section Ends

        //--- Logic Section
        $data = User::findOrFail($id);
        $data->affiliate_referrer_percentage=$request->affiliate_referrer_percentage;
        $data->affiliate_consumer_percentage=$request->affiliate_consumer_percentage;
        $input = $request->all();        
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully!",
            'route'=>route('admin.users.show',$data->id),
        ]);    
        //--- Redirect Section Ends  
    }


    public function status($id1,$id2)
    { 
        $user=User::find($id1);
        $user->status=$id2;
        $user->update();
        return  $user->status;

    }
    public function emailCampaign($value='')
    {   
        $subplans=SubPlan::active()->get();
        return view('admin.users.email-campaign',compact(
            'subplans'));
    }

    public function sendCampaignEmail(Request $request)
    {

        // Define an array of members you want to subscribe
        $filteredUsers = $this->mailchimpHelper->getUsersByCategory();
        $members = [];
        foreach ($filteredUsers['allUsers'] as $user) {
            $members[] = [
                'email_address' => $user['email'], // Assuming 'email' is the key for the email address
                'status' => 'subscribed', // You can set the status to 'subscribed' to subscribe the user
                'merge_fields' => [
                    'NAME' => $user['name'], 
                ],
            ];
        }
        // Manually add an email address to the array
        $members[] = [
            'email_address' => 'faye@gmail.com', // Replace with the email address you want to add manually
            'status' => 'subscribed', // You can set the status to 'subscribed' to subscribe the user
            'merge_fields' => [
                'NAME' => 'Faye', // Replace with the name of the new user
            ],
        ];
        $mailchimp = new \MailchimpMarketing\ApiClient();
        $mailchimp->setConfig([
             'apiKey' => config('services.mailchimp.key'),
             'server' => config('services.mailchimp.server_prefix')
        ]);

        $listId = config('services.mailchimp.list_id');

        try {
            

            // Subscribe or add all members in list   
            $subscribeallusers = $mailchimp->lists->batchListMembers($listId,[
            'members'          => $members,
            'update_existing'  => true, // Set to true if updating existing members
            ]);
            // dd($subscribeallusers);

            // Create segments and add members (user emails) based on the user categories
            // foreach ($filteredUsers as $categoryName => $users) {
            //     // Create a new segment with the name of the category
            //     $segmentName = $categoryName;
            //     // Extract user emails and add them to the segment
            //     $userEmails = $users->pluck('email')->toArray();
            //     $segment = $mailchimp->lists->createSegment($listId, [
            //         "name" => $segmentName,
            //         'static_segment' => [] //add all emails
            //     ]);

            // }
            //Get list of all segments
            $allsegments = $mailchimp->lists->listSegments($listId);
            // Create a mapping of segment names to their IDs
            $segmentNameToId = [];
            foreach ($allsegments->segments as $segment) {
                $segmentNameToId[$segment->name] = $segment->id;
            }

            foreach ($filteredUsers as $categoryName => $users) {
                // Create a new segment with the name of the category
                $segmentName = $categoryName;

                // Check if the segment name exists in the mapping
                if (isset($segmentNameToId[$segmentName])) {
                    // Extract user emails and add them to the existing segment
                    $userEmails = $users->pluck('email')->toArray();
                    // Manually add an email address to the array
                    $userEmails[] = 'faye@gmail.com';
                    $totalUsers=count($userEmails);
                    if($totalUsers>500){
                        // Redirect back with success message
                        return redirect()->back()->with('error', 'User exceeds in one segment, Contact Developer.'); 
                    }
                    //dd($totalUsers);
                    try{
                        // Use the batchSegmentMembers method to add users to the existing segment
                        $update = $mailchimp->lists->updateSegment($listId, $segmentNameToId[$segmentName] , [
                            "name" => $segmentName,
                            "static_segment"=>[]
                        ]);

                        $response = $mailchimp->lists->batchSegmentMembers([
                            "members_to_add" => $userEmails,
                        ], $listId, $segmentNameToId[$segmentName]);

                        
                    }catch (\Exception $e) { 
                         // Use Session::flash to store an error message
                        Session::flash('message', $segmentName . " Message: " . $e->getMessage() . " " . $e->getResponse()->getBody()->getContents());
                        Session::flash('alert-class', 'alert-danger');

                        return redirect()->back()->with('error', $segmentName . " Message: " . $e->getMessage() . " " . $e->getResponse()->getBody()->getContents());

                    }
                    
                }
            }
            Session::flash('message', 'Contacts Exported Succesfully !!');
           Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('success', "Contacts Exported Succesfully !"); 

        } catch (MailchimpMarketing\ApiException $e) {
            Session::flash('message', $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', $e->getMessage()); 
        }
         catch (\Exception $e) {
            Session::flash('message', $e->getResponse()->getBody()->getContents());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error',$e->getResponse()->getBody()->getContents()); 
        }
    }

    // public function sendCampaignEmail11676(Request $request)
    // {
    //     // Validate the form data
    //     $request->validate([
    //         'subplan_ids' => 'required|array',
    //         'membership_status' => 'required|array',
    //         'program_pref' => 'required|array',
    //         'registration_time' => 'required|array',
    //         'details' => 'required|string',
    //     ]);

    //     // Retrieve form data
    //     $subplanIds = $request->input('subplan_ids');
    //     $membershipStatus = $request->input('membership_status');
    //     $program_pref = $request->input('program_pref');
    //     $registrationTime = $request->input('registration_time');
    //     $details = $request->input('details');

    //     // Fetch users based on form inputs
    //     $users = User::whereHas('subplans', function ($query) use ($subplanIds) {
    //         $query->whereIn('subplan_id', $subplanIds);
    //     })
    //      // Filter by membership status
    //     if (!empty($membershipStatus)) {
    //         $users->where(function ($query) use ($membershipStatus) {
    //             foreach ($membershipStatus as $status) {
    //                 if ($status === 'active') {
    //                     $query->whereHas('subscriptionsActive');
    //                 } elseif ($status === 'expired') {
    //                     $query->whereDoesntHave('subscriptionsActive');
    //                 }
    //             }
    //         });
    //     }
    //     ->whereIn('internshipgraduate', $membershipStatus)
    //     ->where(function ($query) use ($registrationTime) {
    //         $now = now(); // Current timestamp
    //         $oneWeekAgo = now()->subWeek(); // One week ago from now
    //         $oneMonthAgo = now()->subMonth(); // One month ago from now
    //         $sixMonthsAgo = now()->subMonths(6); // Six months ago from now

    //         // Filter users based on registration time
    //         if (in_array('one_week', $registrationTime)) {
    //             $query->orWhereBetween('created_at', [$oneWeekAgo, $now]);
    //         }
    //         if (in_array('one_month', $registrationTime)) {
    //             $query->orWhereBetween('created_at', [$oneMonthAgo, $now]);
    //         }
    //         if (in_array('six_month', $registrationTime)) {
    //             $query->orWhereBetween('created_at', [$sixMonthsAgo, $now]);
    //         }
    //     })
    //     ->get();

    //     // Send email to fetched users
    //     foreach ($users as $user) {
    //         Mail::to($user->email)->send(new MembershipCampaignEmail($subplanIds, $membershipStatus, $registrationTime, $details));
    //     }

    //     // Redirect back with success message
    //     return redirect()->back()->with('success', 'Campaign email sent successfully to selected users!');
    // }




    public function sendCampaignEmailOld(Request $request)
    {
        // Get the input data from the form
        $subplanIds = $request->input('subplan_ids');
        $membershipStatus = $request->input('membership_status');
        $programPref = $request->input('program_pref');
        $registrationTime = $request->input('registration_time');
        $details = $request->input('details');

        // Query users based on selected criteria
        $users = User::user()->active()->query();

        if($request->user_type==3){
            // Filter by subscription plans
            $users->when(!empty($subplanIds), function ($query) use ($subplanIds) {
                $query->whereHas('userSubscriptions', function ($query) use ($subplanIds) {
                    $query->whereIn('subplan_id', $subplanIds);
                });
            });

            // Filter by membership status
            $users->when(!empty($membershipStatus), function ($query) use ($membershipStatus) {
                if (count($membershipStatus) === 1) {
                    $status = $membershipStatus[0];
                    if ($status === 'active') {
                        $query->whereHas('userSubscriptions', function ($query) {
                            $query->where('ends_at', '>=', Carbon::now()->subMinutes(2));
                        });
                    } elseif ($status === 'expired') {
                        $query->whereHas('userSubscriptions', function ($query) {
                            $query->where('ends_at', '<=', Carbon::now()->subMinutes(2));
                        });
                    }
                }
            });
        }

        if($request->user_type==2){
             $users=$users->whereDoesntHave('userSubscriptions');
        }
        // Filter by program preference
        $users->when(!empty($programPref), function ($query) use ($programPref) {
            $query->whereIn('internshipgraduate', $programPref);
        });

        // Filter by registration time
        $users->when(!empty($registrationTime), function ($query) use ($registrationTime) {
            $now = Carbon::now();
            $query->where(function ($query) use ($now, $registrationTime) {
                foreach ($registrationTime as $time) {
                    switch ($time) {
                        case 'one_week':
                            $query->orWhere('created_at', '>=', $now->subWeek());
                            break;
                        case 'one_month':
                            $query->orWhere('created_at', '>=', $now->subMonth());
                            break;
                        case 'six_month':
                            $query->orWhere('created_at', '>=', $now->subMonths(6));
                            break;
                    }
                }
            });
        });


        $filteredUsers=$users->get();
        if(!$filteredUsers){
            // Redirect back with success message
            return redirect()->back()->with('error', 'No users found in this criteria');
        }
        // Send email to filtered users
        foreach ($filteredUsers as $user) {
            // Send email using $details
            // e.g. Mail::to($user->email)->send(new EmailCampaign($details));

           
               $to = $user->email;
               $subject = $request->subject;
               $msg = $details ;
             
                $data = [
                        'to' => $to,
                        'subject' => $subject,
                        'body' => $msg,
                    ];
                try{
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);
                }catch(\Exception $e){
                    Session::flash('message', $e->getMessage());
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('admin.users.email.campaign');
                }         
                
            
        }
        // Redirect back with success message
        return redirect()->back()->with('success', 'Email campaign sent successfully.');
    }
     public function destroy($id)
    {
        $user=User::findOrfail($id);
         
         $checkSubscription=$user->userSubscriptions()->where('cancelled',0)->orderBy('id','desc')->first(); 
         if($checkSubscription && $checkSubscription->stripe_id){     
           $stripe = new \Stripe\StripeClient($this->settings->stripe_secret);
           try {
             $response = $stripe->subscriptions->cancel($checkSubscription->stripe_id, []);
           } catch (\Exception $e) {             
             // $e->getMessage();
           }           
        }
        if($user->userSubscriptions->count() > 0)
        {
            foreach ($user->userSubscriptions as $userSubscription) {
               $userSubscription->delete();
            }
        }
        $transactions=Transaction::where('user_id',$user->id)->get();
        if($transactions->count() > 0)
        {
            foreach ($transactions as $transaction) {
               $transaction->delete();
            }
        }
        $user->delete();
        
    }

    public function updateMembership(Request $request,$id)
    {
        //clear cart
        Cart::clearCart();
        $user=User::findOrfail($id); 
        $this->validate($request, [
            'item_id' => 'required'
        ]);

        $item_type='subscription_plan';
        $item_id=$request->item_id;

        // validate item_id
        $item=Cart::validateItem($item_type, $item_id);
        if (!$item) {
            return back()->with('error', 'Invalid item!');
        }

        //Validate subscription not to add lower package
        if($item_type==='subscription_plan' && $activeSubscription = $user->subscriptionsActive()){
            $newPlan=SubPlan::where('id',$item_id)->active()->first();
            $existingPlan=SubPlan::where('id',$activeSubscription->subplan_id)->active()->first();
            if($newPlan->price<=$existingPlan->price){
                return back()->with('error', "You can't subscribe to lower Package!");
            }
        }
        //Validate subscription not to add lower package ends

        // add item to cart
        $cartDataa = [
                'item_type'=>$item_type,
                'item_id'=>$item_id,
                'booking_time'=>null,
                'booking_date'=>null,
                'meeting_id'=>null
            ];
        $response=Cart::addCartItem($cartDataa);
        // dd($response);
        if (!$response) {

             $cartItems = Cart::getCartItems();
             if(!$cartItems){
               return redirect()->back()->with('error','Empty Cart');
             }

             
            $cartTotal=[
                'subtotal' => $request->order_amount ,
                'discount' => 0,
                'previous_subscription_discount' => 0, 
                'tax' => 0,
                'total' => $request->order_amount,
            ];

            $createOrder=OrderLogic::createOrder($user->id,$cartItems,$cartTotal,'completed','Manual',$coupon_code=null,$coupon_own_type=null,$couponPercentage=null);

            $order= $createOrder; 
            $createtransaction=TransactionLogic::createTransaction($order->id,$order->user_id,$order->payment_gateway, $order->pay_amount,$order->checkout_fee,'checkout','ManualBankPay7676');       
            //clear cart
            Cart::clearCart();
            return redirect()->route('admin.users.index')->with('success', 'Membership Updated SucccessFully');
        }else{
            return redirect()->back()->with('error', 'Something went Wrong.');
        }
    }

}
