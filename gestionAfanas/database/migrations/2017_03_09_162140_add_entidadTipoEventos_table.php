<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntidadtipoeventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidadTipoEvento', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('nombre', ['Estado físico', 'Crisis epilépticas', 'Apetito', 'Sueño', 'Consultas y hospitalizaciones', 'Estado mental', 'Incidencias comportamentales', 'Problemas de conducta', 'Higiene', 'Control de esfínteres', 'Corrección en la mesa', 'Ocupacional', 'Vivienda', 'Relaciones sociales', 'Humor bipolar', 'Incidencia']);
            
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
        Schema::drop('entidadTipoEvento');
    }
}
