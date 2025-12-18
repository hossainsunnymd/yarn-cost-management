<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SewingPayment extends Model
{
    protected $fillable=['sewing_party_id','amount','debit','credit','challan_no','particulars'];

    public function sewingParty(){
        return $this->belongsTo(SewingParty::class);
    }
}
