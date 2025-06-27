<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

class OperationArea extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($area) {
            // Detach all observer relationships when area is deleted (soft or hard)
            $area->observers()->detach();
        });
    }

    public function observers()
    {
        return $this->belongsToMany(\App\Models\Observer::class, 'observer_operation_area');
    }
    
    /**
     * Obtener todos los reportes asociados a esta área de operación
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'province_id',
        'district_id',
        'subdistrict_id',
        'village_id',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the province that owns the operation area.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the district that owns the operation area.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the subdistrict that owns the operation area.
     */
    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
    }

    /**
     * Get the village that owns the operation area.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}
