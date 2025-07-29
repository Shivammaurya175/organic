<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
class OrderController extends Controller
{
    


public function userOrders()
{
    $orders = Order::with('orderItems.product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

    return view('User.order_list', compact('orders'));
}
}