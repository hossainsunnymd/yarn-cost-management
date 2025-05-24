<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnittingParty extends Model
{
    protected $fillable=['name','phone','address','total_amount','due_amount'];
}
