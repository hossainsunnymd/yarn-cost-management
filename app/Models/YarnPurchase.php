<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnPurchase extends Model
{
    protected $fillable = ['yarn_party_id', 'name', 'description', 'unit', 'available_unit', 'bags', 'yarn_rate',
                         'bill_amount', 'labour_cost', 'total_amount', 'current_total_amount', 'per_unit_cost'];
}
