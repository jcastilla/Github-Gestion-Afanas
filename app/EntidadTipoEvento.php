<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntidadTipoEvento extends Model
{
    protected $table = "entidadTipoEvento";

    protected $fillable = ['nombre'];

    //relacion EntidadTipoEvento - TipoEvento
    public function tipoEventos()
    {
    	return $this->hasMany('App\TipoEvento');
    }
}
