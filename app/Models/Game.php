<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'club_id',
        'table_id',
        'player1_id',
        'player2_id',
        'player3_id',
        'player4_id',
        'winners',
        'losers',
        'billing_type',
        'price_per_minute',
        'full_game_price',
        'start_time',
        'status',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
