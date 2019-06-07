<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'email_address',
        'member_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
