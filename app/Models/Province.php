<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
    ];

    /**
     * Get the districts for the province.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the operation areas for the province.
     */
    public function operationAreas(): HasMany
    {
        return $this->hasMany(OperationArea::class);
    }
}
