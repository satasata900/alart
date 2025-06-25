<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Village;
use App\Models\Observer;

class ResponsePoint extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'code',
        'operation_area_id',
        'province_id',
        'district_id',
        'subdistrict_id',
        'village_id',
        'location_lat',
        'location_lng',
        'address',
        'phone',
        'capacity',
        'is_active',
        'description',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'location_lat' => 'decimal:7',
        'location_lng' => 'decimal:7',
    ];
    
    /**
     * Get the operation area that owns the response point.
     */
    public function operationArea(): BelongsTo
    {
        return $this->belongsTo(OperationArea::class);
    }
    
    /**
     * Get the province that owns the response point.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    
    /**
     * Get the district that owns the response point.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
    
    /**
     * Get the subdistrict that owns the response point.
     */
    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
    }
    
    /**
     * Get the village that owns the response point.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
    
    /**
     * Get the observers associated with the response point.
     */
    public function observers(): BelongsToMany
    {
        return $this->belongsToMany(Observer::class, 'response_point_observer');
    }
    
    /**
     * Get the report assignments for the response point.
     */
    public function reportAssignments(): HasMany
    {
        return $this->hasMany(ReportAssignment::class);
    }
}
