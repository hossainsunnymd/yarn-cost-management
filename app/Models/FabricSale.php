<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSale extends Model
{
    protected $fillable=['unit','total_amount','drying_receive_id'];
}
