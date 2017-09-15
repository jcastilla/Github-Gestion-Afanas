<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use App\Http\Requests;

class CalendarController extends Controller
{
    public function index($idPersona)
    {
        $data = array(); //declaramos un array principal que va contener los datos
        //obtenemos todos los eventos donde coincida la ID de la persona con el evento ya creado
        $id_persona = Evento::where('id_persona', $idPersona)->get();
        $id = Evento::where('id_persona', $idPersona)->lists('id') ;
        $titulo = Evento::where('id_persona', $idPersona)->lists('titulo');
        $fechaInicio = Evento::where('id_persona', $idPersona)->lists('fechaInicio');
        $fechaFin = Evento::where('id_persona', $idPersona)->lists('fechaFin');
        $allDay = Evento::where('id_persona', $idPersona)->lists('todoElDia');
        $background = Evento::where('id_persona', $idPersona)->lists('color');
        $observ = Evento::where('id_persona', $idPersona)->lists('observacion');
        $id_tipoEvento = Evento::where('id_persona', $idPersona)->lists('id_tipoEvento');
        $id_momentoDia = Evento::where('id_persona', $idPersona)->lists('id_momentoDia');
        $hora = Evento::where('id_persona', $idPersona)->lists('hora');

        //datos especificos de epilepsia
        $duracion = Evento::where('id_persona', $idPersona)->lists('duracion');
        $ultimaToma = Evento::where('id_persona', $idPersona)->lists('ultimaToma');
        $perdidaConciencia = Evento::where('id_persona', $idPersona)->lists('perdidaConciencia');
        $relajaEsfinteres = Evento::where('id_persona', $idPersona)->lists('relajaEsfinteres');
        $convulsiones = Evento::where('id_persona', $idPersona)->lists('convulsiones');
        $lesionesFisicas = Evento::where('id_persona', $idPersona)->lists('lesionesFisicas');

        //datos especificos de sueño
        $horasDormidas = Evento::where('id_persona', $idPersona)->lists('horasDormidas');

        //datos especificos de incidencias comportamentales
        $antes = Evento::where('id_persona', $idPersona)->lists('antes');
        $queHizo = Evento::where('id_persona', $idPersona)->lists('queHizo');
        $despues = Evento::where('id_persona', $idPersona)->lists('despues');

        //turnos
        $turno = Evento::where('id_persona', $idPersona)->lists('turno');

        $count = count($id_persona); //contamos los ids obtenidos para saber el numero exacto de eventos
 
        //$data = CalendarioEvento::select('id', 'titulo', 'fechaIni', 'fechaFin', 'todoeldia', 'todoeldia')->get(); //laravel convertira a json automaticamente﻿

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
                "id_momentoDia"=>$id_momentoDia[$i],
                "hora"=>$hora[$i],
                "id"=>$id[$i],
                "duracion"=>$duracion[$i],
                "ultimaToma"=>$ultimaToma[$i],
                "perdidaConciencia"=>$perdidaConciencia[$i],
                "relajaEsfinteres"=>$relajaEsfinteres[$i],
                "convulsiones"=>$convulsiones[$i],
                "lesionesFisicas"=>$lesionesFisicas[$i],
                "horasDormidas"=>$horasDormidas[$i],
                "antes"=>$antes[$i],
                "queHizo"=>$queHizo[$i],
                "despues"=>$despues[$i],
                "turno"=>$turno[$i]
            );
        }



        //$datosEpilepsias = DB::table('epilepsia')->where('id_evento', $id)->first();
        //dd($datosEpilepsias);
             
        json_encode($data); //convertimos el array principal $data a un objeto Json 
       return $data; //para luego retornarlo y estar listo para consumirlo

    }

    public function create()
    {
        //Valores recibidos via ajax
        $persona = $_POST['id_persona'];
        $tipoEvento = $_POST['id_tipoEvento'];
        $momentoDia = $_POST['id_momentoDia'];
        $time = $_POST['hora'];
        $start = $_POST['start'];
        $title = $_POST['title'];
        $back = $_POST['background'];
        $texto = null;

        //Insertando evento a base de datos
        $evento = new Evento;
        $evento->id_persona = $persona;
        $evento->id_tipoEvento = $tipoEvento;
        $evento->id_momentoDia = $momentoDia;
        $evento->hora = $time;
        $evento->fechaInicio = $start;
        $evento->todoElDia = true;
        $evento->titulo = $title;
        $evento->color = $back;
        $evento->observacion = $texto;

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

        //$evento = Evento::where('id', $id)->get();
        $evento = Evento::find($id);

        //formulario de apetito, que necesita guardar los radio buttons 
        if($_POST['id_tipoEvento'] == 3)
            $evento->id_momentoDia = $_POST['id_momentoDia'];

        //formulario de higiene
        if($_POST['id_tipoEvento'] == 9 && $_POST['opcion'] == '0')
        {
            $evento->id_momentoDia = $_POST['id_momentoDia'];
            $evento->turno = $_POST['turnos'];
        }

        //formulario de esfinteres
        if($_POST['id_tipoEvento'] == 10 && $_POST['opcion'] == '0')
        {
            $evento->id_momentoDia = $_POST['id_momentoDia'];
            $evento->turno = $_POST['turnos'];
        }

        //formulario de correcion en la mesa
        if($_POST['id_tipoEvento'] == 11)
            $evento->id_momentoDia = $_POST['id_momentoDia'];
         

        if($end=='NULL')
            $evento->fechaFin=NULL;
        else
            $evento->fechaFin=$end;
        
        $evento->fechaInicio=$start;
        $evento->todoElDia=$allDay;
        $evento->color=$back;
        $evento->titulo=$title;
        $evento->observacion=$texto;

        //formulario de crisis epilepticas
        if($_POST['id_tipoEvento'] == 2 && $_POST['opcion'] == '0')
        {
            $hora = $_POST['hora'];
            $durCrisis = $_POST['duracion'];
            $ultiToma = $_POST['ultimaToma'];
            $radioPerdida = $_POST['perdidaConciencia'];
            $radioRelajacion = $_POST['relajaEsfinteres'];
            $radioConvulsion = $_POST['convulsiones'];
            $radioLesion = $_POST['lesionesFisicas'];

            $evento->numCrisisDelDia = 1; //NO SE GUARDA NADA REALMENTE
            $evento->hora = $hora;
            $evento->duracion = $durCrisis;
            $evento->perdidaConciencia = $radioPerdida;
            $evento->relajaEsfinteres = $radioRelajacion;
            $evento->convulsiones = $radioConvulsion;
            $evento->lesionesFisicas = $radioLesion;
            $evento->ultimaToma = $ultiToma;
        }

        //formulario de sueños
        if($_POST['id_tipoEvento'] == 4 && $_POST['opcion'] == '0')
        {
            $horasDormidas = $_POST['horasDormidas'];
            $evento->horasDormidas = $horasDormidas;
        }

        //formulario de incidencias comportamentales
        if($_POST['id_tipoEvento'] == 7 && $_POST['opcion'] == '0')
        {
            $antes = $_POST['antes'];
            $queHizo = $_POST['queHizo'];
            $despues = $_POST['despues'];
            $evento->antes = $antes;
            $evento->queHizo = $queHizo;
            $evento->despues = $despues;
        }

        $evento->save();
   }
 
    public function delete()
    {
        //Valor id recibidos via ajax
        $id = $_POST['id'];

        Evento::destroy($id);
   }
}

