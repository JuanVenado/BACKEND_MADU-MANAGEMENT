<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_presentacion', function (Blueprint $table) {
            $table->id(); // <-- Agregar esto
            $table->foreignId('producto')->constrained('productos')->onDelete('cascade');
            $table->foreignId('presentacion')->constrained('presentaciones')->onDelete('cascade');
            $table->unique(['producto', 'presentacion']); // <-- Cambiar de primary a unique
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_presentacion');
    }
};