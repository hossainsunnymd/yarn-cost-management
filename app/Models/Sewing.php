<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewing extends Model
{
    protected $fillable=['cutting_receive_id','unit','available_unit','sewing_party_id','challan_no'];

    public function cuttingReceive()
    {
        return $this->belongsTo(CuttingReceive::class);
    }

    public function sewingParty()  {
        return $this->belongsTo(SewingParty::class);
    }
}
