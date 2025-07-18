<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingPayment extends Model
{
   protected $fillable=['cutting_party_id','amount'];

   public function cuttingParty(){
       return $this->belongsTo(CuttingParty::class);
   }
}
