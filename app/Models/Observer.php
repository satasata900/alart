<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observer extends Model
{
    use SoftDeletes;
    protected $hidden = [
        'password',
    ];
    protected $fillable = [
        'name',
        'username',
        'password',
        'code',
        'whatsapp',
        'phone',
        'description',
        'rank_stars',
        'is_active',
    ];
    /**
     * Automatically hash password when set
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    protected $casts = [
        'last_login' => 'datetime',
    ];

    protected $dates = ['deleted_at'];



    public function operationAreas()
    {
        return $this->belongsToMany(OperationArea::class, 'observer_operation_area');
    }
}
