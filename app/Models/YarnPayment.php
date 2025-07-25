<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnPayment extends Model
{
    protected $fillable=['yarn_party_id','amount'];

    public function yarnParty()
    {
        return $this->belongsTo(YarnParty::class);
    }
}
