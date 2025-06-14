<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\ProductLogic;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\TransactionLogic;
use App\CentralLogics\Helpers;
use App\Models\Transaction;
use Auth;
class WalletController extends Controller
{

    public  function processPayment(Request $request) {

        $user=Auth::user();
        $cartValidate = ProductLogic::cartValidate($request->payment_gateway_name);
        if (is_array($cartValidate)) {
            $initialprice = $cartValidate['initialprice'];
            $checkoutfee = $cartValidate['checkoutfee'];
            $totalprice = $cartValidate['totalprice'];
            $product = $cartValidate['product'];
            $quantity = $cartValidate['quantity'];

            // Perform Wallet Payment Here
            if ($user->wallet >= $totalprice) {
                    $priceLeft=Helpers::getPrice($user->wallet - $totalprice);
                    $user->wallet = $priceLeft;
                    $user->save();
            } else {
                return redirect()->back()->with('error','Insufficient funds in your wallet !');
            }
            $payment_status='completed';
            $createOrder=OrderLogic::createOrder($request, $totalprice, $checkoutfee, $product, $quantity,$payment_status);
            $order= $createOrder;

            $createtransaction=TransactionLogic::createTransaction($order->id,$order->user_id,$order->payment_gateway, $order->pay_amount,$order->checkout_fee,'checkout');

            $fee=0;
            $createTransactionofWithraw=TransactionLogic::addWithdrawData(Auth::user()->id, 'Wallet',$totalprice,'checkout',$fee,'completed');
            
            return redirect()->route('user.order.purchased.show',$order->order_number)->with('success','Order placed SuccessFully');

                    
        }else{
            return $cartValidate;
        }
    } 

}
