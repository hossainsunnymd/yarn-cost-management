<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingYarn extends Model
{
    protected $fillable=['knitting_id','yarn_purchase_id','unit','per_unit_cost'];

    public function yarnPurchase(){
        return $this->belongsTo(YarnPurchase::class);
    }
}
