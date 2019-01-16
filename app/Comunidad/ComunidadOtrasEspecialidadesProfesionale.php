<?php

namespace App\Comunidad;

use Illuminate\Database\Eloquent\Model;

class ComunidadOtrasEspecialidadesProfesionale extends Model
{
    protected $guarded=[];
    
    public function Profesional(){
        return $this->belongsTo('App\Comunidad\ComunidadProfesionale','comProf_id');
    }
}
