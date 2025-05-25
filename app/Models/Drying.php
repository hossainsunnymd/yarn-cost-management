<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drying extends Model
{
    protected $fillable=['knitting_id','drying_party_id','unit','total_amount'];

    public function knitting()
    {
        return $this->belongsTo(Knitting::class);
    }

    public function dryingParty()
    {
        return $this->belongsTo(DryingParty::class);
    }
}
