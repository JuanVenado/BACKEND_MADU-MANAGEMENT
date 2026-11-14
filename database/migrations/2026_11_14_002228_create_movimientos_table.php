<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->decimal('cantidad_base', 10, 2);
            $table->foreignId('producto')->constrained('productos')->onDelete('restrict');
            $table->decimal('saldo_posterior', 10, 2);
            $table->string('motivo')->nullable();
            $table->decimal('saldo_anterior', 10, 2);
            $table->string('tipo');
            $table->string('referencia')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->foreignId('producto_presentacion')->constrained('producto_presentacion')->onDelete('restrict');
            $table->foreignId('usuario')->constrained('usuarios')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};