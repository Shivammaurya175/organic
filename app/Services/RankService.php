<?php
namespace App\Services;

use App\Models\User;
use App\Models\Level;
use App\Models\OrderItem;
use App\Models\RankHistory;

class RankService
{
    public function updateRank(User $user): ?Level
    {
        $selfVolume = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id))
            ->selectRaw('SUM(volume_point * quantity) as total')
            ->value('total') ?? 0;

        $referralCode = $user->user_id;
        $downlineUserIds = $this->getDownlineUserIds($referralCode);

        $teamVolume = OrderItem::whereHas('order', fn($q) => $q->whereIn('user_id', $downlineUserIds))
            ->selectRaw('SUM(volume_point * quantity) as total')
            ->value('total') ?? 0;

        $selfTeamVolume = $selfVolume + $teamVolume;

        $level = Level::where('self_volume', '<=', $selfVolume)
            ->where('team_volume', '<=', $teamVolume)
            ->where('self_team_volume', '<=', $selfTeamVolume)
            ->orderByDesc('rank_profit')
            ->first();

        if ($level && $user->rank_no != $level->id) {
            RankHistory::create([
                'user_id'     => $user->id,
                'old_rank'    => $user->rank_no ?? 0,
                'new_rank'    => $level->id,
                'self_volume' => $selfVolume,
                'team_volume' => $teamVolume,
                'changed_at'  => now(),
            ]);

            $user->rank_no = $level->id;
            $user->save();
        }

        return $level;
    }

    public function updateUplineRanks(User $downline)
    {
        $current = $downline;

        while ($current->referral_id) {
            $upline = User::where('user_id', $current->referral_id)->first();
            if (!$upline) break;

            $this->updateRank($upline);
            $current = $upline;
        }
    }

    public function getDownlineUserIds(string $referralCode): array
    {
        $ids = [];
        $users = User::where('referral_id', $referralCode)->get();

        foreach ($users as $user) {
            $ids[] = $user->id;
            $ids = array_merge($ids, $this->getDownlineUserIds($user->user_id));
        }

        return $ids;
    }
}
