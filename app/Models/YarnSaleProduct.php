<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnSaleProduct extends Model
{
    protected $fillable = ['yarn_purchase_id','yarn_sale_id','unit','price','total_amount'];
}
