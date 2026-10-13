<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('provincia_id')->nullable()->constrained()->onDelete('set null');
            $table->dropColumn(['provincia', 'lenguajes']);
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('provincia')->nullable();
            $table->json('lenguajes')->nullable();
            $table->dropForeign(['provincia_id']);
            $table->dropColumn('provincia_id');
        });
    }
};
