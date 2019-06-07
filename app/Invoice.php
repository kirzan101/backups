<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'principal_amount',
        'downpayment',
        'total_paid_amount',
        'remaining_balance',
        'status',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetails::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
