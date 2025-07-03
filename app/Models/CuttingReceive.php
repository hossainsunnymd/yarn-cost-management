<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingReceive extends Model
{
    protected $fillable=['cutting_id','unit','available_unit','wastage'];

    public function cuttingReceive(){
        $this->belongsTo(Cutting::class);
    }
}
