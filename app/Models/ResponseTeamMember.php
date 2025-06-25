<?php
// تم حذف موديل ResponseTeamMember بالكامل بناءً على طلب المستخدم. الملف معطل ولن يتم تحميله.
// هذا الملف تم تعطيله بالكامل بعد حذف فريق الاستجابة
// تم حذف موديل ResponseTeamMember بالكامل - الملف معطل

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// تم حذف موديل ResponseTeamMember بالكامل بناءً على طلب المستخدم
{
    use SoftDeletes;
    
    protected $fillable = [
        'response_point_id',
        'name',
        'username',
        'password',
        'rank',
        'phone',
        'whatsapp',
        'is_leader',
        'is_active',
        'last_login',
    ];
    
    protected $casts = [
        'is_leader' => 'boolean',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    /**
     * Get the response point that owns the team member.
     */
    public function responsePoint(): BelongsTo
    {
        return $this->belongsTo(ResponsePoint::class);
    }
    
    /**
     * Hash the password when setting it.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
