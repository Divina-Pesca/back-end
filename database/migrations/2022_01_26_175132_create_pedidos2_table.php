<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidos2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->double("subtotal");
            $table->double("iva");
            $table->double("descuento")->nullable();
            $table->double("total");
            $table->double("latitud");
            $table->double("longitud");
            $table->string("metodo_pago");
            $table->boolean('entregado');
            $table->unsignedBigInteger('detalle_pedidos_id');
            $table->foreign('detalle_pedidos_id')->references('id')->on('detalle_pedidos');
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
        Schema::dropIfExists('pedidos');
    }
}
