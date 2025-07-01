<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=[
        'customer_id',
        'total',
    ];

    public function invoiceProducts(){
        return $this->hasMany(InvoiceProduct::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
