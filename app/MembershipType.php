<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    protected $fillable = [
        'type',
        'created_by',
    ];

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class);
    }

}
