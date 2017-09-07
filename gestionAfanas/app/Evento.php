<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = "evento";

    protected $fillable = ['id_persona', 'id_tipoEvento', 'id_momentoDia', 'hora', 'fechaInicio', 'fechaFin', 'todoElDia', 'titulo', 'color', 'observacion', 'numCrisisDelDia', 'duracion', 'perdidaConciencia', 'relajaEsfinteres', 'convulsiones', 'lesionesFisicas', 'ultimaToma', 'notableManiaco', 'moderaManiaco', 'leveManiaco', 'normal', 'leveDepresivo', 'moderaDepresivo', 'notableDepresivo', 'verbal', 'fisica', 'autolesion', 'objetos', 'ofensiva', 'noColabora', 'antes', 'queHizo', 'despues', 'turno'];

    //protected $fillable = ['id_persona', 'id_tipoEvento', 'id_momentoDia', 'hora', 'fechaInicio', 'fechaFin', 'todoElDia', 'titulo', 'color', 'observacion', 'numCrisisDelDia', 'duracion', 'perdidaConciencia', 'relajaEsfinteres', 'convulsiones', 'lesionesFisicas', 'ultimaToma', 'notableManiaco', 'moderaManiaco', 'leveManiaco', 'normal', 'leveDepresivo', 'moderaDepresivo', 'notableDepresivo', 'tipo', 'codigoInci', 'verbal', 'fisica', 'autolesion', 'objetos', 'ofensiva', 'noColabora', 'antes', 'queHizo', 'despues', 'hayComenta', 'comenta', 'horasDormidas', 'turno'];

    protected $hidden = ['id'];

    //relacion Eventos - MomentoDias
	public function momentoDia()
	{
		return $this->belongsTo('App\MomentoDia');	
	}

	//relacion Eventos - TipoEventos
	public function tipoEvento()
	{
		return $this->belongsTo('App\TipoEvento');
	}


	//relacion Eventos - Epilepsias
	//public function epilepsias()
	//{
	//	return $this->hasMany('App\Epilepsia');
	//}

	//relacion Eventos - Humores
	//public function humores()
	//{
	//	return $this->hasMany('App\Humor');
	//}

	//relacion Eventos - Incidenciaas
	//public function incidencias()
	//{
	//	return $this->hasMany('App\Incidencia');
	//}

	//relacion Eventos - Suenios
	//public function suenios()
	//{
	//	return $this->hasMany('App\Suenio');
	//}

	//relacion Eventos - Personas
	public function persona()
	{
		return $this->belongsTo('App\Persona', 'id_persona');
	}
}
