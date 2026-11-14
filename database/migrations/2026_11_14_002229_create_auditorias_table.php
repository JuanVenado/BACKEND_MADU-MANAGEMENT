<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->string('operacion');
            $table->foreignId('usuario')->constrained('usuarios')->onDelete('restrict');
            $table->timestamp('fecha')->useCurrent();
            $table->unsignedBigInteger('registro_afectado');
            $table->string('tabla_afectada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};