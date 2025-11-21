<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'holding_id',
        'name',
        'rut',
    ];

    /**
     * Relación: Una empresa pertenece a un holding
     */
    public function holding(): BelongsTo
    {
        return $this->belongsTo(Holding::class);
    }

    /**
     * Relación: Una empresa tiene muchas sucursales
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
