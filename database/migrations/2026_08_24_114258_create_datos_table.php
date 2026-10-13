<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datos', function (Blueprint $table) {
            $table->id(); // clave primaria autoincremental
            $table->string('nombre', 50); // varchar(50), igual que la validación max:50
            $table->string('apellido', 50);
            $table->string('correo', 100); // varchar(100)
            $table->string('clave', 255); // guardamos el hash, no el texto
            $table->unsignedTinyInteger('edad'); // entero sin signo (0-255)
            $table->date('nacimiento');
            $table->string('estado_laboral');
            $table->json('lenguajes'); // array de checkboxes
            $table->string('provincia');
            $table->string('presentacion', 100);
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos');
    }
};
