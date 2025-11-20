<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Club extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'club_name',
        'owner_name',
        'email',
        'phone',
        'password',
        'account_status',
        'payment_status',
        'last_paid',
        'expiry_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

}
