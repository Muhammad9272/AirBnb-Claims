<?php

namespace App\CentralLogics;

use App\CentralLogics\Cart;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderTrackLogic;
use App\CentralLogics\ProductLogic;
use App\CentralLogics\UserAccess;
use App\Events\GenericEvent;
use App\Models\Appointment;
use App\Models\CareerEventRegistration;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\SubPlan;
use App\Models\Subscriptions;
use App\Models\TemporaryOrder;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\GenericNotification;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
class OrderLogic
{

    public static function createOrder($user_id,$cartItems,$cartTotal, $paymentStatus,$payment_gateway,$coupon_code,$coupon_own_type,$couponPercentage) {
        // $cartItems = Cart::getCartItems();
        if(!$cartItems){
           die("Empty Cart");
           \Log::error('Empty Cart: '.$user_id);

        }
        $user=User::find($user_id);

        $totalPrice=$cartTotal['total'];
        $discount=$cartTotal['discount'];
        $subscription_discount=$cartTotal['previous_subscription_discount'] ?? 0;
        $tax=$cartTotal['tax'];
        $subtotal=$cartTotal['subtotal'];

        try {  
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = self::generateOrderNumber();
            
            $order->discount=$discount;
            $order->subscription_discount=$subscription_discount;
            $order->checkout_fee=$tax;
            $order->subtotal=$subtotal;
            $usercoupons=User::where('affiliate_code',$coupon_code)->first();
            if($usercoupons && $user->coupon_avail!=1){
                //$user->coupon_avail=1;//This will be checked in transaction logic
                $user->referred_by=$coupon_code;
                $user->update();
            }
            $order->coupon_code=$coupon_code;
            $order->coupon_own_type=$coupon_own_type; //user or admin

            $order->pay_amount = $totalPrice;

            $order->payment_status = $paymentStatus;
            $order->payment_gateway = $payment_gateway;
            $order->save();

            if($coupon_code){
                $coupon=Coupon::where('coupon_code',$coupon_code)->firstOrFail();
                $coupon->increment('usage_count');
            }

            foreach ($cartItems as $cartItem) {
                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                // $orderItem->user_id = $order->user_id;

                $itemDetails = Cart::getItemDetails($cartItem['item_type'], $cartItem['item_id']);
                $orderItem->item_id = $cartItem['item_id'];
                $orderItem->item_type = $cartItem['item_type'];
                $orderItem->quantity = $cartItem['quantity'];

                $orderItem->price = $itemDetails['price'];
                
                //Add discounted price (to calculte gross sales in admin analytics)
                $discountedPrice = ($cartItem['item_type'] === 'subscription_plan')
                    ? $itemDetails['price'] - $subscription_discount
                    : $itemDetails['price'];

                if ($coupon_code) {
                    
                    $discount = $discountedPrice * $couponPercentage / 100;
                    $discountedPrice=$discountedPrice-$discount;
                }
                $orderItem->discounted_price = $discountedPrice;
                //Add discounted price ends here
                
                $orderItem->save();


                if($cartItem['item_type']=="subscription_plan"){
                    $response=self::addUserSubscription($cartItem['item_id'],$user->id,$order->id);
                   
                }

            }

            return $order;
        } catch (\Exception $e) {
            \Log::error('Unexpected error for user: ' . $user_id . '. Error: ' . $e->getMessage());
            die($e->getMessage());
        }
    }



    public static function createTemporaryOrder($userId, $partnerOrderNo, $cartItems, $cartTotal, $paymentStatus, $paymentMethod,$auth_token , $couponCode, $couponOwnType, $couponPercentage) {

        $temporaryOrder = new TemporaryOrder;
        
        $temporaryOrder->user_id = $userId;
        $temporaryOrder->partner_order_no = $partnerOrderNo;
        $temporaryOrder->cart_items = json_encode($cartItems); // Assuming cart items are an array
        $temporaryOrder->cart_total = json_encode($cartTotal); // Assuming cart total details are an array
        $temporaryOrder->payment_status = $paymentStatus;
        $temporaryOrder->payment_method = $paymentMethod;
        $temporaryOrder->auth_token = $auth_token;

        $temporaryOrder->coupon_code = $couponCode;
        $temporaryOrder->coupon_own_type = $couponOwnType;
        $temporaryOrder->coupon_percentage = $couponPercentage;

        $temporaryOrder->save();

        return $temporaryOrder;
    }

