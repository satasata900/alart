<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAssignment extends Model
{
    protected $fillable = [
        'report_id',
        'response_point_id',
        'assigned_by',
        'status',
        'notes',
        'assigned_at',
        'completed_at',
    ];
    
    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    /**
     * Get the response point that the report is assigned to.
     */
    public function responsePoint(): BelongsTo
    {
        return $this->belongsTo(ResponsePoint::class);
    }
    
    /**
     * Get the user who assigned the report.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    
    /**
     * Get the report that is assigned.
     * This relationship will be implemented when the Report model is created.
     */
    public function report(): BelongsTo
    {
        // This will be implemented when the Report model is created
        // For now, we'll return a dummy relationship
        return $this->belongsTo(User::class, 'report_id');
    }
}
