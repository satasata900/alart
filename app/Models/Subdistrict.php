<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdistrict extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'district_id',
        'code',
        'postal_code',
        'name_ar',
        'name_en',
    ];

    /**
     * Get the district that owns the subdistrict.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the villages for the subdistrict.
     */
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    /**
     * Get the operation areas for the subdistrict.
     */
    public function operationAreas(): HasMany
    {
        return $this->hasMany(OperationArea::class);
    }
}
