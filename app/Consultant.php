<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    protected $fillable = [
        'name',
        'created_by'
    ];

    public function account()
    {
        return $this->hasMany(Account::class);
    }
}
