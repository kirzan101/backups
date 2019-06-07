<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;


class Setting extends Model
{
    public function userGroup()
    {
        return $this->hasMany(UserGroup::class);
    }

    public function module()
    {
        return $this->hasMany(Module::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function totalUser()
    {
        // return $this->query->where('user_group_name', 'admin');
        // return $this->User::where('user_group', 1)->count();
    }
}
