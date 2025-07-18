<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    protected $fillable=[
        'dyeing_receive_id',
        'cutting_party_id',
        'category_id',
        'unit',
        'available_unit',
        'roll'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function cuttingParty(){
        return $this->belongsTo(CuttingParty::class);
    }
}
