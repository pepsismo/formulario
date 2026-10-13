<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'clave',
        'edad',
        'nacimiento',
        'estado_laboral',
        'provincia_id',
        'presentacion',
    ];

    protected function casts(): array
    {
        return [
            'nacimiento' => 'date',
            'edad' => 'integer',
        ];
    }

    public function domicilio(): HasOne
    {
        return $this->hasOne(Domicilio::class);
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function habilidades(): BelongsToMany
    {
        return $this->belongsToMany(Habilidad::class);
    }
}
