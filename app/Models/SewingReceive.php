<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SewingReceive extends Model
{
    protected $fillable=['sewing_id','total_cost','unit','available_unit','wastage','per_unit_cost','sewing_cost','image','extra_cost'];

    public function sewing(){
        return $this->belongsTo(Sewing::class);
    }
}
