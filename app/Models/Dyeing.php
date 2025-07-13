<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dyeing extends Model
{
     protected $fillable=['knitting_receive_id','dyeing_party_id','unit','available_unit','color','roll','design_name'];

    public function knitting()
    {
        return $this->belongsTo(Knitting::class);
    }

    public function dyeingParty()
    {
        return $this->belongsTo(DyeingParty::class);
    }


}
