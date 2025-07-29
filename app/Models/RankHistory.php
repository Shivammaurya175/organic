<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankHistory extends Model
{
    //
    protected $fillable = ['user_id', 'old_rank', 'new_rank', 'self_volume', 'team_volume', 'changed_at'];

}
