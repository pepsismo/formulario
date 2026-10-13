<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Habilidad extends Model
{
    protected $fillable = ['nombre'];

    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class);
    }
}
