<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $fillable = [
        'invoice_id',
        'item',
        'quantity',
        'unit_price',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

}
