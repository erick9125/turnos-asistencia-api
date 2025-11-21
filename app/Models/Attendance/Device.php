<?php

namespace App\Models\Attendance;

use App\Models\Client\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'area_id',
        'name',
        'device_key',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];

    /**
     * Relación: Un dispositivo pertenece a un área
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Relación: Un dispositivo tiene muchas marcas
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Scope: Solo dispositivos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
