<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventoNoche;
use App\Http\Requests;

class NocheCalendarController extends Controller
{
    public function index($idPersona)
    {
        //dd($idPersona);
    	$data = array(); //declaramos un array principal que va contener los datos
        //obtenemos todos los eventos donde coincida la ID de la persona con el evento ya creado
        $id_persona = EventoNoche::where('id_persona', $idPersona)->get();
        $id = EventoNoche::where('id_persona', $idPersona)->lists('id');
        $id_tipoEvento = EventoNoche::where('id_persona', $idPersona)->lists('id_tipoEvento');
        //$id_momentoDia = EventoNoche::where('id_persona', $idPersona)->lists('id_momentoDia');
        $hora = EventoNoche::where('id_persona', $idPersona)->lists('hora');
        $fechaInicio = EventoNoche::where('id_persona', $idPersona)->lists('fechaInicio');
        $fechaFin = EventoNoche::where('id_persona', $idPersona)->lists('fechaFin');
        $allDay = EventoNoche::where('id_persona', $idPersona)->lists('todoElDia');
        $titulo = EventoNoche::where('id_persona', $idPersona)->lists('titulo');
        $background = EventoNoche::where('id_persona', $idPersona)->lists('color');
        $observ = EventoNoche::where('id_persona', $idPersona)->lists('observacion');
        $horasDormidas = EventoNoche::where('id_persona', $idPersona)->lists('horasDormidas');

        $count = count($id_persona); //contamos los ids obtenidos para saber el numero exacto de eventos

        //hacemos un ciclo para anidar los valores obtenidos a nuestro array principal $data
        for($i=0;$i<$count;$i++){
            $data[$i] = array(
                "title"=>$titulo[$i],
                "start"=>$fechaInicio[$i],
                "end"=>$fechaFin[$i],
                "allDay"=>$allDay[$i],
                "backgroundColor"=>$background[$i],
                "observ"=>$observ[$i],
                "id_persona"=>$id_persona[$i],
                "id_tipoEvento"=>$id_tipoEvento[$i],
                //"id_momentoDia"=>$id_momentoDia[$i],
                "hora"=>$hora[$i],
                "id"=>$id[$i],
                "horasDormidas"=>$horasDormidas[$i]
            );
        }

        json_encode($data); //convertimos el array principal $data a un objeto Json 
       return $data; //para luego retornarlo y estar listo para consumirlo
    }

    public function create()
    {
    	//Valores recibidos via ajax
        $persona = $_POST['id_persona'];
        $tipoEvento = $_POST['id_tipoEvento'];
        //$momentoDia = $_POST['id_momentoDia'];
        $time = $_POST['hora'];
        $start = $_POST['start'];
        $title = $_POST['title'];
        $back = $_POST['background'];

        //1->true TODO EL DIA, 0->false
        //$allDay = $_POST['allday']; 
        $texto = null;

        //Insertando evento a base de datos
        $evento = new EventoNoche;
        $evento->id_persona = $persona;
        $evento->id_tipoEvento = $tipoEvento;
        //$evento->id_momentoDia = $momentoDia;
        $evento->hora = $time;
        $evento->fechaInicio = $start;
        $evento->todoElDia = true;
        $evento->titulo = $title;
        $evento->color = $back;
        $evento->observacion = $texto;

        //dd($evento);

        $evento->save();
    }

    public function update()
    {
    	//Valores recibidos via ajax
        $id = $_POST['id'];
        $title = $_POST['title'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $allDay = $_POST['allday'];
        $back = $_POST['background'];
        $texto = $_POST['observ'];

        $evento = EventoNoche::find($id);

        if($end=='NULL')
            $evento->fechaFin=NULL;
        else
            $evento->fechaFin=$end;
        
        $evento->fechaInicio=$start;
        $evento->todoElDia=$allDay;
        $evento->color=$back;
        $evento->titulo=$title;
        $evento->observacion=$texto;

        if($_POST['opcion'] == '0')
        {
            $horasDormidas = $_POST['horasDormidas'];
            $evento->horasDormidas=$horasDormidas;
        }

        $evento->save();
    }

    public function delete()
    {
    	//Valor id recibidos via ajax
        $id = $_POST['id'];

        EventoNoche::destroy($id);
    }
}
