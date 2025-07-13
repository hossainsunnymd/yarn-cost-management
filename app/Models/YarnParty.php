<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnParty extends Model
{
    protected $fillable=[
        'name',
        'address',
        'phone',
        'due_amount',
    ];

    public function yarnPurchase(){
        return $this->hasMany(YarnPurchase::class);
    }

    public function yarnPayments(){
        return $this->hasMany(YarnPayment::class);
    }
}
