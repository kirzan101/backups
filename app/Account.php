<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'sales_deck',
        'consultant_id',
    ];

    public function accountMember()
    {
        return $this->hasMany(AccountMember::class);
    }

    public function members()
    {
        return $this->hasManyThrough(Member::class, AccountMember::class, 'account_id', 'id', 'id', 'member_id');
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

}
