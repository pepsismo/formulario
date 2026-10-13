<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habilidad_persona', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained()->onDelete('cascade');
            $table->foreignId('habilidad_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habilidad_persona');
    }
};
