<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'worker_id',
        'device_id',
        'direction',
        'source_type',
        'marked_at',
        'truncated_minute',
        'exported_at',
    ];

    protected $casts = [
        'marked_at' => 'datetime',
        'truncated_minute' => 'datetime',
        'exported_at' => 'datetime',
        'direction' => 'string',
        'source_type' => 'string',
    ];

    /**
     * Relación: Una marca pertenece a un trabajador
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Relación: Una marca pertenece a un dispositivo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Scope: Marcas de entrada
     */
    public function scopeIn($query)
    {
        return $query->where('direction', 'in');
    }

    /**
     * Scope: Marcas de salida
     */
    public function scopeOut($query)
    {
        return $query->where('direction', 'out');
    }

    /**
     * Scope: Marcas en un rango de fechas
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('marked_at', [$startDate, $endDate]);
    }
}
