<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function usergroup()
    {
        return $this->belongsTo(UserGroup::class);
    }
}
