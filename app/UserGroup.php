<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $fillable = [
        'user_group_name',
        'description',
        'modules_access',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
