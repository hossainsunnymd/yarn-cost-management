<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    protected $fillable=[
        'dyeing_receive_id',
        'category_id',
        'unit',
        'available_unit',
        'roll'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
