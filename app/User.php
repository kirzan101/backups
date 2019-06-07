<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'user_group',
        'password',
        'department',
        'status',
        'last_login',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function userGroup()
    {
        return $this->hasMany(UserGroup::class, 'usergroup');
    }

    public function auditLog()
    {
        return $this->hasMany(AuditLog::class);
    }
}
