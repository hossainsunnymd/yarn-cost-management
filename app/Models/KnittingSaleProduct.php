<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingSaleProduct extends Model
{
    protected $fillable = ['knitting_sale_id','knitting_receive_id','unit','price','total_amount','per_unit_cost'];
}
