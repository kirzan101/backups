<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'description',
        'user_id',
        'subject_type',
        'module_id',
        'properties',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
