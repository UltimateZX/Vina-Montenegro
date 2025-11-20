<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('productos', function (Blueprint $table) {
        // Cambiamos a LONGTEXT para que quepa el código de la imagen
        $table->longText('url_imagen')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('longtext', function (Blueprint $table) {
            //
        });
    }
};
