<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table = "tipoEvento";

    protected $fillable = ['id_entidadTipoEvento', 'nombre'];

    //relacion TipoEvento - EntidadTipoEvento
    public function entidadTipoEvento()
    {
    	return $this->belongsTo('App\EntidadTipoEvento');
    }
    
    //relacion TipoEvento - Evento
    public function eventos()
    {
    	return $this->hasMany('App\Evento');
    }
}
