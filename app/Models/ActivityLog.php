<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'observer_id',
        'action',
        'description',
        'ip_address',
        'created_at',
    ];

    public function observer()
    {
        return $this->belongsTo(Observer::class);
    }
}
