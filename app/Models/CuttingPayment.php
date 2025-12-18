<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingPayment extends Model
{
   protected $fillable=['cutting_party_id','amount','debit','credit','particulars','challan_no'];

   public function cuttingParty(){
       return $this->belongsTo(CuttingParty::class);
   }
}
