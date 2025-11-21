<?php

namespace App\Models\Attendance;

use App\Models\Client\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'worker_id',
        'area_id',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Relación: Un turno pertenece a un trabajador
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Relación: Un turno pertenece a un área
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Relación: Un turno tiene muchas ausencias
     */
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    /**
     * Scope: Turnos del día
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_at', $date);
    }

    /**
     * Scope: Turnos en estado específico
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Turnos que se solapan con un rango de tiempo
     */
    public function scopeOverlapping($query, $startAt, $endAt, $workerId = null)
    {
        $query->where(function ($q) use ($startAt, $endAt) {
            $q->whereBetween('start_at', [$startAt, $endAt])
                ->orWhereBetween('end_at', [$startAt, $endAt])
                ->orWhere(function ($q2) use ($startAt, $endAt) {
                    $q2->where('start_at', '<=', $startAt)
                        ->where('end_at', '>=', $endAt);
                });
        });

        if ($workerId) {
            $query->where('worker_id', $workerId);
        }

        return $query;
    }
}
