<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');                                        # Indice Incrementable.
            $table->string('description')->nullable()->default(null);           # Descripcion de la Peticion. 
            $table->bigInteger('misc_id')->nullable()->unsigned();          # Nivel de Prioridad 'table_various'
            $table->foreign('misc_id')->references('id')->on('misc');       
            $table->bigInteger('user_id')->nullable()->unsigned();              # Indice de Usuario 'table_users'
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('origin_id')->nullable()->unsigned();            # Indice del origen 'table_origins'
            $table->foreign('origin_id')->references('id')->on('origins');      
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
        Schema::dropIfExists('requests');
    }
}
