<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingPayment extends Model
{
    protected $fillable=['knitting_party_id','amount'];

    public function knittingParty(){
        return $this->belongsTo(KnittingParty::class);
    }
}
