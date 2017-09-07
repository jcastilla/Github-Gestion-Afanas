<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PersonaRequest;


use DateTime;
use App\Centro;
use App\Persona;
use Laracasts\Flash\Flash;

//use App\Vivienda;
//use App\Color;
use App\EntidadTipoEvento;
use App\TipoEvento;
use App\Evento;
use App\MomentoDia;
use App\User;

//para obtener el nombre de usuario
use Illuminate\Support\Facades\Auth;

class PersonasController extends Controller
{
    public function index(Request $request)
    {
        $personas = Persona::search($request->name)->orderBy('id', 'ASC')->paginate(5);
        $personas->each(function($personas){  
            
            //manipulacion de fecha
            $fecha = new DateTime($personas->fechaIngreso);
            $personas->fechaIngreso = $fecha->format('d/m/Y');

            $personas->centro; //del modelo persona.php
            $personas->usuario; //Persona.php
            //$personas->vivienda; //el metodo vivienda
        });

        return view('admin.personas.index')
            ->with('personas', $personas);
    }

    public function create()
    {
    	$centros = Centro::orderBy('nombre', 'ASC')->lists('nombre', 'id');
        //$viviendas = Vivienda::orderBy('numVi', 'ASC')->lists('numVi','id');

    	return view('admin.personas.create')
    		->with('centros', $centros);
            //->with('viviendas', $viviendas);
    }

    public function store(PersonaRequest $request)
    {
    	$persona = new Persona($request->all());
    	$persona->id_usuario = \Auth::user()->id;

        /** RADIO BUTTONS **/

        //option1 -> Hombre
        //option2 -> Mujer

        $generoRadio = $_POST['optionsRadios'];
        if ($generoRadio == "option1")
            $generoRadio = "Hombre";
        else
            $generoRadio = "Mujer";

        $persona->generoPersona = $generoRadio;

        //option1 -> Interno
        //option2 -> Externo

        $generoRadio = $_POST['optionsRadios1'];
        if ($generoRadio == "option1")
            $generoRadio = "Interno";
        else
            $generoRadio = "Externo";

        $persona->regimenPersona = $generoRadio;
        
        //$fechaNacimiento = DateTime::createFromFormat('d/m/Y', $persona->fechaNacimiento);
        //$fechaIngreso = DateTime::createFromFormat('d/m/Y', $persona->fechaIngreso);

        //$persona->fechaNacimiento = $fechaNacimiento->format('Y/m/d');
        //$persona->fechaIngreso = $fechaIngreso->format('Y/m/d');

        $persona->save();
        Flash::success("Se ha registrado la persona " . $anioNacimiento . " de forma correcta!");
        return redirect()->route('admin.personas.index');
    }

    public function edit($id)
    {
        $persona = Persona::find($id);
        $persona->centro;
        $centros = Centro::orderBy('nombre', 'DESC')->lists('nombre', 'id');
        //$persona->vivienda;
        //$viviendas = Vivienda::orderBy('numVi', 'DESC')->lists('numVi', 'id');

        //manipular fecha
        $fechaNacimiento = new DateTime($persona->fechaNacimiento);
        $persona->fechaNacimiento = $fechaNacimiento->format('d/m/Y');

        $fechaIngreso = new DateTime($persona->fechaIngreso);
        $persona->fechaIngreso = $fechaIngreso->format('d/m/Y');

        return view('admin.personas.edit')
                ->with('centros', $centros) //le pasamos la variable a la vista con un nombre
                ->with('persona', $persona);
                //->with('viviendas', $viviendas);
    }

    public function update(PersonaRequest $request, $id)
    {
        $persona = Persona::find($id);
        $persona->fill($request->all());

        /** RADIO BUTTONS **/

        //option1 -> Hombre
        //option2 -> Mujer

        $generoRadio = $_POST['optionsRadios'];
        if ($generoRadio == "option1")
            $generoRadio = "Hombre";
        else
            $generoRadio = "Mujer";

        $persona->generoPersona = $generoRadio;

        //option1 -> Interno
        //option2 -> Externo

        $generoRadio = $_POST['optionsRadios1'];
        if ($generoRadio == "option1")
            $generoRadio = "Interno";
        else
            $generoRadio = "Externo";

        $persona->regimenPersona = $generoRadio;

        //manipulacion de fecha
        $fechaNacimiento = DateTime::createFromFormat('d/m/Y', $persona->fechaNacimiento);
        $fechaIngreso = DateTime::createFromFormat('d/m/Y', $persona->fechaIngreso);

        $persona->fechaNacimiento = $fechaNacimiento->format('Y/m/d');
        $persona->fechaIngreso = $fechaIngreso->format('Y/m/d');

        $persona->save();

        Flash::warning('La persona ' . $persona->nombre . ' ha sido editada correctamente!');
        return redirect()->route('admin.personas.index');
    }

    public function destroy($id)
    {
        $persona = Persona::find($id);
        $persona->delete();

        Flash::error('La persona ' .$persona->nombre . ' ha sido borrado de forma correcta!');
        return redirect()->route('admin.personas.index');
    }

    public function show($id)
    {
        $persona = Persona::find($id);

        $momentoDias = MomentoDia::orderBy('nombre', 'ASC')->lists('nombre', 'id');
        $tipoEventos = TipoEvento::orderBy('id', 'ASC')->lists('nombre', 'id')->ToArray();
        $entidadTipoEventos = EntidadTipoEvento::orderBy('id', 'ASC')->lists('nombre', 'id')->ToArray();

        //cogemos el nombre del usuario que ha creado a la persona
        //si no se ha creado todavia un evento le damos el nombre de la persona logueada
        //si no el nombre de la pesona que lo creó
        $idUsuario = $persona->id_usuario;
        $nombre = User::where('id', $idUsuario)->lists('name');
        if($nombre->isEmpty())
            $usuarioConectado = Auth::user()->name;
        else
            $usuarioConectado = $nombre[0];

        //$usuarioConectado = User::where('id', $idUsuario)->lists('name');
  
        return view('admin.personas.show')
                ->with('persona', $persona)
                ->with('momentoDias', $momentoDias)
                ->with('tipoEventos', $tipoEventos)
                ->with('entidadTipoEventos', $entidadTipoEventos)
                ->with('usuarioConectado', $usuarioConectado);
    }
}


