<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSaleProduct extends Model
{
    protected $fillable=['dyeing_receive_id','fabric_sale_id','unit','price','total_amount','roll'];

    public function dyeingReceive()
    {
        return $this->belongsTo(DyeingReceive::class);
    }
}
