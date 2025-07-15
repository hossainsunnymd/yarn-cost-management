<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSale extends Model
{
    protected $fillable=['customer_id','total_cost','total_sale_price'];

    public function fabricSaleProducts(){
        return $this->hasMany(FabricSaleProduct::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
