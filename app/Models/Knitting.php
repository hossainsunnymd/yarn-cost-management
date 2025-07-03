<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knitting extends Model
{
    protected $fillable=['knitting_party_id','yarn_purchase_id','total_unit','available_unit','total_cost','per_unit_cost'];

    public function knittingParty()
    {
        return $this->belongsTo(KnittingParty::class);
    }

    public function knittingYarn()
    {
        return $this->hasMany(KnittingYarn::class);
    }
}
