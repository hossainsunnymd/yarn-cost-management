<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyeingParty extends Model
{
    protected $fillable=['name','phone','address','due_amount'];

    public function dyeings(){
        return $this->hasMany(Dyeing::class);
    }

    public function dyeingPayments(){
        return $this->hasMany(DyeingPayment::class);
    }
}
