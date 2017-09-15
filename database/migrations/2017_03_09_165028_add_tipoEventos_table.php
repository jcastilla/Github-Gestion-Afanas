<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoeventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipoEvento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_entidadTipoEvento')->unsigned();
            $table->enum('nombre', ['Digestivo', 'Respiratorio', 'Circulatorio', 'Cutáneo', 'Locomotor', 'Sensorial', 'Malestar (pre) menstrual', 'Efectos psicofármacos', 'Buco-dental', 'Dolor de cabeza (fiebre, cambio estacional...)','1ª crisis del dia', '2ª crisis del dia', '3ª crisis del dia', '4ª crisis del dia', '5ª crisis del dia', '6ª crisis del dia', '7ª crisis del dia', '8ª crisis del dia', '9ª crisis del dia', '10ª crisis del dia', 'Nulo', 'Pérdida','Excesivo', 'Bulimia', 'Bebe poco', 'Bebe demasiado', 'Ha comido forzado', 'Le cuesta dormirse', 'Se despierta durante la noche', 'Se despierta demasiado temprano', 'No duerme nada', 'Duerme demasiado', 'Somnolencia diurna', 'Consulta Médico', 'Consulta Psicólogo', 'Consulta Psiquiatra', 'Consulta de Enfermería', 'Cambio de medicación', 'Visita CAP', 'Visita CSM', 'Visita Angeles Nocturnos', 'Hospitalización', 'USM' , 'Irritabilidad', 'Falta de energía y/o fatigabilidad ', 'Tensión Muscular', 'Tensión Nerviosa', 'Inquietud', 'Preocupaciones', 'Ansiedad', 'Tristeza o depresión', 'Humor expansivo o euforia', 'Delirios', 'Alucinaciones Auditivas', 'Alucinaciones Visuales', 'Alucinaciones de otros sentidos (olf., tacto…)', 'Desorientación', 'Problemas de memoria', 'Heteroagresividad Verbal', 'Heteroagresividad Física', 'Autolesión', 'Destrucción de objetos', 'Social ofensiva', 'No colaboradora', 'Disruptiva', 'Estereotipias', 'Retraimiento', 'Falta de atención', 'Mutismo', 'Ducha', 'Afeitado', 'Higiene bucal', 'Enuresis dentro', 'Enuresis fuera', 'Encopresis dentro', 'Encopresis fuera', 'Regla', 'Laxante', 'Tira cubiertos', 'Quita comida', 'No espera', 'Variación en el volumen de trabajo', 'Cambio de grupo de trabajo', 'Cambio de taller', 'Incremento de responsabilidad', 'Penalización en el sueldo por mal comportamiento', 'Temperaturas extremas', 'Disminución del rendimiento', 'Se ausenta del taller o centro sin previo aviso', 'Castigo por mal comportamiento', 'Cambios menores en condic. de vida (nueva habitación o compañero)', 'Ausencia temporal de la madre/padre', 'Aumento de problemas con el vecindario', 'Carece de espacio personal/privado', 'Cambios mayores en condic. de vida (nueva casa)', 'Rotaciones frecuentes de vivienda', 'Se ausenta de la residencia sin previo aviso', 'Cambio importante en las actividades sociales (tipo o cantidad)', 'Un amigo se va del trabajo o de la residencia', 'Enfermedad grave de familiar cercano o amigo', 'Muerte de familiar cercano o amigo', 'Frustación en relaciones íntimas', 'Problemas de relación con los demás', 'Recibe visita forzada', 'Recibe visita no forzada', 'Visita a familiares', 'Visita a amigos', 'Notablemente maníaco', 'Moderadamente maníaco', 'Levemente maníaco', 'Humor normal durante todo el dia', 'Levemente depresivo', 'Moderadamente depresivo', 'Notablemente depresivo','Incidencia']);
            
            //crear claves foraneas
            $table->foreign('id_entidadTipoEvento')->references('id')->on('entidadTipoEvento')->onDelete('cascade');

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
        Schema::drop('tipoEvento');
    }
}
