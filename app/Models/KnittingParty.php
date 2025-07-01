<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingParty extends Model
{
    protected $fillable=['name','phone','address','due_amount'];

    public function knittings(){
        return $this->hasMany(Knitting::class);
    }

    public function knittingPayments(){
        return $this->hasMany(KnittingPayment::class);
    }
}
