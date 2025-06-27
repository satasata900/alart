<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsePointItem extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن ملؤها جماعياً
     */
    protected $fillable = [
        'response_point_id',
        'code',
        'name',
        'mobile',
        'whatsapp',
        'telegram_id',
        'description',
        'is_leader',
    ];

    /**
     * القيم الافتراضية للحقول
     */
    protected $attributes = [
        'is_leader' => false,
    ];

    /**
     * تحويل الحقول
     */
    protected $casts = [
        'is_leader' => 'boolean',
    ];

    /**
     * علاقة مع نقطة الاستجابة
     */
    public function responsePoint()
    {
        return $this->belongsTo(ResponsePoint::class);
    }
}
