<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MomentoDia extends Model
{
    protected $table = "momentoDia";

    protected $fillable = ['nombre'];

    //relacion Incidencia - Eventos
    public function eventos()
    {
    	return $this->hasMany('App\Evento');
    }

}
