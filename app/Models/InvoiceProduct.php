<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable=['sewing_receive_id','invoice_id','unit','sale_price'];

    public function sewingReceive(){
        return $this->belongsTo(SewingReceive::class);
    }
}
