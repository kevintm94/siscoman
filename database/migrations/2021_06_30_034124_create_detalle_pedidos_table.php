<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('plato_id')->nullable()->constrained('platos');
            $table->boolean('medio')->nullable();
            $table->string('detalle_coccion', 150)->nullable();
            $table->foreignId('guarnicion_id')->nullable()->constrained('guarniciones');
            $table->boolean('extra')->nullable();
            $table->foreignId('bebida_id')->nullable()->constrained('bebidas');
            $table->string('comentario', 50)->nullable();
            $table->string('tipo', 15);
            $table->string('estado', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
}
