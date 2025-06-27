<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'color',
        'is_active'
    ];

    /**
     * النوع لديه العديد من البلاغات
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * الحصول على عدد البلاغات لهذا النوع
     *
     * @return int
     */
    public function getReportsCountAttribute(): int
    {
        return $this->reports()->count();
    }
}
