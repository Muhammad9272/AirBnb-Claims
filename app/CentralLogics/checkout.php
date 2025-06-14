<?php

namespace App\CentralLogics;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\DB;
use Exception;

class Checkout
{
    /**
     * Validate the coupon code.
     *
     * @param string $couponCode
     * @return array ['valid' => bool, 'discount' => int, 'message' => string]
     */
    public static function validateCoupon(string $couponCode): array
    {
        $coupon = Coupon::where('coupon_code', strtoupper($couponCode))->first();
        if (!$coupon) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Coupon code is invalid.'];
        }

        $now = Carbon::now();
        if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Coupon is not yet active.'];
        }
        if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Coupon has expired.'];
        }
        if ($coupon->max_usage_count != 0 && $coupon->usage_count >= $coupon->max_usage_count) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Coupon usage limit reached.'];
        }

        return ['valid' => true, 'discount' => $coupon->discount, 'message' => 'Coupon is valid.'];
    }

    /**
     * Calculate the discount amount and final price.
     *
     * @param float $price
     * @param int   $discount Percentage discount (e.g. 10 means 10%)
     * @return array ['discount_amount' => float, 'final_price' => float]
     */
    public static function calculateFinalPrice(float $price, int $discount): array
    {
        $discountAmount = ($price * $discount / 100);
        $finalPrice = $price - $discountAmount;
        return [
            'discount_amount' => Helpers::getPrice($discountAmount),
            'final_price'     => Helpers::getPrice($finalPrice),
        ];
    }

    /**
     * Calculate the checkout fee based on the payment method.
     *
     * @param float  $price
     * @param string $method 'stripe' or 'paypal'
     * @return float
     */
    public static function getCheckoutFee(float $price, string $method = 'stripe'): float
    {
        if ($method === 'paypal') {
            $flat_fee = 0.39;
            $percentage_fee = 3.49;
        } else {
            // Default to Stripe fee.
            $flat_fee = 0.30;
            $percentage_fee = 2.9;
        }
        $fee = ($price * ($percentage_fee / 100)) + $flat_fee;
        return Helpers::getPrice($fee);
    }

    /**
     * Process an order from cart data.
     *
     * @param Cart $cart
     * @param array $paymentData
     * @param array $addressData
     * @return array ['success' => bool, 'order' => Order|null, 'message' => string]
     */
    public static function processOrder($cart, $paymentData, $addressData)
    {
        try {
            DB::beginTransaction();

            // 1. Stock Validation
            $stockValidation = self::validateStock($cart);
            if (!$stockValidation['success']) {
                throw new Exception($stockValidation['message']);
            }

            // 2. Address Validation
            $addressValidator = self::validateAddress($addressData);
            if ($addressValidator->fails()) {
                // Get all validation errors and format them for display
                $errorMessages = $addressValidator->errors()->all();
                $formattedErrors = implode(', ', $errorMessages);
                throw new Exception("Please check your address information: " . $formattedErrors);
            }

            // 3. Create Order
            $order = self::createOrder($cart, $paymentData, $addressData);

            // 4. Update Product Stock
            self::updateProductStock($order);

            // 5. Clear Cart
            $cart->delete();

            // 6. Send Confirmation Email
            self::sendOrderConfirmationEmail($order);

            DB::commit();

            return [
                'success' => true,
                'order' => $order,
                'message' => 'Order placed successfully'
            ];

        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'order' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate stock availability for all cart items.
     *
     * @param Cart $cart
     * @return array ['success' => bool, 'message' => string]
     */
    public static function validateStock($cart)
    {
        foreach ($cart->items as $item) {
            $product = Product::find($item->product_id);
            if (!$product || $product->quantity < $item->quantity) {
                return [
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}"
                ];
            }
        }
        return ['success' => true, 'message' => 'All items in stock'];
    }

    /**
     * Create a new order from cart data.
     *
     * @param Cart $cart
     * @param array $paymentData
     * @param array $addressData
     * @return Order
     */
    public static function createOrder($cart, $paymentData, $addressData)
    {
        $order = new Order();
        $order->order_number = self::generateOrderNumber();
        $order->user_id = auth()->id();
        $order->status = 'pending';
        $order->payment_method = $paymentData['payment_method'];
        $order->payment_status = 'pending';
        
        // Set billing details
        $order->billing_name = $addressData['billing_name'];
        $order->billing_email = $addressData['billing_email'];
        $order->billing_phone = $addressData['billing_phone'];
        $order->billing_address = $addressData['billing_address'];
        $order->billing_city = $addressData['billing_city'];
        $order->billing_state = $addressData['billing_state'];
        $order->billing_zipcode = $addressData['billing_zipcode'];
        $order->billing_country = $addressData['billing_country'];
        
        // Set shipping details
        $order->shipping_name = $addressData['shipping_name'];
        $order->shipping_email = $addressData['shipping_email'];
        $order->shipping_phone = $addressData['shipping_phone'];
        $order->shipping_address = $addressData['shipping_address'];
        $order->shipping_city = $addressData['shipping_city'];
        $order->shipping_state = $addressData['shipping_state'];
        $order->shipping_zipcode = $addressData['shipping_zipcode'];
        $order->shipping_country = $addressData['shipping_country'];
        
        // Set amounts
        $order->subtotal = $cart->subtotal;
        $order->tax = $cart->tax;
        $order->shipping = Helpers::getShippingCost();
        $order->discount = $cart->discount;
        $order->total = $cart->total + $order->shipping;
        $order->currency = $cart->currency;
        $order->coupon_code = $cart->coupon_code;
        
        $order->save();

        // Create order items
        foreach ($cart->items as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit_price' => $item->price, // Add the unit_price field
                'options' => $item->options
            ]);
        }

        return $order;
    }

    /**
     * Generate a unique order number.
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid()) . '-' . date('dmy');
    }

    /**
     * Update product stock after order is placed.
     *
     * @param Order $order
     * @return void
     */
    public static function updateProductStock($order)
    {
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->decrement('quantity', $item->quantity);
        }
    }

    /**
     * Validate address data.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validateAddress($data)
    {
        $rules = [
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'billing_phone' => 'required|string',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_zipcode' => 'required|string',
            'billing_country' => 'required|string',
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'shipping_country' => 'required|string',
        ];

        return validator($data, $rules);
    }

    /**
     * Send order confirmation email.
     *
     * @param Order $order
     * @return bool
     */
    public static function sendOrderConfirmationEmail($order)
    {
        try {
            $data = [
                'to' => $order->billing_email,
                'subject' => "Order Confirmation - #" . $order->order_number,
                'view' => 'email.orderemail',
                'data' => [
                    'order' => $order,
                    'items' => $order->orderItems,
                    'billing' => [
                        'name' => $order->billing_name,
                        'email' => $order->billing_email,
                        'phone' => $order->billing_phone,
                        'address' => $order->billing_address,
                        'city' => $order->billing_city,
                        'state' => $order->billing_state,
                        'zipcode' => $order->billing_zipcode,
                        'country' => $order->billing_country,
                    ],
                    'shipping' => [
                        'name' => $order->shipping_name,
                        'address' => $order->shipping_address,
                        'city' => $order->shipping_city,
                        'state' => $order->shipping_state,
                        'zipcode' => $order->shipping_zipcode,
                        'country' => $order->shipping_country,
                    ]
                ]
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);

            return true;
        } catch (\Exception $e) {
            \Log::error('Order confirmation email failed: ' . $e->getMessage());
            return false;
        }
    }
}
