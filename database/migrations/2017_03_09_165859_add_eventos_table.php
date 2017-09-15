<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_persona')->unsigned();
            $table->integer('id_tipoEvento')->unsigned();
            $table->integer('id_momentoDia')->unsigned();
            $table->time('hora')->nullable();
            $table->datetime('fechaInicio');
            $table->datetime('fechaFin')->nullable();
            $table->boolean('todoElDia')->nullable(); 
            $table->mediumtext('titulo')->nullable();
            $table->string('color')->nullable(); 
            $table->string('observacion')->nullable();

            /*****************************************************
                            DATOS TABLA EPILEPSIA
            *****************************************************/
            //$table->datetime('fecha')->nullable(); 
            $table->integer('numCrisisDelDia')->unsigned()->nullable(); //COMPROBAR
            //$table->datetime('hora')->nullable(); 
            $table->integer('duracion')->unsigned()->nullable();
            $table->boolean('perdidaConciencia')->nullable(); 
            $table->boolean('relajaEsfinteres')->nullable(); 
            $table->boolean('convulsiones')->nullable(); 
            $table->boolean('lesionesFisicas')->nullable(); 
            $table->time('ultimaToma')->nullable(); 
            /*****************************************************
                            FIN DATOS TABLA EPILEPSIA
            *****************************************************/

            /*****************************************************
                            DATOS TABLA HUMOR
            *****************************************************/
            $table->boolean('notableManiaco')->nullable();
            $table->boolean('moderaManiaco')->nullable();
            $table->boolean('leveManiaco')->nullable();
            $table->boolean('normal')->nullable();
            $table->boolean('leveDepresivo')->nullable();
            $table->boolean('moderaDepresivo')->nullable();
            $table->boolean('notableDepresivo')->nullable();
            /*****************************************************
                            FIN DATOS TABLA HUMOR
            *****************************************************/

            /*****************************************************
                            DATOS TABLA INCIDENCIA
            *****************************************************/
            //$table->integer('tipo')->unsigned()->nullable();
            //$table->datetime('hora');
            //$table->integer('codigoInci')->unsigned()->nullable();
            $table->boolean('verbal')->nullable();
            $table->boolean('fisica')->nullable();
            $table->boolean('autolesion')->nullable();
            $table->boolean('objetos')->nullable();
            $table->boolean('ofensiva')->nullable();
            $table->boolean('noColabora')->nullable();
            $table->string('antes')->nullable();
            $table->string('queHizo')->nullable(); 
            $table->string('despues')->nullable();
            /*****************************************************
                            FIN DATOS TABLA INCIDENCIA
            *****************************************************/

            /*****************************************************
                            DATOS TABLA SUEÑO
            *****************************************************/
            //ESTA DE PRUEBA, PROBABLEMENTE SE BORREN ESTOS DATOS ANTES DE ENTREGAR!!!!!
            //$table->boolean('hayComenta')->nullable();
            //$table->string('comenta')->nullable(); 
            $table->integer('horasDormidas')->nullable();
            /*****************************************************
                            FIN DATOS TABLA SUEÑO
            *****************************************************/
            $table->enum('turno', ['Mañana','Tarde','Noche']); //NUEVO!!!!!!!!!!


            //crear claves foraneas
            $table->foreign('id_persona')->references('id')->on('persona')->onDelete('cascade');
            $table->foreign('id_tipoEvento')->references('id')->on('tipoEvento')->onDelete('cascade');
            $table->foreign('id_momentoDia')->references('id')->on('momentoDia')->onDelete('cascade');

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
        Schema::drop('evento');
    }
}
