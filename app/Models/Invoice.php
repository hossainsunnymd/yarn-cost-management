<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=[
        'customer_id',
        'total_amount',
        'challan_no',
        'invoice_date',
        'total_unit',
        'total_cost',
    ];

    public function invoiceProducts(){
        return $this->hasMany(InvoiceProduct::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
