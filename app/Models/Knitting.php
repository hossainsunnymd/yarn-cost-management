<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knitting extends Model
{
    protected $fillable=['knitting_party_id','yarn_purchase_id','unit'];

    public function knittingParty()
    {
        return $this->belongsTo(KnittingParty::class);
    }

    public function yarnPurchase()
    {
        return $this->belongsTo(YarnPurchase::class);
    }
}
