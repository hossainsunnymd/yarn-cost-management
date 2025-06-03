<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DryingReceive extends Model
{
    protected $fillable=['drying_id','unit','total_amount','per_unit_cost','wastage','available_unit'];

    public function drying()
    {
        return $this->belongsTo(Drying::class);
    }
}
