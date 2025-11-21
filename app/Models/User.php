<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status' => 'string',
        ];
    }

    /**
     * Verifica si el usuario es manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Verifica si el usuario es worker
     */
    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    /**
     * Verifica si el usuario es admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Relación: Un usuario puede tener un trabajador asociado (solo si es worker)
     */
    public function worker(): HasOne
    {
        return $this->hasOne(\App\Models\Attendance\Worker::class);
    }
}
