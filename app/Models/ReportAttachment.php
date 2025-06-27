<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAttachment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'report_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_type',
        'size',
        'description',
        'uploaded_by'
    ];

    /**
     * المرفق ينتمي لبلاغ
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * هل المرفق صورة
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * هل المرفق فيديو
     *
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    /**
     * هل المرفق مستند
     *
     * @return bool
     */
    public function isDocument(): bool
    {
        return $this->file_type === 'document';
    }

    /**
     * الحصول على حجم الملف بشكل مقروء
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        $size = $this->size;
        
        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } elseif ($size < 1073741824) {
            return round($size / 1048576, 2) . ' MB';
        } else {
            return round($size / 1073741824, 2) . ' GB';
        }
    }
}
