<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // fetch via the user relation, with pagination
        $orders = auth()->user()
                       ->orders()
                       ->latest()
                       ->paginate(10);
                      
        return view('user.orders.index', compact('orders'));
    }
    
    public function show($orderNumber)
    {
        // fetch a single order via the relation
        $order = auth()->user()
                      ->orders()
                      ->where('order_number', $orderNumber)
                      ->firstOrFail();
                     
        return view('user.orders.show', compact('order'));
    }
}
