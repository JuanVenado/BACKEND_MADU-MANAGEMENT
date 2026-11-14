<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto')->constrained('productos')->onDelete('cascade');
            $table->foreignId('proveedor')->constrained('proveedores')->onDelete('cascade');
            $table->unique(['producto', 'proveedor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_proveedor');
    }
};