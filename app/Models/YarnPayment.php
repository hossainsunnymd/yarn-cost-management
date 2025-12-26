<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnPayment extends Model
{
    protected $fillable=['yarn_party_id','amount','debit','credit','particulars','date','challan_no'];

    public function yarnParty()
    {
        return $this->belongsTo(YarnParty::class);
    }
}
