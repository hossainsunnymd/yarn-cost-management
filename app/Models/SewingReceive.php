<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SewingReceive extends Model
{
    protected $fillable=['sewing_id','unit','available_unit','wastage'];

    public function sewing(){
        return $this->belongsTo(Sewing::class);
    }
}
