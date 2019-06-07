<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_member';

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'account_id');
    }
}
