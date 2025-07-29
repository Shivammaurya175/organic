<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Level;
use App\Models\User;
use App\Models\RankHistory;
use App\Services\RankService;
use App\Models\IncomeHistory;


class CheckoutController extends Controller
{
    

   public function index()
{
    if (Auth::check()) {
        $items = CartItem::with('product')->where('user_id', Auth::id())->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'product' => $item->product,
                'quantity' => $item->quantity,
            ];
        });
    } else {
        $sessionCart = session('cart', []);
        $items = collect($sessionCart)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            return [
                'id' => null,
                'product' => $product,
                'quantity' => $item['quantity'],
            ];
        });
    }

    return view('checkout', compact('items'));
}



public function placeOrder(Request $request)
{
    $user = Auth::user();
    $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('checkout')->with('error', 'Your cart is empty.');
    }

    DB::beginTransaction();
    try {
        // 1. Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
            'status' => 'pending',
            'order_date' => now(),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item->product_id,
                'volume_point'=> $item->product->volume_point ?? 0,
                'quantity'    => $item->quantity,
                'price'       => $item->product->price,
            ]);
        }

        // 2. Update current user's rank
        $rankService = app(RankService::class);
        $level = $rankService->updateRank($user);

        // 3. Update all uplines' ranks
        $rankService->updateUplineRanks($user);

        // 4. Distribute level income
        $totalVP = $cartItems->sum(fn($item) => ($item->product->volume_point ?? 0) * $item->quantity);
        $currentUser = $user;
        $currentProfitPercent = $level?->rank_profit ?? 0;

        while ($currentUser->referral_id) {
            $upline = \App\Models\User::where('user_id', $currentUser->referral_id)->first();
            if (!$upline) break;

            $uplineLevel = \App\Models\Level::find($upline->rank_no);
            if ($uplineLevel) {
                $diff = $uplineLevel->rank_profit - $currentProfitPercent;
                if ($diff > 0) {
                    $incomeAmount = ($totalVP * $diff) / 100;

                    IncomeHistory::create([
                        'user_id'     => $upline->id,
                        'from_user_id'=> $user->id,
                        'type'        => 'level_income',
                        'amount'      => $incomeAmount,
                        'note'        => "Level income from User ID {$user->id} on VP {$totalVP} with diff {$diff}%",
                        'earned_at'   => now(),
                    ]);

                    $currentProfitPercent = $uplineLevel->rank_profit;
                }
            }

            $currentUser = $upline;
        }

        // 5. Clear cart
        CartItem::where('user_id', $user->id)->delete();

        DB::commit();
        return redirect()->route('web.index')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}



function getDownlineUserIds($referralCode)
{
    $downlines = collect();
    $queue = [$referralCode];

    while (!empty($queue)) {
        $current = array_shift($queue);
        $children = \App\Models\User::where('referral_id', $current)->get();

        foreach ($children as $child) {
            $downlines->push($child->id);
            $queue[] = $child->user_id;
        }
    }

    return $downlines;
}

}

