<?php

namespace App\CentralLogics;

use App\Models\Product;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use DB;
use App\CentralLogics\Helpers;
class ProductLogic{

    public static function calculateInitialPrice($product, $quantity) {
        $price = $product->price;
        $initialPrice = $price * $quantity;
        $discount_info = json_decode($product->discount, true);
        if($discount_info) {
            $closest_discount = null;
            foreach($discount_info as $discount) {
                if($quantity >= $discount['quantity']) {
                    $closest_discount = $discount;
                }
            }
            if($closest_discount) {
                $initialPrice = $initialPrice - ($initialPrice * $closest_discount['percentage'] / 100);
            }
        }
        return Helpers::getPrice($initialPrice);
    }

    public static function calculateDiscount($product, $quantity) {
        $price = $product->price;
        $initialPrice = $price * $quantity;
        $discount_info = json_decode($product->discount, true);
        $discount_amount=0;
        if($discount_info) {
            $closest_discount = null;         
            foreach($discount_info as $discount) {
                if($quantity >= $discount['quantity']) {
                    $closest_discount = $discount;
                }
            }
            if($closest_discount) {
                $discount_amount = ($initialPrice * $closest_discount['percentage'] / 100);
            }
        }
        return Helpers::getPrice($discount_amount);
    }
    public static function calculateUserEarnings($product, $quantity) {
        $initialPrice = self::calculateInitialPrice($product, $quantity);
        // Deduct percentage fee
        $percentage_fee = ($initialPrice * $product->game->category->percentage_fee) / 100;
        $initialPrice -= $percentage_fee;
        // Deduct fixed fee
        $initialPrice -= $product->game->category->flat_fee;
        return Helpers::getPrice($initialPrice);
    }

    public static function calculateCheckoutFee($price,$method='') {
        if($method){
            $gateway=PaymentGateway::where('name',$method)->where('enabled',1)->firstOrFail();
        }else{
             $gateway=PaymentGateway::where('default',1)->where('enabled',1)->firstOrFail();
        }
        $flat_fee = $gateway->fee_cents;
        $percentage_fee = $gateway->fee;
        $fee =($price * ($percentage_fee / 100)) + $flat_fee;
        //$total = $price + ($price * ($percentage_fee / 100)) + $flat_fee;
        return Helpers::getPrice($fee);
    }

    public static function cartValidate($gateway_name='')
    {
        if (session()->has('cart')) {
            $productSku = session()->get('cart')['product_sku'];
            $quantity = session()->get('cart')['quantity'];
            $product = Product::where('sku', $productSku)->active()->firstOrFail();
            if ($quantity < $product->min_order_quantity) {
                return redirect()->back()->with('error', 'Invalid quantity. Minimum order quantity is ' . $product->min_order_quantity . '.');
            }
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Requested quantity is not available in stock.');
            }
            if (!$product) {
                return redirect('/')->with('error', 'Product not found!');
            }

            $initialprice = self::calculateInitialPrice($product, $quantity);
            $checkoutfee = self::calculateCheckoutFee($initialprice,$gateway_name);
            $totalprice = Helpers::getPrice($initialprice + $checkoutfee);
            return [
                'initialprice'=> $initialprice,
                'checkoutfee' => $checkoutfee,
                'totalprice' => $totalprice,
                'product' => $product,
                'quantity' => $quantity
            ];
        } else {
            return redirect('/')->with('error', 'Empty Cart');
        }
    }




}