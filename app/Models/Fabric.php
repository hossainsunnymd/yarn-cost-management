<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
     protected $fillable=['drying_id','unit','total_cost'];

     public function drying(){
        return $this->belongsTo(Drying::class);
    }
}
