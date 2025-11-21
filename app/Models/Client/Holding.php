<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holding extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    /**
     * Relación: Un holding tiene muchas empresas
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
