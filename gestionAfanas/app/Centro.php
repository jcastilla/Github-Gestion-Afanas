<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = "centro";

    protected $fillable = ['nombre','direccion','poblacion'];

    //relacion 1 a muchos con personas
    public function personas()
    {
    	return $this->hasMany('App\Persona');
    }

    public function scopeSearch($query, $nombre)
    {
        return $query->where('nombre', 'LIKE', "%$nombre%");
    }
}
