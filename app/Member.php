<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'membership_type',
        'birthday',
        'gender',
        'status',
        'created_by',
    ];

    public function accountMember()
    {
        return $this->hasMany(AccountMember::class);
    }

    public function accounts()
    {
        return $this->hasManyThrough(Account::class, AccountMember::class, 'member_id', 'id', 'id', 'account_id');
    }

    public function email()
    {
        return $this->hasOne(Email::class);
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type');
    }

    public function contactNumbers()
    {
        return $this->hasMany(ContactNumber::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
