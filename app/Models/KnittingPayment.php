<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingPayment extends Model
{
    protected $fillable=['knitting_party_id','amount','debit','credit','particulars','challan_no','date'];

    public function knittingParty(){
        return $this->belongsTo(KnittingParty::class);
    }
}
