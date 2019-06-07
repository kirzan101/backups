<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'account_id',
        'card_number',
        'status',
        'date_issued',
        'valid_from',
        'valid_to',
        'remarks',
        'date_redeemed',
        'check_in',
        'check_out',
        'guest_first_name',
        'guest_middle_name',
        'guest_last_name',
        'created_by',
        'updated_by',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
