<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('unidad')->constrained('unidades')->onDelete('restrict');
            $table->foreignId('categoria')->constrained('categorias')->onDelete('restrict');
            $table->boolean('activo')->default(true);
            $table->boolean('existencial')->default(false);
            $table->integer('stock_min')->default(0);
            $table->string('coding')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};