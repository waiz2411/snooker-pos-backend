<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'club_id',
        'table_name',
        'status',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
