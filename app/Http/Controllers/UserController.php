<?php

namespace App\Http\Controllers;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
   public function user_dashboard()
{
    $user = Auth::user();

    $selfVolume = \App\Models\OrderItem::whereHas('order', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    })->selectRaw('SUM(volume_point * quantity) as total')->value('total') ?? 0;
    $referralCode = $user->user_id;
    $downlineUserIds = $this->getDownlineUserIds($referralCode);
    $teamVolume = \App\Models\OrderItem::whereHas('order', function ($q) use ($downlineUserIds) {
        $q->whereIn('user_id', $downlineUserIds);
    })->selectRaw('SUM(volume_point * quantity) as total')->value('total') ?? 0;
    $totalVolume = $selfVolume + $teamVolume;

    return view('user.dashboard', compact('selfVolume', 'teamVolume', 'totalVolume'));
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
