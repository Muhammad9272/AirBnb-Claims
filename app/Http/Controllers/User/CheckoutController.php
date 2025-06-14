<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Cart;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\TransactionLogic;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\TemporaryOrder;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Session;
use Stripe\Exception\CardException;
class CheckoutController extends Controller
{
   public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }



    public function checkout($value='')
    {   
        

        //dd(Session::get('coupon_code'));
        $cartItems = Cart::getCartItems();
        $cartTotal = $cartItems?Cart::calculateCartTotal($cartItems):null;
        return view('user.checkout.checkout',compact('cartItems','cartTotal'));
    }


    public function checkoutStore(Request $request)
    {    

        //Remove temporary order by changing the status
        TemporaryOrder::where('user_id',auth::user()->id)->where('status','pending')->update(['status'=>'override']);


         $gs = GeneralSetting::findOrFail(1);
         $paymentMethod=$request->payment_method;
         $user=Auth::user();
         $cartItems = Cart::getCartItems();
         if(!$cartItems){
           return redirect()->back()->with('error','Empty Cart');
         }
         $cartTotal = Cart::calculateCartTotal($cartItems);
         $totalprice = Helpers::getPrice($cartTotal['total']);
         $savecard = $request->input('save_card_details');
            //SaveCard 
            if($savecard){
               if (! auth()->user()->hasPaymentMethod()) {
                   auth()->user()->createOrGetStripeCustomer();
               }
               try {
                 auth()->user()->deletePaymentMethods();
               } catch (\Exception $e) {
                 // error
               }
               try{
                  auth()->user()->updateDefaultPaymentMethod($paymentMethod);
                  auth()->user()->save(); 
                } catch (\Exception $exception) {
                    auth()->user()->deletePaymentMethods();
                    auth()->user()->stripe_id=null;
                    auth()->user()->update();
                    return back()->with('error', $exception->getMessage());
                }                       
            }
            //SaveCard ends

            try{


                $coupon_code=Session::get('coupon_code');
                $coupon_own_type=Session::get('coupon_own_type');
                $couponPercentage = Session::get('coupon_percentage');


                $partnerOrderNo='stripe' . time() . mt_rand(1000, 9999);
                $authToken = hash('sha256', Str::random(32));
                
                $tempOrder=OrderLogic::createTemporaryOrder($user->id,$partnerOrderNo,$cartItems,$cartTotal,'pending','Stripe',$authToken,$coupon_code,$coupon_own_type,$couponPercentage);

                \Log::info('Stripe payment initiation.', ['user_id' => $user->id, 'amount' => $totalprice]);

                $metadata = [
                    'product_name' => "Items in cart",
                ];
                $charge = $user->charge($totalprice * 100, $paymentMethod, [
                    'metadata' => $metadata,
                    'currency' => $gs->currency_code, // Specify the currency here
                ]);

                if($charge->status=='succeeded'){
                     $payment_status='completed';
                     \Log::info('Stripe payment successful.', ['user_id' => $user->id, 'transaction_id' => $charge->id]);

                    $txn_id= $charge->id;

                    $cartItems = json_decode($tempOrder->cart_items, true);
                    $cartTotal = json_decode($tempOrder->cart_total, true);
                    $createOrder = OrderLogic::createOrder(
                        $tempOrder->user_id, 
                        $cartItems, 
                        $cartTotal, 
                        $payment_status, 
                        $tempOrder->payment_method, 
                        $tempOrder->coupon_code, 
                        $tempOrder->coupon_own_type, 
                        $tempOrder->coupon_percentage
                    );

                    $order= $createOrder; 
                    $createtransaction=TransactionLogic::createTransaction($order->id,$order->user_id,$order->payment_gateway, $order->pay_amount,$order->checkout_fee,'checkout',$txn_id); 

                    $tempOrder->status='completed';
                    $tempOrder->payment_status='completed';
                    $tempOrder->update(); 

                    $mailer = new GeniusMailer();
                    $mailer->sendOrderConfirmation($order);
                    
                    
                    //clear cart
                    //Cart::clearCart();   

                }else{
                    \Log::error('Stripe payment failed.', ['user_id' => $user->id]);
                    return back()->with('error','Something went wrong');
                    //$payment_status='pending';
                } 
                  
                //clear cart
                Cart::clearCart();
                return redirect()->route('user.orders')->with('success','Order placed SuccessFully');
                    
            } catch (\Exception $exception) {
               \Log::error('Order/Transaction creation failed.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
                return back()->with('error', $exception->getMessage());
            }

    }

}
