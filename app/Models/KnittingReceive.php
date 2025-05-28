<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingReceive extends Model
{
    protected $fillable=['knitting_id','unit','total_amount','wastage','per_unit_cost','knitting_cost'];
}
