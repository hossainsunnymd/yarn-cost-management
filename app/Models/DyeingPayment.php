<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyeingPayment extends Model
{
    protected $fillable=['dyeing_party_id','amount'];

    public function dyeingParty()
    {
        return $this->belongsTo(DyeingParty::class);
    }   
}
