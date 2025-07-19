<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSaleProduct extends Model
{
    protected $fillable=['dyeing_receive_id','fabric_sale_id','unit','total_cost','per_unit_cost','sale_price','role'];

    public function dyeingReceive()
    {
        return $this->belongsTo(DyeingReceive::class);
    }
}
