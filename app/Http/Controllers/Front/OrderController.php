<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $orders = auth()->user()->orders()
                        ->latest()
                        ->paginate(10);
                        
        return view('front.orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();

        return view('front.orders.show', compact('order'));
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();

        return view('front.orders.success', compact('order'));
    }

    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('front.orders.show', $order->order_number)
                        ->with('warning', 'Order has been cancelled.');
    }
}
