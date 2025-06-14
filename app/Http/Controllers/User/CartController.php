<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Cart;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\UserAccess;
use App\Http\Controllers\Controller;
use App\Models\CareerEventRegistration;
use App\Models\QuizBankManagement;
use App\Models\Schedule;
use App\Models\SubPlan;
use Auth;
use Illuminate\Http\Request;
use Session;
use Validator;
class CartController extends Controller
{   
     public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function addItem(Request $request)
    {  

        
        $this->validate($request, [
            'item_type' => 'required',
            'item_id' => 'required'
        ]);
        $user=Auth::user();
          $item_type=$request->item_type;
          $item_id=$request->item_id;
          $bookingTime = $request->booking_time;
          $bookingDate = $request->booking_date;
          $meetingID=null;
          if($request->schedule_id){
           $schedule = Schedule::findOrFail($request->schedule_id);
           $meetingID = $schedule->meeting_id;
          }
         
        // validate item_type
        $valid_item_types = ['events', 'quizbank', 'interview', 'subscription_plan'];
        if (!in_array($item_type, $valid_item_types)) {
            return back()->with('error', 'Invalid item type!');
        }

        // validate item_id
        $item=Cart::validateItem($item_type, $item_id);
        if (!$item) {
            return back()->with('error', 'Invalid item!');
        }

        //Validate subscription not to add lower package
        if($item_type==='subscription_plan' && $activeSubscription = auth()->user()->subscriptionsActive()){
            $newPlan=SubPlan::where('id',$item_id)->active()->first();
            $existingPlan=SubPlan::where('id',$activeSubscription->subplan_id)->active()->first();
            if($newPlan->price<=$existingPlan->price){
                return back()->with('error', "You can't subscribe to lower Package!");
            }
        }
        //Validate subscription not to add lower package ends
        
        $UserAccess=UserAccess::hasAccess(Auth::user(),$item_type,$item_id);
        if(!isset($UserAccess['single_purchase'])){
            if($item_type=="events" && ($item->price<=0  || $UserAccess['access']) ){  
                $user_id=Auth::id();
                $response=OrderLogic::registerCareerEvent($item_id,$user_id,$free=1); 
                // if ($response instanceof CareerEventRegistration) {
                return back()->with('success', $response['message']);     
             
            }
            if($item_type=="interview" && ($item->price<=0  || $UserAccess['access']) ){
                $data = [
                    'id'=>null,
                    'student_id'=>$user->id,
                    'tutor_id'=>$item_id,
                    'meeting_id'=>$meetingID,
                    'title' => 'Appointment with '.$user->name,
                    'start_date' => $bookingDate,
                    'end_date' => null,
                    'start_time' => $bookingTime,
                    'end_time' => null,
                    'status' => 'pending',
                    'details'=>"You Meeting is Scheduled we will respond you soon. Thanks !!",
                ];
                $response=OrderLogic::storeOrUpdateAppointment($data,$item_id,$free=1);
                return back()->with($response['status'], $response['message']);
            }
        }    
        // add item to cart
        $cartDataa = [
                'item_type'=>$item_type,
                'item_id'=>$item_id,
                'booking_time'=>$bookingTime,
                'booking_date'=>$bookingDate,
                'meeting_id'=>$meetingID
            ];
        $response=Cart::addCartItem($cartDataa);
        if ($response) {
            // Item already in cart
            return redirect()->back()->with('error', $response['message']);
        } else {
            // Item added to cart successfully
            return redirect()->route('user.cart')->with('success', 'Item added to cart successfully');
        }

    }

    public function removeItem(Request $request)
    {
        $rules = [
                'item_type' => 'required',
                'item_id' => 'required'
                ];
        $validator = Validator::make($request->all(), $rules);       
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $item_type = $request->item_type;
        $item_id = $request->item_id;

        // validate item_type
        $valid_item_types = ['events', 'quizbank', 'interview', 'subscription_plan'];
        if (!in_array($item_type, $valid_item_types)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid item type!'
            ]);
        }

        // validate item_id
        $item = Cart::validateItem($item_type, $item_id);
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid item!'
            ]);
        }

        // remove item from cart
        $response = Cart::removeCartItem($item_type, $item_id);
        if ($response) {
            // Item already in cart
            return response()->json([
                'success' => false,
                'message' => $response['message']
            ]);
        } else {
            // Item added to cart successfully
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully!'
            ]);
        }
    }


    public function clearCart(Request $request)
    {
        $response=Cart::clearCart();
        return back()->with('success', 'Cart cleared successfully!');
    }

    public function addCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');
        $payment_method = $request->input('payment_method');
        $result = Cart::addCoupon($couponCode);
        
        $cartItems = Cart::getCartItems();
        $cartTotal = $cartItems ? Cart::calculateCartTotal($cartItems,$payment_method) : null;

        if ($result['status'] == 'success') {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'sidebar_html' => view('user.checkout.partials.sidebar', [
                    'cartItems' => $cartItems,
                    'cartTotal' => $cartTotal,
                ])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'sidebar_html' => view('user.checkout.partials.sidebar', [
                    'cartItems' => $cartItems,
                    'cartTotal' => $cartTotal,
                ])->render(),
            ]);
        }
    }

    public function changePaymentGateway(Request $request)
    {
        $payment_method = $request->input('payment_method');        
        $cartItems = Cart::getCartItems();
        $cartTotal = $cartItems ? Cart::calculateCartTotal($cartItems,$payment_method) : null;

        return response()->json([
            'success' => true,
            'sidebar_html' => view('user.checkout.partials.sidebar', [
                'cartItems' => $cartItems,
                'cartTotal' => $cartTotal,
            ])->render(),
        ]);

    }

    // public function addCoupon(Request $request)
    // {   
    //     $couponCode = $request->input('coupon_code');
    //     $result=Cart::addCoupon($couponCode);
        
    //     $cartItems = Cart::getCartItems();
    //     $cartTotal = $cartItems?Cart::calculateCartTotal($cartItems):null;

    //     return view('user.checkout.partials.sidebar', [
    //         'cartItems' => $cartItems,
    //         'cartTotal' => $cartTotal,
    //     ])->render();

    //     if ($result['status'] == 'success') {
    //         return back()->with('success', $result['message']);
    //     } else {
    //         return back()->with('error', $result['message']);
    //     }
    // }
}