    public static function generateOrderNumber()
    {
        // Code to generate a unique order number
        return substr(str_shuffle(str_repeat("012398237DFTUJSSHDVWXYZ", 5)), 0, 5) . '-' . time();
        // 100000 + Order::all()->count() + 1,
    }
   
    public static function addUserSubscription($item_id,$user_id,$order_id)
    {   

        // Expire existing active subscriptions for the user
        Subscriptions::where('user_id', $user_id)
        ->where('ends_at', '>=', now())
        ->update(['ends_at' => DB::raw('DATE_SUB(ends_at, INTERVAL 5 YEAR)')]);

        $subplan=SubPlan::find($item_id);
        $data=new Subscriptions(); 
        $data->user_id=$user_id;
        $data->subplan_id=$item_id;
        $data->order_id=$order_id;
        $data->ends_at = Carbon::now()->add(UserAccess::getTimeIntervalInDays($subplan->interval), 'days')->toDateTimeString(); // Set the end date based on the interval of the subscription and convert to DateTime format
        $data->save();

        


       return ['status' => 'success', 'message' => "Data Added successfully"];
    }
    public static function registerCareerEvent($event_id,$user_id,$free=null)
    {   
        if($free==1){
            $eventexist=CareerEventRegistration::where('user_id',$user_id)->where('event_id',$event_id)->exists();
            if($eventexist){
                return ['status' => 'error', 'message' => "You are already Registered for this event !"];
            }
        }
       
        $data=new CareerEventRegistration(); 
        $data->registration_id=self::generateOrderNumber();
        $data->user_id=$user_id;
        $data->event_id=$event_id;
        $data->save();
       return ['status' => 'success', 'message' => "Data Added successfully"];
    }


    public static function storeOrUpdateAppointment(array $appointmentData, int $tutor_id,$free=null)
    {
        // $appointmentData = Validator::make($appointmentData, [
        //     'title' => 'required|string|max:255',
        //     'start_date' => 'required',
        //     'end_date' => 'nullable',
        //     'start_time' => 'required',
        //     'end_time' => 'required',
        //     'id' => 'nullable|integer|exists:appointments,id'
        // ])->validate();
        if($free==1){
          $appointmentexist= Appointment::where('student_id',$appointmentData['student_id'])->where('tutor_id',$tutor_id)->exists();
          if($appointmentexist){
                return ['status' => 'error', 'message' => "You have already Booked appointment with this Tutor!"];
            }
        }
        $appointment = $appointmentData['id'] ? Appointment::findOrFail($appointmentData['id']) : new Appointment();
        if(!$appointmentData['id']){
            $appointment->tutor_id = $tutor_id;
            $appointment->student_id = $appointmentData['student_id'];  
            $appointment->order_item_id=isset($appointmentData['order_item_id'])?$appointmentData['order_item_id']:null; 
        } 
          
        $appointment->meeting_id=$appointmentData['meeting_id'];  
        $appointment->title = $appointmentData['title'];
        $appointment->start_date = Carbon::createFromFormat('d M, Y', $appointmentData['start_date'])->format('Y-m-d');
        $appointment->end_date = $appointmentData['end_date']
            ? Carbon::createFromFormat('d M, Y', $appointmentData['end_date'])->format('Y-m-d')
            : $appointment->start_date;
       
        $appointment->start_time = $appointmentData['start_time'];

        $appointment->end_time = $appointmentData['end_time']? $appointmentData['end_time']:Carbon::createFromFormat('H:i:s', $appointmentData['start_time'])->addMinutes(60);
        
        $appointment->details = $appointmentData['details'];
        $appointment->status = $appointmentData['status'];
        $appointment->save();
        $message = $appointmentData['id'] ? 'Appointment updated successfully.' : 'Appointment saved successfully.';
        return ['status' => 'success', 'message' => $message];
        
    }
}

