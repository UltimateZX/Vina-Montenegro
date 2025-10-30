<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 100);
            $table->string('email', 100)->unique();
            $table->string('password_hash', 255); // O usa $table->string('password');
            $table->enum('rol', ['cliente', 'admin'])->default('cliente');
            $table->timestamp('fecha_registro')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }
    public function down(): void { Schema::dropIfExists('usuarios'); }
};