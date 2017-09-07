<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventosNocheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventoNoche', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_persona')->unsigned();
            $table->integer('id_tipoEvento')->unsigned();
            //$table->integer('id_momentoDia')->unsigned();
            $table->time('hora')->nullable();
            $table->datetime('fechaInicio');
            $table->datetime('fechaFin')->nullable();
            $table->boolean('todoElDia')->nullable(); 
            $table->mediumtext('titulo')->nullable();
            $table->string('color')->nullable(); 
            $table->string('observacion')->nullable();

            /*****************************************************
                            DATOS TABLA SUEÑO
            *****************************************************/
            //campos provisionales, transformar desde script para no usarlos!!!!!!
            //$table->boolean('v1')->nullable();
            //$table->boolean('v1m')->nullable();
            //$table->boolean('v2')->nullable();
            //$table->boolean('v2m')->nullable();
            //$table->boolean('v3')->nullable();
            //$table->boolean('v3m')->nullable();
            //$table->boolean('v4')->nullable();
            //$table->boolean('v4m')->nullable();
            //$table->boolean('c1')->nullable();
            //$table->boolean('c1m')->nullable();
            //$table->boolean('c2')->nullable();
            //$table->boolean('c2m')->nullable();
            //$table->boolean('c3')->nullable();
            //$table->boolean('c3m')->nullable();
            //$table->boolean('c4')->nullable();
            //$table->boolean('c4m')->nullable();
            //$table->boolean('c5')->nullable();
            //$table->boolean('c5m')->nullable();
            //$table->boolean('c6')->nullable();
            //$table->boolean('c6m')->nullable();
            //$table->boolean('c7')->nullable();
            //$table->boolean('c7m')->nullable();
            //$table->boolean('c8')->nullable();
            //$table->boolean('c8m')->nullable();
            //$table->boolean('c9')->nullable();

            //campos obligatorios
            //$table->boolean('hayComenta')->nullable();
            //$table->string('comenta')->nullable(); 
            $table->integer('horasDormidas')->nullable();
            /*****************************************************
                            FIN DATOS TABLA SUEÑO
            *****************************************************/

            //crear claves foraneas
            $table->foreign('id_persona')->references('id')->on('persona')->onDelete('cascade');
            $table->foreign('id_tipoEvento')->references('id')->on('tipoEvento')->onDelete('cascade');
            //$table->foreign('id_momentoDia')->references('id')->on('momentoDia')->onDelete('cascade');

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
        Schema::drop('eventoNoche');
    }
}
