<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'net_amount',
        'wallet_address',
        'wallet_type',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
