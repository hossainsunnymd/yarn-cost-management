<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knitting extends Model
{
    protected $fillable=['knitting_party_id','total_unit','available_unit','total_cost','per_unit_cost','fabric_name','send_date','challan_no'];

    public function knittingParty()
    {
        return $this->belongsTo(KnittingParty::class);
    }

    public function knittingYarn()
    {
        return $this->hasMany(KnittingYarn::class);
    }
}
