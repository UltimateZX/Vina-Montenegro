<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos');
            $table->string('metodo_pago', 50)->default('Yape');
            $table->string('url_voucher', 255);
            $table->timestamp('fecha_carga')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('estado_validacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('notas_admin')->nullable();
            $table->timestamp('fecha_validacion')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('pagos'); }
};