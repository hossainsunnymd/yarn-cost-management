<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnSale extends Model
{
    protected $fillable=['total_unit','total_amount','sale_date','challan_no','customer_id','total_cost'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function yarnSaleProducts()
    {
        return $this->hasMany(YarnSaleProduct::class);
    }
}
