<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_centro')->unsigned();
            //$table->integer('id_vivienda')->unsigned();
            $table->string('nombre', 60);
            $table->string('apellidos', 255);
            $table->datetime('fechaIngreso');
            $table->datetime('fechaNacimiento');
            $table->enum('generoPersona', ['Hombre','Mujer']);
            $table->enum('regimenPersona', ['Interno','Externo']);
            $table->string('orienta', 255);
            $table->string('nif');
            $table->string('personaContacto')->nullable();
            $table->integer('telContacto')->unsigned();
            $table->string('numSeguridadSocial');
            $table->string('centroSalud');
            $table->string('medicoCabecera')->nullable();
            $table->string('medicacion')->nullable();
            $table->string('vacunacion')->nullable();

            //crear claves foraneas
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_centro')->references('id')->on('centro')->onDelete('cascade');
            //$table->foreign('id_vivienda')->references('id')->on('vivienda')->onDelete('cascade');
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
        Schema::drop('persona');
    }
}
