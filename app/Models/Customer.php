<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'phoneNum',
        'wins',
        'losses',
        'billed_amount',
        'paid_amount',
        'pending_amount'
    ];

    // Auto-calc pending amount
    protected static function booted()
    {
        static::saving(function ($customer) {

            // Only auto-calc if it's a new customer OR values not manually changed
            if (!$customer->isDirty('pending_amount')) {
                $customer->pending_amount = $customer->billed_amount - $customer->paid_amount;
            }

        });
    }


    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
