<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'company_id',
        'name',
        'code',
    ];

    /**
     * Relación: Una sucursal pertenece a una empresa
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relación: Una sucursal tiene muchas áreas
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}
