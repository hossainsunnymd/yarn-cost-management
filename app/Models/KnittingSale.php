<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingSale extends Model
{
    protected $fillable=['customer_id','total_unit','total_amount','challan_no','sale_date','total_cost'];

    public function knittingSaleProducts(){
        return $this->hasMany(KnittingSaleProduct::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
