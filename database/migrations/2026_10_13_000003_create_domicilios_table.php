<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domicilios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained()->onDelete('cascade');
            $table->string('calle', 100);
            $table->string('numero', 10);
            $table->string('localidad', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};
