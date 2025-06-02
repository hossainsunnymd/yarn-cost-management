<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingParty extends Model
{
    protected $fillable=['name','phone','address','total_amount','due_amount','last_payment','last_payment_date'];

    public function knittings(){
        return $this->hasMany(Knitting::class);
    }
}
