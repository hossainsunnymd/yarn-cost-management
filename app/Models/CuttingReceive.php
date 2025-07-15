<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingReceive extends Model
{
    protected $fillable=['cutting_id','total_cost','per_unit_cost','unit','available_unit','cutting_cost','wastage'];

    public function cutting(){
       return $this->belongsTo(Cutting::class);
    }
}
