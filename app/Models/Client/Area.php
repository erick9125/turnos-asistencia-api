<?php

namespace App\Models\Client;

use App\Models\Attendance\Device;
use App\Models\Attendance\Shift;
use App\Models\Attendance\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'branch_id',
        'name',
        'code',
    ];

    /**
     * Relación: Un área pertenece a una sucursal
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relación: Un área tiene muchos dispositivos
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Relación: Un área tiene muchos trabajadores
     */
    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    /**
     * Relación: Un área tiene muchos turnos
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }
}
