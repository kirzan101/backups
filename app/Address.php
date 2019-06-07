<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
