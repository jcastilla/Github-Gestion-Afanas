<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Persona;

class TestController extends Controller
{
    public function view($id)
    {
    	$persona = Persona::find($id);
		$persona->centro;
		$persona->usuario;

		//dd($persona);

		return view('test.index', ['persona' => $persona]);
    }
}
