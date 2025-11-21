<?php

namespace App\Models\Attendance;

use App\Models\Client\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'area_id',
        'name',
        'rut',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relación: Un trabajador puede tener un usuario asociado (opcional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un trabajador pertenece a un área
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Relación: Un trabajador tiene muchos turnos
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * Relación: Un trabajador tiene muchas marcas
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Relación: Un trabajador tiene muchas ausencias
     */
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    /**
     * Scope: Solo trabajadores activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
