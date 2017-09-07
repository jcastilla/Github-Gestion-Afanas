<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Persona;
use Laracasts\Flash\Flash;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::search($request->name)->orderBy('id', 'ASC')->paginate(5);
        //el primer user que manda es el nombre de la variable que va a utilizar en la vista, el segundo $users es la variable que estamos utilizando aqui
        return view('admin.users.index')->with('users', $users);
    }

    public function create()
    {
    	return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
    	$user = new User($request->all());
    	$user->password = bcrypt($request->password);
    	$user->save();

        Flash::success("Se ha registrado " . $user->name . " de forma correcta!");
    	
    	return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        //tenemos que mirar si esta asociado a alguna persona para que no se pueda borrar.
        $id_persona = Persona::where('id_usuario', $id)->lists('id');

        //si no tiene ninguna operacion asociada se elimina
        if($id_persona->isEmpty())
        {
            $user->delete();
            Flash::error('El usuario ' .$user->name . ' ha sido borrado de forma correcta!');
        }
        else
            Flash::warning('El usuario ' .$user->name . ' no se puede borrar, está asociado a una persona.');


       // $user->delete();

        //Flash::error('El usuario ' .$user->name . ' ha sido borrado de forma correcta!');
        return redirect()->route('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit')->with('user', $user);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->all());
        //$user->name = $request->name;
        //$user->email = $request->email;
        //$user->type = $request->type;
        $user->save();

        Flash::warning('El usuario ' . $user->name . ' ha sido editado correctamente!');
        return redirect()->route('admin.users.index');
    }
}
