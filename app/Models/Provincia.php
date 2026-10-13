<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    protected $fillable = ['nombre'];

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class);
    }
}
