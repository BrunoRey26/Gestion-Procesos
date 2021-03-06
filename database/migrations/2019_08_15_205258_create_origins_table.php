<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('origins', function (Blueprint $table) {
            $table->bigIncrements('id');                                        # Indice Incrementable.
            $table->bigInteger('misc_id')->nullable()->unsigned();              # Tipo de Entidad 'table_various'.
            $table->foreign('misc_id')->references('id')->on('misc');
            $table->string('name');                                             # Nombre Descriptivo.
            $table->timestamps();                                               # Creado / Modificado.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('origins');
    }
}
