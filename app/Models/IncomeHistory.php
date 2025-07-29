<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeHistory extends Model
{
    protected $fillable = [
        'user_id', 'from_user_id', 'type', 'amount', 'note', 'earned_at'
    ];

    protected $dates = ['earned_at'];
}

