<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioCuponesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_cupones', function (Blueprint $table) {
            $table->unsignedBigInteger('cupon_id');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('cupon_id')->references('id')->on('cupon');
            $table->foreign('usuario_id')->references('id')->on('usuario');
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
        Schema::dropIfExists('usuario_cupones');
    }
}
