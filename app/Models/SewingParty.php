<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SewingParty extends Model
{
    protected $fillable=['name','phone','address','due_amount'];

    public function sewings()
    {
        return $this->hasMany(Sewing::class);
    }
}
