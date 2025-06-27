<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'report_type_id',
        'status',
        'urgency_level',
        'reporter_type',
        'created_by',
        'operation_area_id',
        'assigned_to',
        'observer_id',
        'location_data',
        'report_date',
        'notes'
    ];

    /**
     * الحقول التي يجب تحويلها
     *
     * @var array
     */
    protected $casts = [
        'location_data' => 'array',
        'report_date' => 'datetime',
    ];

    /**
     * الالوان المرتبطة بمستويات الأهمية
     */
    const URGENCY_COLORS = [
        'normal' => 'bg-info',
        'urgent' => 'bg-warning',
        'emergency' => 'bg-danger',
    ];

    /**
     * الالوان المرتبطة بحالات البلاغ
     */
    const STATUS_COLORS = [
        'new' => 'bg-primary',
        'in_progress' => 'bg-warning',
        'resolved' => 'bg-success',
        'closed' => 'bg-secondary',
    ];

    /**
     * البلاغ ينتمي لنوع
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportType(): BelongsTo
    {
        return $this->belongsTo(ReportType::class);
    }

    /**
     * البلاغ ينتمي لمنطقة عمليات
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operationArea(): BelongsTo
    {
        return $this->belongsTo(OperationArea::class);
    }

    /**
     * البلاغ ينتمي لراصد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function observer(): BelongsTo
    {
        return $this->belongsTo(Observer::class);
    }

    /**
     * البلاغ ينتمي لنقطة استجابة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsePoint(): BelongsTo
    {
        return $this->belongsTo(ResponsePoint::class, 'assigned_to');
    }

    /**
     * البلاغ لديه العديد من المرفقات
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    /**
     * هل البلاغ جديد
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    /**
     * هل البلاغ قيد المعالجة
     *
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * هل البلاغ تمت معالجته
     *
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * هل البلاغ مغلق
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * هل البلاغ عاجل
     *
     * @return bool
     */
    public function isUrgent(): bool
    {
        return $this->urgency_level === 'urgent' || $this->urgency_level === 'emergency';
    }

    /**
     * هل البلاغ طارئ
     *
     * @return bool
     */
    public function isEmergency(): bool
    {
        return $this->urgency_level === 'emergency';
    }

    /**
     * الحصول على لون حالة البلاغ
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-secondary';
    }

    /**
     * الحصول على لون مستوى الأهمية
     *
     * @return string
     */
    public function getUrgencyColorAttribute(): string
    {
        return self::URGENCY_COLORS[$this->urgency_level] ?? 'bg-info';
    }

    /**
     * الحصول على عدد المرفقات
     *
     * @return int
     */
    public function getAttachmentsCountAttribute(): int
    {
        return $this->attachments()->count();
    }

    /**
     * الحصول على اسم المبلغ
     *
     * @return string
     */
    public function getReporterNameAttribute(): string
    {
        if ($this->reporter_type === 'observer' && $this->observer) {
            return $this->observer->name;
        }
        
        return '\u0645\u062f\u064a\u0631 \u0627\u0644\u0646\u0638\u0627\u0645'; // مدير النظام
    }
}
