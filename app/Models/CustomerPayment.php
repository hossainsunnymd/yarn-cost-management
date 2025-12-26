<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $fillable = [
        'customer_id',
        'credit',
        'debit',
        'challan_no',
        'challan_type',
        'particulars',
        'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
