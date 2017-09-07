<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoNoche extends Model
{
    protected $table = "eventoNoche";

    protected $fillable = ['id_persona', 'id_tipoEvento', 'hora', 'fechaInicio', 'fechaFin', 'todoElDia', 'titulo', 'color', 'observacion', 'horasDormidas'];

    //protected $fillable = ['id_persona', 'id_tipoEvento', 'hora', 'fechaInicio', 'fechaFin', 'todoElDia', 'titulo', 'color', 'observacion', 'v1', 'v1m', 'v2', 'v2m', 'v3', 'v3m', 'v4', 'v4m', 'c1', 'c1m', 'c2', 'c2m', 'c3', 'c3m', 'c4', 'c4m', 'c5', 'c5m', 'c6', 'c6m', 'c7', 'c7m', 'c8', 'c8m', 'c9', 'hayComenta', 'comenta', 'horasDormidas'];

    protected $hidden = ['id'];

    //relacion Eventos - MomentoDias
	/*public function momentoDia()
	{
		return $this->belongsTo('App\MomentoDia');	
	}*/

	//relacion Eventos - TipoEventos
	public function tipoEvento()
	{
		return $this->belongsTo('App\TipoEvento');
	}

	//relacion Eventos - Personas
	public function persona()
	{
		return $this->belongsTo('App\Persona', 'id_persona');
	}
}
