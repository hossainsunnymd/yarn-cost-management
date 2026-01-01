<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricSale extends Model
{
    protected $fillable=['customer_id','total_unit','total_amount','sale_date','challan_no','total_cost'];

    public function fabricSaleProducts(){
        return $this->hasMany(FabricSaleProduct::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
