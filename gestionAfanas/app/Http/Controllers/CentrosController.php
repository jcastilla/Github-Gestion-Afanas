<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Centro;
use App\Persona;
use Laracasts\Flash\Flash;
use App\Http\Requests\CentroRequest;

class CentrosController extends Controller
{
	public function index(Request $request)
    {
        $centros = Centro::search($request->name)->orderBy('id', 'ASC')->paginate(5);
        //el primer user que manda es el nombre de la variable que va a utilizar en la vista, el segundo $users es la variable que estamos utilizando aqui
        return view('admin.centros.index')->with('centros', $centros);
    }

    public function create()
    {
    	return view('admin.centros.create');
    }

    public function store(CentroRequest $request)
    {
    	$centro = new Centro($request->all());
    	$centro->save();

        Flash::success("Se ha registrado el centro " . $centro->nombre . " de forma correcta!");
    	
    	return redirect()->route('admin.centros.index');
    }

    public function destroy($id)
    {
        $centro = Centro::find($id);

        //tenemos que mirar si esta asociado a alguna persona para que no se pueda borrar.
        $id_persona = Persona::where('id_centro', $id)->lists('id');

        //si no tiene ninguna operacion asociada se elimina
        if($id_persona->isEmpty())
        {
            $centro->delete();
            Flash::error('El centro ' .$centro->nombre . ' ha sido borrado de forma correcta!');
        }
        else
            Flash::warning('El centro ' .$centro->nombre . ' no se puede borrar, está asociado a una persona.');


        //$centro->delete();

        //Flash::error('El centro ' .$centro->nombre . ' ha sido borrado de forma correcta!');
        return redirect()->route('admin.centros.index');
    }

    public function edit($id)
    {
        $centro = Centro::find($id);
        return view('admin.centros.edit')->with('centro', $centro);
    }

    public function update(CentroRequest $request, $id)
    {
        $centro = Centro::find($id);
        $centro->fill($request->all());
        $centro->save();

        Flash::warning('El centro ' . $centro->nombre . ' ha sido editado correctamente!');
        return redirect()->route('admin.centros.index');
    }
}
