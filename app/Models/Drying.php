<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drying extends Model
{
    protected $fillable=['knitting_receive_id','drying_party_id','unit','available_unit'];

    public function knitting()
    {
        return $this->belongsTo(Knitting::class);
    }

    public function dryingParty()
    {
        return $this->belongsTo(DryingParty::class);
    }
}
