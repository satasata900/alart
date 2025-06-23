<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'province_id',
        'code',
        'postal_code',
        'name_ar',
        'name_en',
    ];

    /**
     * Get the province that owns the district.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the subdistricts for the district.
     */
    public function subdistricts(): HasMany
    {
        return $this->hasMany(Subdistrict::class);
    }

    /**
     * Get the operation areas for the district.
     */
    public function operationAreas(): HasMany
    {
        return $this->hasMany(OperationArea::class);
    }
}
