<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->decimal('monto_total', 10, 2);
            $table->text('direccion_envio');
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('nombre_receptor', 100)->nullable();
            $table->enum('estado', ['pendiente_pago', 'pendiente_validacion', 'procesando', 'completado', 'cancelado'])->default('pendiente_pago');
            $table->timestamp('fecha_pedido')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }
    public function down(): void { Schema::dropIfExists('pedidos'); }
};