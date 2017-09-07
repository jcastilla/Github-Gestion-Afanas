<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "persona";

    protected $fillable = ['id_usuario', 'id_centro', 'nombre', 'apellidos', 'fechaIngreso', 'fechaNacimiento', 'generoPersona', 'regimenPersona', 'orienta', 'nif', 'personaContacto', 
    	'telContacto', 'numSeguridadSocial', 'centroSalud', 'medicoCabecera', 'medicacion', 'vacunacion'];

    //relacion persona - centro
    public function centro()
    {
    	return $this->belongsTo('App\Centro', 'id_centro');
    }

    //persona - vivienda
    //public function vivienda()
    //{
    //	return $this->belongsTo('App\Vivienda', 'id_vivienda');
    //}

    //persona - persona_diagnostico
    //public function personas_diagnosticos()
    //{
    //    return $this->hasMany('App\Persona_Diagnostico');
    //}

    //persona - persona_tratamiento
    //public function personas_tratamientos()
    //{
    //    return $this->hasMany('App\Persona_Tratamiento');
    //}

    //relacion persona - usuario
    public function usuario()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }

    public function scopeSearch($query, $nombre)
    {
        return $query->where('nombre', 'LIKE', "%$nombre%");
    }
}
