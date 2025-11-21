<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absence extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'shift_id',
        'worker_id',
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relación: Una ausencia pertenece a un turno (opcional)
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Relación: Una ausencia pertenece a un trabajador
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }
}
