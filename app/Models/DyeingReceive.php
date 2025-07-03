<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyeingReceive extends Model
{
    protected $fillable=['dyeing_id','unit','total_cost','per_unit_cost','wastage','available_unit','dyeing_cost','roll'];

    public function dyeing()
    {
        return $this->belongsTo(Dyeing::class);
    }
}
