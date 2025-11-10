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
    Schema::table('productos', function (Blueprint $table) {
        // 1. Añadimos la columna
        // Por defecto, todos los productos nuevos estarán ACTIVOS (true)
        $table->boolean('is_active')->default(true)->after('url_imagen');

        // 2. (Opcional) Hacemos un índice para que las búsquedas sean rápidas
        $table->index('is_active');
    });
}

public function down(): void
{
    Schema::table('productos', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
};
