<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'total_earned',
        'daily_roi_percent',
        'status',
        'last_roi_at'
    ];

    protected $casts = [
        'last_roi_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
