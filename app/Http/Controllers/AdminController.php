<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\RankHistory;
use App\Models\Product;

class AdminController extends Controller
{
    //
      public function admin_dashboard(){
        return view('Admin.index');
    }   

    // app/Http/Controllers/AdminController.php

public function genealogy()
{
    // Fetch top-level users (those without referrer)
    $rootUsers = User::whereNull('referral_id')->get();

    return view('Admin.genealogy', compact('rootUsers'));
}

public function userList()
{
    $users = User::withCount('orders')->get(); // Get users with order count
    return view('admin.user_list', compact('users'));
}

public function userOrders($id)
{
    $user = User::findOrFail($id);
    $orders = Order::with('orderItems.product')->where('user_id', $id)->latest()
->get();

    return view('admin.orders', compact('user', 'orders'));
}




public function rankHistory()
{
    $user = User::where('role', 'user')->get(); // Assuming you want the first admin user
    $histories = RankHistory::get();

    return view('Admin.rank_history', compact('user', 'histories'));
}




}
