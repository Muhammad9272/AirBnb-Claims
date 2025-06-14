<?php

namespace App\CentralLogics;

use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class TransactionLogic
{
    public static function createTransaction($order_id,$user_id,$payment_gateway, $totalprice,$checkoutfee,$type,$txn_id='')
    {
        $earning_net_user=0;
        $earning_net_admin=$totalprice;
        $referred_by='';

        $gs=GeneralSetting::find(1);
        $user=User::find($user_id);
        
        $order=Order::find($order_id); 
        $usercoupons=User::where('affiliate_code',$order->coupon_code)->first();
        if($usercoupons && $gs->is_affilate==1 && $user->referred_by && $user->coupon_avail!=1){
            $user->coupon_avail=1;//This will be checked in transaction logic
            $user->update();

            $earning_net_user = ($totalprice - $checkoutfee)* ($usercoupons->affiliate_referrer_percentage / 100);
            $earning_net_admin=$totalprice-$earning_net_user;                                  
            $referred_by=$user->referred_by;
        }


        $transaction = new Transaction();
        $transaction->order_id = $order_id;
        $transaction->user_id =$user_id;
        $transaction->payment_gateway = $payment_gateway;
        $transaction->amount = $totalprice;
        $transaction->taxes = $checkoutfee;
        $transaction->txn_id = $txn_id;
        $transaction->type=$type; 
        $transaction->status = 'active';

        $transaction->earning_net_user  = $earning_net_user;
        $transaction->earning_net_admin = $earning_net_admin;
        $transaction->referrer_link=$referred_by;
        $transaction->save();
    }

    public static function addWithdrawData($user_id, $method, $amount,$type,$fee,$status, $transfer='')
    {
        $newwithdraw = new Withdraw();
        $newwithdraw['user_id'] = $user_id;
        $newwithdraw['method'] = $method;
        
        if($transfer){
            $newwithdraw['transfer_id'] = $transfer->id;
            $newwithdraw['balance_transaction'] = $transfer->balance_transaction;
            $newwithdraw['destination'] = $transfer->destination;
            $newwithdraw['destination_payment'] = $transfer->destination_payment;
            $newwithdraw['live_mode'] = $transfer->live_mode?1:0;       
        }

        $newwithdraw['amount'] =$amount  ;
        $newwithdraw['fee'] = $fee;
        $newwithdraw['type'] = $type;
        $newwithdraw['status'] =$status;
        $newwithdraw->save();
    }

}
