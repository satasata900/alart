<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subdistrict_id',
        'code',
        'name_ar',
        'name_en',
    ];

    /**
     * Get the subdistrict that owns the village.
     */
    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
    }

    /**
     * Get the operation areas for the village.
     */
    public function operationAreas(): HasMany
    {
        return $this->hasMany(OperationArea::class);
    }
}
