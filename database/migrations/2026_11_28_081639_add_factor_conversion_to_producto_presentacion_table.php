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
        Schema::table('producto_presentacion', function (Blueprint $table) {
            $table->decimal('factor_conversion', 8, 4)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_presentacion', function (Blueprint $table) {
            $table->dropColumn('factor_conversion');
        });
    }
};
