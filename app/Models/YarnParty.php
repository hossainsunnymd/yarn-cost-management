<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YarnParty extends Model
{
    protected $fillable=[
        'name',
        'address',
        'phone',
        'total_amount',
        'due_amount',
    ];
}
