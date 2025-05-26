<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnPurchase extends Model
{
    protected $fillable=['yarn_party_id','lot_no','name','description','unit','weight','bags','yarn_rate','bill_amount','labour_cost','total_amount','available_unit','current_total_amount'];
}
