<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'module_name',
        'description',
    ];

    public function settings()
    {
        return $this->belongsTo(Setting::class);
    }

    public function auditLog()
    {
        return $this->hasMany(AuditLog::class);
    }
}
